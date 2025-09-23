<?php
// api/registrar.php

// Cabeceras CORS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

// Incluir los archivos necesarios
require_once '../conexion/db.php';
require_once '../controllers/userController.php';

// ✅ VERIFICACIÓN DEL MÉTODO DE LA PETICIÓN
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Si el método es POST, continuamos con la lógica normal
    $datos = json_decode(file_get_contents("php://input"));
    $controlador = new userController($conexion);
    $respuesta = $controlador->registrar($datos);
    
    http_response_code($respuesta['estado']);
    echo json_encode(['mensaje' => $respuesta['mensaje']]);

} else {
    // Si el método no es POST, enviamos un error 405
    http_response_code(405);
    echo json_encode(['mensaje' => 'Método no permitido. Solo se aceptan peticiones POST.']);
}

$conexion->close();
?>