<?php
namespace Controllers;

use Model\Forma;
use Model\Usuario;

class FormaController {

    public static function index() {
        session_start();
        isAuth();
        $formas = Forma::belongsTo('usuario_id', $_SESSION['id']);
        $formasFiltradas = [];
        foreach ($formas as $forma) {
            $formasFiltradas[] = [
                'id' => $forma->id,
                'nombre' => $forma->nombre
            ];
        }
        echo json_encode(['formas' => $formasFiltradas]);
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
            $existeArr = Forma::whereArray(['nombre' => $nombre, 'usuario_id' => $usuario_id]);
            $existe = !empty($existeArr) ? $existeArr[0] : null;
            if ($existe) {
                echo json_encode([
                    'tipo' => 'error',
                    'mensaje' => 'Ya existe una forma de pago con ese nombre.'
                ]);
                return;
            }
            $forma = new Forma;
            $forma->nombre = $nombre;
            $forma->usuario_id = $usuario_id;
            $resultado = $forma->guardar();
            if ($resultado && isset($resultado['id'])) {
                $respuesta = [
                    "tipo" => "exito",
                    "id" => $resultado['id'],
                    "mensaje" => "Forma de Pago guardada correctamente",
                ];
            } else {
                $respuesta = [
                    "tipo" => "error",
                    "mensaje" => "Error al guardar la forma de pago."
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
            $formaArr = Forma::whereArray(['id' => $id, 'usuario_id' => $usuario_id]);
            $for = !empty($formaArr) ? $formaArr[0] : null;
            if (!$for) {
                echo json_encode([
                    'respuesta' => [
                        'tipo' => 'error',
                        'mensaje' => 'No tienes permiso para editar esta Forma de Pago.'
                    ]
                ]);
                return;
            }
            // Evitar duplicados
            $existeArr = Forma::whereArray(['nombre' => $nombre, 'usuario_id' => $usuario_id]);
            $existe = !empty($existeArr) ? $existeArr[0] : null;
            if ($existe && $existe->id != $id) {
                echo json_encode([
                    'respuesta' => [
                        'tipo' => 'error',
                        'mensaje' => 'Ya existe una Forma de Pago con ese nombre.'
                    ]
                ]);
                return;
            }
            $forma = new Forma(['id' => $id, 'nombre' => $nombre, 'usuario_id' => $usuario_id]);
            $resultado = $forma->guardar();
            if ($resultado) {
                $respuesta = [
                    'tipo' => 'exito',
                    'id' => $forma->id,
                    'mensaje' => 'Actualizado correctamente'
                ];
            } else {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Error al actualizar la Forma de Pago.'
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
            $formaArr = Forma::whereArray(['id' => $id, 'usuario_id' => $usuario_id]);
            $for = !empty($formaArr) ? $formaArr[0] : null;
            if (!$for) {
                echo json_encode([
                    'resultado' => false,
                    'mensaje' => 'No tienes permiso para eliminar esta forma de pago.',
                    'tipo' => 'error'
                ]);
                return;
            }
            $forma = new Forma(['id' => $id, 'usuario_id' => $usuario_id]);
            $resultado = $forma->eliminar();
            if ($resultado) {
                $resp = [
                    'resultado' => true,
                    'mensaje' => 'Forma de pago eliminada correctamente',
                    'tipo' => 'exito'
                ];
            } else {
                $resp = [
                    'resultado' => false,
                    'mensaje' => 'Error al eliminar la forma de pago.',
                    'tipo' => 'error'
                ];
            }
            echo json_encode(['resultado' => $resp]);
        }
    }
}