<?php

namespace Controllers;

use Model\Categoria;
use Model\Egresos;
use Model\Forma;
use Model\Ingresos;
use MVC\Router;

class DashboardController
{
    public static function index(Router $router) {
        session_start();
        isAuth();
        $ingresos = Ingresos::sumCondition('monto', 'usuario_id', $_SESSION['id']);
        $egresos = Egresos::sumCondition('monto', 'usuario_id', $_SESSION['id']);
        $balance = $ingresos - $egresos;
        $formas_pago = Forma::belongsTo('usuario_id', $_SESSION['id']);
        $router->render('dashboard/inicio', [
            'titulo' => 'Inicio',
            'ingresos' => round($ingresos,2),
            'egresos' => round($egresos,2),
            'balance' => round($balance, 2),
            'formas_pago' => $formas_pago
        ]);
    }

    public static function categorias(Router $router) {
        session_start();
        isAuth();
        $router->render('dashboard/categorias', [
            'titulo' => 'Categorias',
        ]);
    }
    
    public static function formas(Router $router) {
        session_start();
        isAuth();
        $router->render('dashboard/formas', [
            'titulo' => 'Formas de Pago',
        ]);
    }

    public static function egresos(Router $router) {
        session_start();
        isAuth();
        $categorias = Categoria::belongsTo('usuario_id', $_SESSION['id']);
        $formas_pago = Forma::belongsTo('usuario_id', $_SESSION['id']);
        $router->render('dashboard/egresos', [
            'titulo' => 'Egresos',
            'categorias' => $categorias,
            'formas_pago' => $formas_pago
        ]);
    }

    public static function ingresos(Router $router) {
        session_start();
        isAuth();
        $formas_pago = Forma::belongsTo('usuario_id', $_SESSION['id']);
        $router->render('dashboard/ingresos', [
            'titulo' => 'Ingresos',
            'formas_pago' => $formas_pago
        ]);
    }
}
