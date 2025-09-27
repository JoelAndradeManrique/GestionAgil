<?php
// api/obtenerMisCursos.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['mensaje' => 'Método no permitido.']);
    exit();
}

// El ID del instructor vendrá por la URL (ej. ...?id_instructor=2)
if (!isset($_GET['id_instructor'])) {
    http_response_code(400);
    echo json_encode(['mensaje' => 'Se requiere el ID del instructor.']);
    exit();
}

require_once '../conexion/db.php';
require_once '../controllers/CursoController.php';

$id_instructor = intval($_GET['id_instructor']);
$controlador = new CursoController($conexion);
$respuesta = $controlador->obtenerCursosPorInstructor($id_instructor);

http_response_code($respuesta['estado']);
echo json_encode($respuesta);

$conexion->close();
?>