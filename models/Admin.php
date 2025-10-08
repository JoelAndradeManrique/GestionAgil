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

    public function getInstructores() {
    $query = "
        SELECT 
            u.id_usuario, u.nombre, u.email, u.created_at, COUNT(c.id_curso) as total_cursos
        FROM Usuarios u
        LEFT JOIN Cursos c ON u.id_usuario = c.id_instructor
        WHERE u.rol = 'instructor'
        GROUP BY u.id_usuario, u.nombre, u.email, u.created_at
        ORDER BY u.nombre;
    ";
    $resultado = $this->conexion->query($query);
    $instructores = [];
    while($fila = $resultado->fetch_assoc()) {
        // Obtenemos los nombres de los cursos por separado
        $stmt_cursos = $this->conexion->prepare("SELECT titulo FROM Cursos WHERE id_instructor = ?");
        $stmt_cursos->bind_param("i", $fila['id_usuario']);
        $stmt_cursos->execute();
        $res_cursos = $stmt_cursos->get_result();
        $cursos = [];
        while($curso_fila = $res_cursos->fetch_assoc()) {
            $cursos[] = $curso_fila['titulo'];
        }
        $fila['cursos'] = $cursos;
        $instructores[] = $fila;
    }
    return $instructores;
}


// models/Admin.php
public function getAlumnos() {
    // Usamos GROUP_CONCAT para traer los nombres de los cursos en una sola consulta
    $query = "
        SELECT 
            u.id_usuario, u.nombre, u.email, u.created_at,
            GROUP_CONCAT(c.titulo SEPARATOR '|') AS cursos_inscritos
        FROM Usuarios u
        LEFT JOIN Inscripciones i ON u.id_usuario = i.id_usuario
        LEFT JOIN Cursos c ON i.id_curso = c.id_curso
        WHERE u.rol = 'alumno'
        GROUP BY u.id_usuario, u.nombre, u.email, u.created_at
        ORDER BY u.nombre;
    ";
    $resultado = $this->conexion->query($query);
    $alumnos = [];
    while($fila = $resultado->fetch_assoc()) {
        // Convertimos la cadena de cursos en un array
        $fila['cursos_inscritos'] = $fila['cursos_inscritos'] ? explode('|', $fila['cursos_inscritos']) : [];
        $alumnos[] = $fila;
    }
    return $alumnos;
}
}
?>