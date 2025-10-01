<?php
// controllers/CursoController.php

require_once '../models/Curso.php';

class CursoController {
    private $modeloCurso;
    public $conexion;

    public function __construct($db) {
        $this->conexion = $db;
        $this->modeloCurso = new Curso($db);
    }

    /**
     * Procesa la creación de un nuevo curso.
     */
    public function crearCurso($datos) {
        // Validación de campos obligatorios (sin cambios)
        $campos_requeridos = ['titulo', 'descripcion', 'fecha_inicio', 'fecha_fin', 'cupo_disponible', 'precio', 'estado', 'modalidad', 'id_instructor', 'id_categoria'];
        foreach ($campos_requeridos as $campo) {
            if (!isset($datos->$campo)) {
                return ['estado' => 400, 'mensaje' => "Faltan datos. El campo '$campo' es requerido."];
            }
        }

        // ✅ AÑADIMOS LA NUEVA VALIDACIÓN AQUÍ
        // Verificamos si ya existe un curso con ese título para ese instructor
        if ($this->modeloCurso->findByTitleAndInstructor($datos->titulo, $datos->id_instructor)) {
            // Si se encuentra, devolvemos un error de "Conflicto"
            return ['estado' => 409, 'mensaje' => 'Ya has creado un curso con este mismo título.'];
        }
        // ✅ FIN DE LA VALIDACIÓN

        // Si no existe, continuamos con la creación (sin cambios)
        if ($this->modeloCurso->create($datos)) {
            return ['estado' => 201, 'mensaje' => 'Curso creado con éxito.'];
        } else {
            return ['estado' => 500, 'mensaje' => 'Error al crear el curso en la base de datos.', 'error_db' => $this->conexion->error];
        }
    }

    /**
     * Obtiene un curso específico por su ID.
     */
    public function obtenerCursoPorId($id) {
        $curso = $this->modeloCurso->getById($id);

        if ($curso) {
            return ['estado' => 200, 'datos' => $curso];
        } else {
            return ['estado' => 404, 'mensaje' => 'Curso no encontrado.'];
        }
    }

   /**
     * Procesa la edición de un curso existente.
     */
    public function editarCurso($datos) {
        if (!isset($datos->id_curso)) {
            return ['estado' => 400, 'mensaje' => 'Se requiere el ID del curso para editar.'];
        }

        $id_curso = intval($datos->id_curso);
        
        // Guardamos el número que nos devuelve el modelo
        $filasAfectadas = $this->modeloCurso->update($id_curso, $datos);

        // ✅ LÓGICA SIMPLIFICADA Y MÁS CONFIABLE
        if ($filasAfectadas >= 0) {
            // Si es 0 (sin cambios) o 1 (actualizado), lo consideramos éxito
            return ['estado' => 200, 'mensaje' => 'Curso actualizado con éxito.'];
        } else {
            // Si es -1, significa que hubo un error en la consulta
            return ['estado' => 500, 'mensaje' => 'Error al actualizar el curso.', 'error_db' => $this->conexion->error];
        }
    }

    /**
     * Procesa la eliminación de un curso.
     */
    public function eliminarCurso($datos) {
        if (!isset($datos->id_curso)) {
            return ['estado' => 400, 'mensaje' => 'Se requiere el ID del curso para eliminar.'];
        }

        $id_curso = intval($datos->id_curso);
        $filasAfectadas = $this->modeloCurso->delete($id_curso);

        if ($filasAfectadas > 0) {
            // Se eliminó 1 fila, ¡éxito!
            return ['estado' => 200, 'mensaje' => 'Curso eliminado con éxito.'];
        } elseif ($filasAfectadas === 0) {
            // No se afectó ninguna fila, el curso no existía.
            return ['estado' => 404, 'mensaje' => 'Curso no encontrado.'];
        } else {
            // Si es -1, hubo un error. Verificamos si es por una llave foránea.
            if ($this->conexion->errno == 1451) {
                return ['estado' => 409, 'mensaje' => 'Conflicto: No se puede eliminar el curso porque ya tiene inscripciones asociadas.'];
            }
            // Otro tipo de error del servidor.
            return ['estado' => 500, 'mensaje' => 'Error en el servidor al intentar eliminar el curso.'];
        }
    }

    /**
     * Obtiene todos los cursos de un instructor específico.
     */
    public function obtenerCursosPorInstructor($id_instructor) {
        if (empty($id_instructor) || !is_numeric($id_instructor)) {
            return ['estado' => 400, 'mensaje' => 'Se requiere un ID de instructor válido.'];
        }

        $cursos = $this->modeloCurso->getByInstructorId($id_instructor);
        
        // La consulta siempre es exitosa, incluso si no devuelve cursos.
        // Simplemente devolvemos la lista (que puede estar vacía).
        return ['estado' => 200, 'datos' => $cursos];
    }

    /**
     * Busca cursos por un término de búsqueda.
     */
    public function buscarCursosPorTermino($termino) {
        if (empty(trim($termino))) {
            return ['estado' => 400, 'mensaje' => 'Se requiere un término de búsqueda.'];
        }

        $cursos = $this->modeloCurso->buscarCursos($termino);
        
        return ['estado' => 200, 'datos' => $cursos];
    }

    /**
     * Obtiene todos los cursos para el catálogo principal.
     */
    public function obtenerTodosLosCursos() {
        $cursos = $this->modeloCurso->getAll();
        return ['estado' => 200, 'datos' => $cursos];
    }
}
?>