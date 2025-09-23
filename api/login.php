<?php
// api/login.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

// Verificar que el método sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['mensaje' => 'Método no permitido.']);
    exit();
}

require_once '../conexion/db.php';
require_once '../controllers/userController.php';

$datos = json_decode(file_get_contents("php://input"));
$controlador = new userController($conexion);

// Llamamos al método de login
$respuesta = $controlador->login($datos); 

http_response_code($respuesta['estado']);
// Si el login es exitoso, la respuesta incluye el campo 'datos'
if (isset($respuesta['datos'])) {
    echo json_encode([
        'mensaje' => $respuesta['mensaje'],
        'datos' => $respuesta['datos']
    ]);
} else {
    // Si falla, solo se envía el mensaje de error
    echo json_encode(['mensaje' => $respuesta['mensaje']]);
}

$conexion->close();
?>