<?php
namespace Controllers;

use Model\Categoria;
use Model\Usuario;

class CategoriaController {

    public static function index() {
        session_start();
        isAuth();
        $categorias = Categoria::belongsTo('usuario_id', $_SESSION['id']);
        $categoriasFiltradas = [];
        foreach ($categorias as $cat) {
            $categoriasFiltradas[] = [
                'id' => $cat->id,
                'nombre' => $cat->nombre
            ];
        }
        echo json_encode(['categorias' => $categoriasFiltradas]);
    }

    public static function crear() {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            session_start();
            isAuth();
            $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
            $usuario_id = $_SESSION['id'];
            // Validación básica
            if ($nombre === '' || strlen($nombre) > 50) {
                echo json_encode([
                    'tipo' => 'error',
                    'mensaje' => 'El nombre es obligatorio y debe tener menos de 50 caracteres.'
                ]);
                return;
            }
            // Sanitizar
            $nombre = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');
            // Evitar duplicados
            $existeArr = Categoria::whereArray(['nombre' => $nombre, 'usuario_id' => $usuario_id]);
            $existe = !empty($existeArr) ? $existeArr[0] : null;
            if ($existe) {
                echo json_encode([
                    'tipo' => 'error',
                    'mensaje' => 'Ya existe una categoría con ese nombre.'
                ]);
                return;
            }
            $categoria = new Categoria;
            $categoria->nombre = $nombre;
            $categoria->usuario_id = $usuario_id;
            $resultado = $categoria->guardar();
            if ($resultado && isset($resultado['id'])) {
                $respuesta = [
                    "tipo" => "exito",
                    "id" => $resultado['id'],
                    "mensaje" => "Categoría guardada correctamente",
                ];
            } else {
                $respuesta = [
                    "tipo" => "error",
                    "mensaje" => "Error al guardar la categoría."
                ];
            }
            echo json_encode($respuesta);
        }
    }
    
    public static function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            session_start();
            isAuth();
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
            $usuario_id = $_SESSION['id'];
            if ($id <= 0 || $nombre === '' || strlen($nombre) > 50) {
                echo json_encode([
                    'respuesta' => [
                        'tipo' => 'error',
                        'mensaje' => 'Datos inválidos para actualizar.'
                    ]
                ]);
                return;
            }
            $nombre = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');
            // Verificar que la categoría pertenezca al usuario
            $catArr = Categoria::whereArray(['id' => $id, 'usuario_id' => $usuario_id]);
            $cat = !empty($catArr) ? $catArr[0] : null;
            if (!$cat) {
                echo json_encode([
                    'respuesta' => [
                        'tipo' => 'error',
                        'mensaje' => 'No tienes permiso para editar esta categoría.'
                    ]
                ]);
                return;
            }
            // Evitar duplicados
            $existeArr = Categoria::whereArray(['nombre' => $nombre, 'usuario_id' => $usuario_id]);
            $existe = !empty($existeArr) ? $existeArr[0] : null;
            if ($existe && $existe->id != $id) {
                echo json_encode([
                    'respuesta' => [
                        'tipo' => 'error',
                        'mensaje' => 'Ya existe una categoría con ese nombre.'
                    ]
                ]);
                return;
            }
            $categoria = new Categoria(['id' => $id, 'nombre' => $nombre, 'usuario_id' => $usuario_id]);
            $resultado = $categoria->guardar();
            if ($resultado) {
                $respuesta = [
                    'tipo' => 'exito',
                    'id' => $categoria->id,
                    'mensaje' => 'Actualizado correctamente'
                ];
            } else {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Error al actualizar la categoría.'
                ];
            }
            echo json_encode(['respuesta' => $respuesta]);
        }
    }
    
    public static function eliminar() {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            session_start();
            isAuth();
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            $usuario_id = $_SESSION['id'];
            if ($id <= 0) {
                echo json_encode([
                    'resultado' => false,
                    'mensaje' => 'ID inválido.',
                    'tipo' => 'error'
                ]);
                return;
            }
            // Verificar que la categoría pertenezca al usuario
            $catArr = Categoria::whereArray(['id' => $id, 'usuario_id' => $usuario_id]);
            $cat = !empty($catArr) ? $catArr[0] : null;
            if (!$cat) {
                echo json_encode([
                    'resultado' => false,
                    'mensaje' => 'No tienes permiso para eliminar esta categoría.',
                    'tipo' => 'error'
                ]);
                return;
            }
            $categoria = new Categoria(['id' => $id, 'usuario_id' => $usuario_id]);
            $resultado = $categoria->eliminar();
            if ($resultado) {
                $resp = [
                    'resultado' => true,
                    'mensaje' => 'Categoría eliminada correctamente',
                    'tipo' => 'exito'
                ];
            } else {
                $resp = [
                    'resultado' => false,
                    'mensaje' => 'Error al eliminar la categoría.',
                    'tipo' => 'error'
                ];
            }
            echo json_encode(['resultado' => $resp]);
        }
    }
}