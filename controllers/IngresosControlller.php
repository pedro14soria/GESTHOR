<?php
namespace Controllers;

use Model\Ingresos;
use Model\Forma;

class IngresosControlller {
    public static function index() {
        session_start();
        isAuth();
        $ingresos = Ingresos::belongsToOrder('usuario_id', $_SESSION['id'], 'DESC');
        $ingresosFiltrados = [];
        foreach ($ingresos as $ingreso) {
            $ingresosFiltrados[] = [
                'id' => $ingreso->id,
                'descripcion' => $ingreso->descripcion,
                'monto' => $ingreso->monto,
                'forma' => Forma::find($ingreso->forma_pago_id)->nombre,
                'fecha' => $ingreso->fecha
            ];
        }
        echo json_encode(['ingresos' => $ingresosFiltrados]);
    }

    public static function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            isAuth();
            $usuario_id = $_SESSION['id'];

            $erroresPertencia = [];
            $for = Forma::whereArray(['id' => intval($_POST['forma_pago_id']), 'usuario_id' => $usuario_id]);
            if (empty($for)) $erroresPertencia[] = 'Error al agregar la forma de pago';
            if(!empty($erroresPertencia)) {
                echo json_encode(['tipo' => 'error', 'mensajes' => $erroresPertencia]);
                return;
            }
            // 3. Instanciar y validar el modelo
            $ingreso = new Ingresos($_POST);
            $ingreso->usuario_id = $usuario_id;
            $alertas = $ingreso->validar();
            if (isset($alertas['error']) && count($alertas['error']) > 0) {
                echo json_encode(['tipo' => 'error', 'mensajes' => $alertas['error']]);
                return;
            }
            // 4. Guardar y responder
            $resultado = $ingreso->guardar();
            $respuesta = ($resultado && isset($resultado['id']))
                ? ['tipo' => 'exito', 'id' => $resultado['id'], "mensajes" => "Ingreso agregado Correctamente"]
                : ['tipo' => 'error', 'id' => $resultado['id'], "mensajes" => "Error al guardar el ingreso"];
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
            $ingresoArr = Ingresos::whereArray(['id' => $id, 'usuario_id' => $usuario_id]);
            $ingreso = !empty($ingresoArr) ? $ingresoArr[0] : null;
            if (!$ingreso) {
                header('Location: /');
                exit;
            }

            // Validar pertenencia de categoria y forma de pago
            $erroresPertencia = [];
            $for = Forma::whereArray(['id' => intval($_POST['forma_pago_id']), 'usuario_id' => $usuario_id]);
            if (empty($for)) $erroresPertencia[] = 'Error al agregar la forma de pago';
            if(!empty($erroresPertencia)) {
                echo json_encode(['tipo' => 'error', 'mensajes' => $erroresPertencia]);
                return;
            }
            // Sincronizar y validar reglas del modelo
            $ingreso->sincronizar($_POST);
            $alertas = $ingreso->validar();
            if (isset($alertas['error']) && count($alertas['error']) > 0) {
                echo json_encode(['tipo' => 'error', 'mensajes' => $alertas['error']]);
                return;
            }
            $resultado = $ingreso->actualizar();
            $respuesta = ($resultado)
                ? ['tipo' => 'exito', "mensajes" => "Ingreso actualizado Correctamente"]
                : ['tipo' => 'error', "mensajes" => "Error al actualizar el ingreso"];
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

            $ingresoArr = Ingresos::whereArray(['id' => $id, 'usuario_id' => $usuario_id]);
            $ingr = !empty($ingresoArr) ? $ingresoArr[0] : null;
            if (!$ingr) {
                echo json_encode([
                    'resultado' => false,
                    'mensaje' => 'No tienes permiso para eliminar este ingreso.',
                    'tipo' => 'error'
                ]);
                return;
            }

            $ingreso = new Ingresos(['id' => $id, 'usuario_id' => $usuario_id]);
            $resultado = $ingreso->eliminar();
            if ($resultado) {
                $resp = [
                    'resultado' => true,
                    'mensaje' => 'Ingreso Eliminado Correctamente',
                    'tipo' => 'exito'
                ];
            } else {
                $resp = [
                    'resultado' => false,
                    'mensaje' => 'Error al eliminar el ingreso',
                    'tipo' => 'error'
                ];
            }
            echo json_encode(['resultado' => $resp]);
        }
    }
}