<?php
// api/buscarCursos.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['mensaje' => 'Método no permitido.']);
    exit();
}

// El término de búsqueda vendrá por la URL (ej. ...?q=java)
// Usamos 'q' como abreviatura de 'query' (consulta), es una práctica común.
if (!isset($_GET['q'])) {
    http_response_code(400);
    echo json_encode(['mensaje' => 'Se requiere un término de búsqueda (parámetro q).']);
    exit();
}

require_once '../conexion/db.php';
require_once '../controllers/CursoController.php';

$termino = $_GET['q'];
$controlador = new CursoController($conexion);
$respuesta = $controlador->buscarCursosPorTermino($termino);

http_response_code($respuesta['estado']);
echo json_encode($respuesta);

$conexion->close();
?>