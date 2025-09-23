<?php
// api/eliminarUsuario.php

// Cabeceras CORS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE"); // Este endpoint solo acepta peticiones DELETE
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Verificar que el método sea DELETE
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(['mensaje' => 'Método no permitido. Solo se aceptan peticiones DELETE.']);
    exit();
}

// Incluir archivos necesarios
require_once '../conexion/db.php';
require_once '../controllers/userController.php';

// Obtener los datos enviados en el cuerpo de la petición
$datos = json_decode(file_get_contents("php://input"));

// Crear una instancia del controlador
$controlador = new userController($conexion);

// Llamar al método específico para eliminar
$respuesta = $controlador->eliminarUsuario($datos);

// Devolver la respuesta al front-end
http_response_code($respuesta['estado']);
echo json_encode(['mensaje' => $respuesta['mensaje']]);

$conexion->close();
?>