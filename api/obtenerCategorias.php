<?php // api/obtenerCategorias.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../conexion/db.php';
require_once '../controllers/CategoriaController.php';

$controlador = new CategoriaController($conexion);
$respuesta = $controlador->obtenerTodas();

http_response_code($respuesta['estado']);
echo json_encode($respuesta['datos']);
?>