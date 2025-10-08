<?php // api/obtenerInstructores.php
header("Content-Type: application/json; charset=UTF-8");
require_once '../conexion/db.php';
require_once '../controllers/AdminController.php';

$controlador = new AdminController($conexion);
$respuesta = $controlador->obtenerTodosLosInstructores();
http_response_code($respuesta['estado']);
echo json_encode($respuesta['datos']);
?>