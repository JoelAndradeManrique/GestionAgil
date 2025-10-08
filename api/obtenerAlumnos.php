<?php // api/obtenerAlumnos.php
header("Content-Type: application/json; charset=UTF-8");
require_once '../conexion/db.php';
require_once '../controllers/AdminController.php';

$controlador = new AdminController($conexion);
$respuesta = $controlador->obtenerTodosLosAlumnos();
http_response_code($respuesta['estado']);
echo json_encode($respuesta['datos']);
?>