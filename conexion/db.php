<?php

// -- Configuración de la base de datos --
$db_host = 'localhost';    
$db_usuario = 'root';       
$db_contrasena = '';       
$db_nombre = 'cursos_db';

// -- Crear la conexión --
$conexion = new mysqli($db_host, $db_usuario, $db_contrasena, $db_nombre);

// -- Establecer el juego de caracteres a UTF-8 --
// Esto es importante para evitar problemas con tildes y caracteres especiales.
$conexion->set_charset("utf8mb4");

// -- Verificar la conexión --
if ($conexion->connect_error) {
    // Si hay un error, se termina la ejecución y se muestra el error.
    die("Error de conexión: " . $conexion->connect_error);
}

// Si todo está bien, este archivo simplemente dejará la variable $conexion
// disponible para cualquier otro script que lo incluya.
?>