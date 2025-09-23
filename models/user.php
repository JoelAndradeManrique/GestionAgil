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
        $stmt = $this->conexion->prepare("SELECT id_usuario FROM Usuarios WHERE email = ?");
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
}
?>