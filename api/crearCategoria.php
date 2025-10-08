<?php // api/crearCategoria.php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require_once '../conexion/db.php';
require_once '../controllers/CategoriaController.php';

$datos = json_decode(file_get_contents("php://input"));
$controlador = new CategoriaController($conexion);
$respuesta = $controlador->crearCategoria($datos);

http_response_code($respuesta['estado']);
echo json_encode($respuesta);
?>