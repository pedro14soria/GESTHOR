<?php
// Ruta: controllers/ResumenController.php
namespace Controllers;

use Model\Egresos;
use Model\Ingresos;

class ResumenController {
    public static function resumen() {
        session_start();
        isAuth();
        
        $fecha_inicio = $_GET['fecha_inicio'] ?? null;
        $fecha_fin = $_GET['fecha_fin'] ?? null;
        $forma_pago_id = $_GET['forma_pago_id'] ?? null;
        $usuario_id = $_SESSION['id'];

        $whereIngresos = ["usuario_id = '$usuario_id'"];
        if ($fecha_inicio) $whereIngresos[] = "fecha >= '$fecha_inicio'";
        if ($fecha_fin) $whereIngresos[] = "fecha <= '$fecha_fin'";
        if ($forma_pago_id) $whereIngresos[] = "forma_pago_id = '$forma_pago_id'";
        $totalIngresos = Ingresos::filter($whereIngresos);
        
        $whereEgresos = ["usuario_id = '$usuario_id'"];
        if ($fecha_inicio) $whereEgresos[] = "fecha >= '$fecha_inicio'";
        if ($fecha_fin) $whereEgresos[] = "fecha <= '$fecha_fin'";
        if ($forma_pago_id) $whereEgresos[] = "forma_pago_id = '$forma_pago_id'";
        $totalEgresos = Egresos::filter($whereEgresos);
        
        $balance = $totalIngresos - $totalEgresos;

        echo json_encode([
            'ingresos' => floatval($totalIngresos),
            'egresos' => floatval($totalEgresos),
            'balance' => floatval($balance)
        ]);
    }
}
