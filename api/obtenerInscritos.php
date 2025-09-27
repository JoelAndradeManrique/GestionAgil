<?php
// api/obtenerInscritos.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['mensaje' => 'Método no permitido.']);
    exit();
}

if (!isset($_GET['id_curso'])) {
    http_response_code(400);
    echo json_encode(['mensaje' => 'Se requiere el ID del curso.']);
    exit();
}

require_once '../conexion/db.php';
require_once '../controllers/InscripcionController.php';

$id_curso = intval($_GET['id_curso']);
$controlador = new InscripcionController($conexion);
$respuesta = $controlador->obtenerInscritos($id_curso);

http_response_code($respuesta['estado']);
echo json_encode($respuesta);

$conexion->close();
?>