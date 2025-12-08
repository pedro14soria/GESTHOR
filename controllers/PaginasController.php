<?php
namespace Controllers;

use MVC\Router;


class PaginasController {
    public static function inicio(Router $router)
    {
        $router->render('auth/index', [
            'titulo' => 'Inicio'
        ]);
    }

    public static function error(Router $router) {
        $router->render('auth/error', [
            'titulo' => 'ERROR 404'
        ]);
    }
}