<?php
// api/eliminarCurso.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, DELETE"); // Aceptamos POST o DELETE

if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(['mensaje' => 'Método no permitido.']);
    exit();
}

require_once '../conexion/db.php';
require_once '../controllers/CursoController.php';

$datos = json_decode(file_get_contents("php://input"));
$controlador = new CursoController($conexion);
$respuesta = $controlador->eliminarCurso($datos);

http_response_code($respuesta['estado']);
echo json_encode($respuesta);

$conexion->close();
?>