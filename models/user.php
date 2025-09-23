<?php
// models/Usuario.php

class user {
    private $conexion;

    public function __construct($db) {
        $this->conexion = $db;
    }

    /**
     * Busca un usuario por su email para verificar si ya existe.
     * @param string $email El email a buscar.
     * @return array|null Los datos del usuario si se encuentra, o null.
     */
    public function findByEmail($email) {
        $stmt = $this->conexion->prepare("SELECT * FROM Usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }


    /**
     * Inserta un nuevo usuario en la base de datos.
     * @param string $nombre El nombre del usuario.
     * @param string $email El email del usuario.
     * @param string $contrasena_hash La contraseña ya hasheada.
     * @param string $rol El rol del usuario (ej. 'alumno').
     * @return bool True si la creación fue exitosa, false en caso contrario.
     */
    public function create($nombre, $email, $contrasena_hash, $rol) {
        $stmt = $this->conexion->prepare("INSERT INTO Usuarios (nombre, email, contrasena_hash, rol) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nombre, $email, $contrasena_hash, $rol);
        return $stmt->execute();
    }

    /**
     * Elimina un usuario de la base de datos por su ID.
     * @param int $id_usuario El ID del usuario a eliminar.
     * @return int El número de filas afectadas (1 si se borró, 0 si no se encontró).
     */
    public function delete($id_usuario) {
        $stmt = $this->conexion->prepare("DELETE FROM Usuarios WHERE id_usuario = ?");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        return $stmt->affected_rows;
    }


        /**
     * Guarda un token de reseteo y su fecha de expiración para un usuario.
     * @param string $email El email del usuario.
     * @param string $token El token generado.
     * @param string $fechaExpiracion La fecha en que el token expira (formato Y-m-d H:i:s).
     * @return bool True si fue exitoso, false en caso contrario.
     */
    public function guardarResetToken($email, $token, $fechaExpiracion) {
        $stmt = $this->conexion->prepare("UPDATE Usuarios SET reset_token = ?, reset_token_expires = ? WHERE email = ?");
        $stmt->bind_param("sss", $token, $fechaExpiracion, $email);
        return $stmt->execute();
    }




        /**
     * Busca un usuario por su token de reseteo y verifica que no haya expirado.
     */
    public function buscarPorResetToken($token) {
        $stmt = $this->conexion->prepare("SELECT id_usuario, email FROM Usuarios WHERE reset_token = ? AND reset_token_expires > NOW()");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    /**
     * Actualiza la contraseña de un usuario por su ID y limpia el token.
     */
    public function updatePassword($id_usuario, $contrasena_hash) {
        $stmt = $this->conexion->prepare("UPDATE Usuarios SET contrasena_hash = ?, reset_token = NULL, reset_token_expires = NULL WHERE id_usuario = ?");
        $stmt->bind_param("si", $contrasena_hash, $id_usuario);
        $stmt->execute();
        return $stmt->affected_rows;
    }


   

   
    

}
?>