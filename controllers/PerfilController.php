<?php
namespace Controllers;

use MVC\Router;
use Model\Usuario;

class PerfilController{
    public static function perfil(Router $router) {
        session_start();
        isAuth();
        $alertas = [];
        $usuario = Usuario::find($_SESSION['id']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $usuario->sincronizar($_POST);
            
            $alertas = $usuario->validarPerfil();
            
            if (empty($alertas)) {
                $existeUsuario = Usuario::where('email', $usuario->email);
                if ($existeUsuario && $existeUsuario->id !== $usuario->id) {
                    Usuario::setAlerta('error', 'Cuenta Ya registrada');
                }else {
                    $usuario->guardar();
    
                    Usuario::setAlerta('exito', 'Guardado Correctamente');
    
                    $_SESSION['nombre'] = $usuario->nombre;
                    $_SESSION['email'] = $usuario->email;
                }
            }
        }
        $alertas = $usuario->getAlertas();
        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'alertasPerfil' => $alertas,
            'nombre' => $_SESSION['nombre'],
            'email' => $_SESSION['email']
        ]);
    }
    public static function contraseña(Router $router){
        session_start();
        isAuth();
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = Usuario::find($_SESSION['id']);
            
            $usuario->sincronizar($_POST);
            
            $alertas = $usuario->nuevo_password();
            
            if (empty($alertas)) {
                $resultado = $usuario->comprobar_password();
                if ($resultado) {
                    $usuario->password = $usuario->password_nuevo;
                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);
                    $usuario->hashPassword();
                    $guardado = $usuario->guardar();
                    if ($guardado) {
                        Usuario::setAlerta('exito', 'password guardado correctamente');
                        $alertas = $usuario->getAlertas();
                    }
                } else {
                    Usuario::setAlerta('error', 'password incorrecto');
                    $alertas = $usuario->getAlertas();
                }
            }
        }
        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'alertasContraseña' => $alertas,
            'nombre' => $_SESSION['nombre'],
            'email' => $_SESSION['email']
        ]);
    }
}