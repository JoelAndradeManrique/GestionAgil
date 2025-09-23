<?php
// controllers/UsuarioController.php

require_once '../models/user.php';

class userController {
    private $modeloUsuario;

    public function __construct($db) {
        $this->modeloUsuario = new user($db);
    }

    /**
     * Procesa la lógica para registrar un nuevo usuario.
     * @param object $datos Los datos recibidos del front-end (nombre, email, contrasena).
     * @return array Un array con el estado y mensaje de la operación.
     */
    public function registrar($datos) {
        // 1. Validar que los datos necesarios llegaron
        if (!isset($datos->nombre) || !isset($datos->email) || !isset($datos->contrasena) || !isset($datos->rol)) {
            return ['estado' => 400, 'mensaje' => 'Datos incompletos. Se requiere nombre, email y contraseña.'];
        }

        // 2. Verificar si el usuario ya existe (usando el Modelo)
        if ($this->modeloUsuario->findByEmail($datos->email)) {
            return ['estado' => 409, 'mensaje' => 'El correo electrónico ya está en uso.'];
        }

        // 3. Hashear la contraseña por seguridad
        $hash = password_hash($datos->contrasena, PASSWORD_BCRYPT);
        $rol = $datos->rol ?? 'alumno'; // Asignar 'alumno' como rol por defecto

        // 4. Intentar crear el usuario (usando el Modelo)
        if ($this->modeloUsuario->create($datos->nombre, $datos->email, $hash, $rol)) {
            return ['estado' => 201, 'mensaje' => 'Usuario registrado con éxito.'];
        } else {
            return ['estado' => 500, 'mensaje' => 'Hubo un error en el servidor al registrar el usuario.'];
        }
    }
}
?>