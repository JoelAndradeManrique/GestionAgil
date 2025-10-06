<?php
// api/adminDashboard.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../conexion/db.php';
require_once '../controllers/AdminController.php';

// Aquí podríamos añadir una validación de rol para asegurar que solo un admin pueda acceder
// $datosUsuario = ...;
// if ($datosUsuario->rol !== 'admin') { ... }

$controlador = new AdminController($conexion);
$respuesta = $controlador->obtenerDatosDashboard();

http_response_code($respuesta['estado']);
echo json_encode($respuesta['datos']);

$conexion->close();
?>