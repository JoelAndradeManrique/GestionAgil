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
        if (!isset($datos->nombre) || !isset($datos->email) || !isset($datos->contrasena)) {
            return ['estado' => 400, 'mensaje' => 'Datos incompletos. Se requiere nombre, email y contraseña.'];
        }

        if ($this->modeloUsuario->findByEmail($datos->email)) {
            return ['estado' => 409, 'mensaje' => 'El correo electrónico ya está en uso.'];
        }

        $hash = password_hash($datos->contrasena, PASSWORD_BCRYPT);
        $rol = $datos->rol ?? 'alumno';

        // Intentamos crear el usuario
        $resultado = $this->modeloUsuario->create($datos->nombre, $datos->email, $hash, $rol);

        if ($resultado) {
            // Si el modelo devuelve 'true', el registro fue exitoso.
            return ['estado' => 201, 'mensaje' => 'Usuario registrado con éxito.'];
        } else {
            // Si el modelo devuelve 'false', algo falló en la base de datos.
            // Le pedimos al controlador que nos diga cuál fue el error de MySQL.
            $error_db = $this->modeloUsuario->conexion->error;
            return ['estado' => 500, 'mensaje' => 'Error al registrar el usuario en la base de datos.', 'error_db' => $error_db];
        }
    }


    /**
     * Procesa la lógica para eliminar un usuario.
     * @param object $datos Los datos recibidos, que deben contener el id_usuario.
     * @return array Un array con el estado y mensaje de la operación.
     */
    public function eliminarUsuario($datos) {
        // 1. Validar que se recibió el ID del usuario
        if (!isset($datos->id_usuario)) {
            return ['estado' => 400, 'mensaje' => 'Se requiere el id_usuario para eliminar.'];
        }

        // 2. Intentar eliminar al usuario usando el Modelo
        $filasAfectadas = $this->modeloUsuario->delete($datos->id_usuario);

        // 3. Verificar el resultado
        if ($filasAfectadas > 0) {
            return ['estado' => 200, 'mensaje' => 'Usuario eliminado con éxito.'];
        } else {
            // Este error puede ocurrir si el usuario ya fue eliminado o nunca existió.
            // Ojo: También puede ocurrir si el usuario tiene registros asociados (FK).
            if ($this->modeloUsuario->conexion->errno == 1451) {
                 return ['estado' => 409, 'mensaje' => 'Conflicto: No se puede eliminar el usuario porque tiene registros asociados (cursos, inscripciones, etc.).'];
            }
            return ['estado' => 404, 'mensaje' => 'Usuario no encontrado o ya fue eliminado.'];
        }
    }

    

  public function solicitarRecuperacion($datos) {
        if (!isset($datos->email)) {
            return ['estado' => 400, 'mensaje' => 'Se requiere el correo electrónico.'];
        }

        $usuario = $this->modeloUsuario->findByEmail($datos->email);

        // Si el usuario no existe, devolvemos el error que querías
        if (!$usuario) {
            return ['estado' => 404, 'mensaje' => 'El correo no está registrado.'];
        }

        // Si el usuario sí existe, generamos el token
        $token = bin2hex(random_bytes(32));
        $expiracion = date('Y-m-d H:i:s', time() + 3600); // 1 hora de expiración

        if ($this->modeloUsuario->guardarResetToken($datos->email, $token, $expiracion)) {
            
            // ✅ NOS ASEGURAMOS DE QUE ESTA PARTE ESTÉ ACTIVA
            // Construimos el enlace para la simulación
            $linkRecuperacion = "http://localhost/GestionAgil/Principal/resetear_contrasena.php?token=" . $token;
            
            // Devolvemos la respuesta incluyendo la simulación del email
            return [
                'estado' => 200, 
                'mensaje' => 'Enlace de recuperación generado. En una app real, se enviaría por correo.',
                'simulacion_email' => 'Enlace simulado: ' . $linkRecuperacion
            ];

        } else {
            return ['estado' => 500, 'mensaje' => 'Error al procesar la solicitud en la base de datos.'];
        }
    }


        /**
     * Procesa el reseteo de la contraseña usando un token.
     */
    public function resetearContrasena($datos) {
        if (!isset($datos->token) || !isset($datos->nueva_contrasena)) {
            return ['estado' => 400, 'mensaje' => 'Se requiere el token y la nueva contraseña.'];
        }

        // Buscar si el token es válido y no ha expirado
        $usuario = $this->modeloUsuario->buscarPorResetToken($datos->token);

        if ($usuario) {
            $hash = password_hash($datos->nueva_contrasena, PASSWORD_BCRYPT);
            // Reutilizamos el método de actualizar contraseña, ahora también limpia el token
            if ($this->modeloUsuario->updatePassword($usuario['id_usuario'], $hash) > 0) {
                return ['estado' => 200, 'mensaje' => 'Contraseña actualizada con éxito.'];
            } else {
                return ['estado' => 500, 'mensaje' => 'Error al actualizar la contraseña.'];
            }
        } else {
            return ['estado' => 400, 'mensaje' => 'Token inválido o expirado.'];
        }
    }


    
     /**
     * Procesa el inicio de sesión de un usuario.
     * @param object $datos Los datos del front-end (email, contrasena).
     * @return array Un array con el estado y mensaje/datos del usuario.
     */
    public function login($datos) {
        // 1. Validar que llegaron ambos datos
        if (!isset($datos->email) || !isset($datos->contrasena)) {
            return ['estado' => 400, 'mensaje' => 'Se requiere email y contraseña.'];
        }

        // 2. Buscar al usuario por su email usando el modelo
        $usuario = $this->modeloUsuario->findByEmail($datos->email);

        // 3. Verificar si el usuario existe Y si la contraseña coincide
        if ($usuario && password_verify($datos->contrasena, $usuario['contrasena_hash'])) {
            // ¡Éxito! La contraseña coincide con el hash guardado
            unset($usuario['contrasena_hash']); // No enviar el hash en la respuesta
            unset($usuario['reset_token']); // No enviar datos sensibles
            unset($usuario['reset_token_expires']);
            
            return ['estado' => 200, 'mensaje' => 'Login exitoso.', 'datos' => $usuario];
        } else {
            // Error: O el usuario no existe, o la contraseña es incorrecta.
            // Se devuelve el mismo error genérico por seguridad.
            return ['estado' => 401, 'mensaje' => 'Inicio de sesión fallido. Puede que algo sea incorrecto, intenta de nuevo.'];
        }
    }



   
}
?>