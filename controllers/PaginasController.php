<?php
namespace Controllers;

use MVC\Router;


class PaginasController {
    public static function inicio(Router $router)
    {
        session_start();
        $_SESSION = [];
        $router->render('auth/index', [
            'titulo' => 'Inicio'
        ]);
    }

    public static function error(Router $router) {
        session_start();
        $_SESSION = [];
        $router->render('auth/error', [
            'titulo' => 'ERROR 404'
        ]);
    }
}