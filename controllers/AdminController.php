<?php
// controllers/AdminController.php

require_once '../models/Admin.php';

class AdminController {
    private $modeloAdmin;

    public function __construct($db) {
        $this->modeloAdmin = new Admin($db);
    }

    public function obtenerDatosDashboard() {
        $datos_planos = $this->modeloAdmin->getDashboardData();
        
        // --- PROCESAMIENTO PARA ANIDAR LOS DATOS ---
        $datos_estructurados = [];

        foreach ($datos_planos as $fila) {
            $id_instructor = $fila['id_instructor'];
            $id_curso = $fila['id_curso'];

            // Si el instructor no existe en nuestro array, lo añadimos
            if (!isset($datos_estructurados[$id_instructor])) {
                $datos_estructurados[$id_instructor] = [
                    'nombre_instructor' => $fila['nombre_instructor'],
                    'cursos' => []
                ];
            }

            // Si el curso no existe en el array de cursos del instructor, lo añadimos
            if (!isset($datos_estructurados[$id_instructor]['cursos'][$id_curso])) {
                $datos_estructurados[$id_instructor]['cursos'][$id_curso] = [
                    'nombre_curso' => $fila['nombre_curso'],
                    'fecha_inicio' => $fila['fecha_inicio'],
                    'fecha_fin' => $fila['fecha_fin'],
                    'alumnos_inscritos' => []
                ];
            }

            // Si hay un alumno en esta fila (no es nulo), lo añadimos al curso
            if ($fila['id_alumno']) {
                $datos_estructurados[$id_instructor]['cursos'][$id_curso]['alumnos_inscritos'][] = [
                    'id_alumno' => $fila['id_alumno'],
                    'nombre_alumno' => $fila['nombre_alumno'],
                    'fecha_inscripcion' => $fila['fecha_inscripcion']
                ];
            }
        }

        // Re-indexar los arrays para que el JSON no tenga llaves numéricas extrañas
        $resultado_final = array_values($datos_estructurados);
        foreach ($resultado_final as $i => &$instructor) {
            $instructor['cursos'] = array_values($instructor['cursos']);
        }

        return ['estado' => 200, 'datos' => $resultado_final];
    }

    public function obtenerTodosLosInstructores() {
    $instructores = $this->modeloAdmin->getInstructores();
    return ['estado' => 200, 'datos' => $instructores];
}

// controllers/AdminController.php
public function obtenerTodosLosAlumnos() {
    $alumnos = $this->modeloAdmin->getAlumnos();
    return ['estado' => 200, 'datos' => $alumnos];
}
}
?>