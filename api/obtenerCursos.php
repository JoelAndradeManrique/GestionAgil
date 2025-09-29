<?php
// api/obtenerCursos.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../conexion/db.php';
require_once '../controllers/CursoController.php';

$controlador = new CursoController($conexion);
$respuesta = $controlador->obtenerTodosLosCursos();

http_response_code($respuesta['estado']);
echo json_encode($respuesta['datos']); // Devolvemos directamente el array de datos

$conexion->close();
?>