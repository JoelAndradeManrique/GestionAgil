<?php
// api/obtenerCurso.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['mensaje' => 'Método no permitido.']);
    exit();
}

// El ID del curso vendrá por la URL (ej. ...?id=5)
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['mensaje' => 'Se requiere el ID del curso.']);
    exit();
}

require_once '../conexion/db.php';
require_once '../controllers/CursoController.php';

$id_curso = intval($_GET['id']);
$controlador = new CursoController($conexion);
$respuesta = $controlador->obtenerCursoPorId($id_curso);

http_response_code($respuesta['estado']);
echo json_encode($respuesta);

$conexion->close();
?>