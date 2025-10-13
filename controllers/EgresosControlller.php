<?php
namespace Controllers;

use Model\Egresos;
use Model\Categoria;
use Model\Forma;

class EgresosControlller {
    public static function index() {
        session_start();
        isAuth();
        $egresos = Egresos::belongsToOrder('usuario_id', $_SESSION['id'], 'DESC');
        $egresosFiltrados = [];
        foreach ($egresos as $egreso) {
            $egresosFiltrados[] = [
                'id' => $egreso->id,
                'descripcion' => $egreso->descripcion,
                'monto' => $egreso->monto,
                'categoria' => Categoria::find($egreso->categoria_id)->nombre,
                'forma' => Forma::find($egreso->forma_pago_id)->nombre,
                'fecha' => $egreso->fecha
            ];
        }
        echo json_encode(['egresos' => $egresosFiltrados]);
    }

    public static function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            isAuth();
            $usuario_id = $_SESSION['id'];

            $erroresPertencia = [];
            $cat = Categoria::whereArray(['id' => intval($_POST['categoria_id']), 'usuario_id' => $usuario_id]);
            if (empty($cat)) $erroresPertencia[] = 'Error al agregar la categoria';
            $for = Forma::whereArray(['id' => intval($_POST['forma_pago_id']), 'usuario_id' => $usuario_id]);
            if (empty($for)) $erroresPertencia[] = 'Error al agregar la forma de pago';
            if(!empty($erroresPertencia)) {
                echo json_encode(['tipo' => 'error', 'mensajes' => $erroresPertencia]);
                return;
            }
            // 3. Instanciar y validar el modelo
            $egreso = new Egresos($_POST);
            $egreso->usuario_id = $usuario_id;
            $alertas = $egreso->validar();
            if (isset($alertas['error']) && count($alertas['error']) > 0) {
                echo json_encode(['tipo' => 'error', 'mensajes' => $alertas['error']]);
                return;
            }
            // 4. Guardar y responder
            $resultado = $egreso->guardar();
            $respuesta = ($resultado && isset($resultado['id']))
                ? ['tipo' => 'exito', 'id' => $resultado['id'], "mensajes" => "Egreso agregado Correctamente"]
                : ['tipo' => 'error', 'id' => $resultado['id'], "mensajes" => "Error al guardar el egreso"];
            echo json_encode($respuesta);
        }
    }

    
    public static function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            isAuth();
            // Validar id correcto
            $id = isset($_POST['id']) ? filter_var($_POST['id'], FILTER_VALIDATE_INT) : null;
            if (!$id) {
                header('Location: /');
                exit;
            }
            $usuario_id = $_SESSION['id'];
            $egresoArr = Egresos::whereArray(['id' => $id, 'usuario_id' => $usuario_id]);
            $egreso = !empty($egresoArr) ? $egresoArr[0] : null;
            if (!$egreso) {
                header('Location: /');
                exit;
            }

            // Validar pertenencia de categoria y forma de pago
            $erroresPertencia = [];
            $cat = Categoria::whereArray(['id' => intval($_POST['categoria_id']), 'usuario_id' => $usuario_id]);
            if (empty($cat)) $erroresPertencia[] = 'Error al agregar la categoria';
            $for = Forma::whereArray(['id' => intval($_POST['forma_pago_id']), 'usuario_id' => $usuario_id]);
            if (empty($for)) $erroresPertencia[] = 'Error al agregar la forma de pago';
            if(!empty($erroresPertencia)) {
                echo json_encode(['tipo' => 'error', 'mensajes' => $erroresPertencia]);
                return;
            }
            // Sincronizar y validar reglas del modelo
            $egreso->sincronizar($_POST);
            $alertas = $egreso->validar();
            if (isset($alertas['error']) && count($alertas['error']) > 0) {
                echo json_encode(['tipo' => 'error', 'mensajes' => $alertas['error']]);
                return;
            }
            $resultado = $egreso->actualizar();
            $respuesta = ($resultado)
                ? ['tipo' => 'exito', "mensajes" => "Egreso actualizado Correctamente"]
                : ['tipo' => 'error', "mensajes" => "Error al actualizar el egreso"];
            echo json_encode($respuesta);
        }
    }   
    
    public static function eliminar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

            $egresoArr = Egresos::whereArray(['id' => $id, 'usuario_id' => $usuario_id]);
            $egr = !empty($egresoArr) ? $egresoArr[0] : null;
            if (!$egr) {
                echo json_encode([
                    'resultado' => false,
                    'mensaje' => 'No tienes permiso para eliminar este egreso.',
                    'tipo' => 'error'
                ]);
                return;
            }

            $egreso = new Egresos(['id' => $id, 'usuario_id' => $usuario_id]);
            $resultado = $egreso->eliminar();
            if ($resultado) {
                $resp = [
                    'resultado' => true,
                    'mensaje' => 'Egreso Eliminado Correctamente',
                    'tipo' => 'exito'
                ];
            } else {
                $resp = [
                    'resultado' => false,
                    'mensaje' => 'Error al eliminar el egreso',
                    'tipo' => 'error'
                ];
            }
            echo json_encode(['resultado' => $resp]);
        }
    }
}