<?php
// models/Admin.php

class Admin {
    private $conexion;

    public function __construct($db) {
        $this->conexion = $db;
    }

    /**
     * Obtiene todos los datos necesarios para el dashboard del administrador.
     * @return array La lista de todos los cursos con sus instructores y alumnos.
     */
    public function getDashboardData() {
        $data = [];
        // Esta es una consulta compleja que une 4 tablas (Usuarios se usa dos veces)
        $query = "
            SELECT 
                c.id_curso,
                c.titulo AS nombre_curso,
                c.fecha_inicio,
                c.fecha_fin,
                instructor.id_usuario AS id_instructor,
                instructor.nombre AS nombre_instructor,
                alumno.id_usuario AS id_alumno,
                alumno.nombre AS nombre_alumno,
                i.fecha_inscripcion
            FROM Cursos c
            JOIN Usuarios instructor ON c.id_instructor = instructor.id_usuario
            LEFT JOIN Inscripciones i ON c.id_curso = i.id_curso
            LEFT JOIN Usuarios alumno ON i.id_usuario = alumno.id_usuario
            ORDER BY instructor.nombre, c.titulo, alumno.nombre;
        ";
        
        $resultado = $this->conexion->query($query);

        while ($fila = $resultado->fetch_assoc()) {
            $data[] = $fila;
        }
        return $data;
    }
}
?>