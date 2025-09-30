<?php
// api/obtenerMisInscripciones.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../conexion/db.php';
require_once '../controllers/InscripcionController.php';

if (!isset($_GET['id_usuario'])) {
    http_response_code(400);
    echo json_encode(['mensaje' => 'Se requiere el ID del usuario.']);
    exit();
}

$id_usuario = intval($_GET['id_usuario']);
$filtros = $_GET; // Tomamos todos los parámetros GET como filtros

$controlador = new InscripcionController($conexion);
$respuesta = $controlador->obtenerInscripcionesPorAlumno($id_usuario, $filtros);

http_response_code($respuesta['estado']);
echo json_encode($respuesta['datos']);

$conexion->close();
?>