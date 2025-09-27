<?php
// api/inscribirCurso.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['mensaje' => 'Método no permitido.']);
    exit();
}

require_once '../conexion/db.php';
require_once '../controllers/InscripcionController.php';

$datos = json_decode(file_get_contents("php://input"));
$controlador = new InscripcionController($conexion);
$respuesta = $controlador->inscribirAlumno($datos);

http_response_code($respuesta['estado']);
echo json_encode($respuesta);

$conexion->close();
?>