<?php

require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Model\Egresos;
use Controllers\FormaController;
use Controllers\LoginController;
use Controllers\EgresosControlller;
use Controllers\CategoriaController;
use Controllers\DashboardController;
use Controllers\IngresosControlller;
use Controllers\PaginasController;
use Controllers\ResumenController;

$router = new Router();

$router->get('/', [PaginasController::class, 'inicio']);
$router->get('/404', [PaginasController::class, 'error']);

// Login
$router->get('/login', [LoginController::class, 'login']);
$router->post('/login', [LoginController::class, 'login']);
$router->get('/logout', [LoginController::class, 'logout']);

// Crear Cuenta
$router->get('/crear', [LoginController::class, 'crear']);
$router->post('/crear', [LoginController::class, 'crear']);

// Formulario de olvide mi password
$router->get('/olvide', [LoginController::class, 'olvide']);
$router->post('/olvide', [LoginController::class, 'olvide']);

// Colocar el nuevo password
$router->get('/reestablecer', [LoginController::class, 'reestablecer']);
$router->post('/reestablecer', [LoginController::class, 'reestablecer']);

// Confirmación de Cuenta
$router->get('/mensaje', [LoginController::class, 'mensaje']);
$router->get('/confirmar', [LoginController::class, 'confirmar']);

//Inicio
$router->get('/dashboard/inicio', [DashboardController::class, 'index']);
$router->get('/api/inicio', [ResumenController::class, 'resumen']);

//Categorias
$router->get('/dashboard/categorias', [DashboardController::class, 'categorias']);
//API para categorias
$router->get('/api/categorias', [CategoriaController::class, 'index']);
$router->post('/api/categorias/categoria', [CategoriaController::class, 'crear']);
$router->post('/api/categorias/actualizar', [CategoriaController::class, 'actualizar']);
$router->post('/api/categorias/eliminar', [CategoriaController::class, 'eliminar']);

//Formas de Pago
$router->get('/dashboard/formas', [DashboardController::class, 'formas']);
//API para Formas de pago
$router->get('/api/formas', [FormaController::class, 'index']);
$router->post('/api/formas/forma', [FormaController::class, 'crear']);
$router->post('/api/formas/eliminar', [FormaController::class, 'eliminar']);
$router->post('/api/formas/actualizar', [FormaController::class, 'actualizar']);

//Egresos
$router->get('/dashboard/egresos', [DashboardController::class, 'egresos']);
//API para Egresos
$router->get('/api/egresos', [EgresosControlller::class, 'index']);
$router->post('/api/egresos/egreso', [EgresosControlller::class, 'crear']);
$router->post('/api/egresos/eliminar', [EgresosControlller::class, 'eliminar']);
$router->post('/api/egresos/actualizar', [EgresosControlller::class, 'actualizar']);

//Ingresos
$router->get('/dashboard/ingresos', [DashboardController::class, 'ingresos']);
//API para Egresos
$router->get('/api/ingresos', [IngresosControlller::class, 'index']);
$router->post('/api/ingresos/ingreso', [IngresosControlller::class, 'crear']);
$router->post('/api/ingresos/eliminar', [IngresosControlller::class, 'eliminar']);
$router->post('/api/ingresos/actualizar', [IngresosControlller::class, 'actualizar']);


$router->comprobarRutas();
