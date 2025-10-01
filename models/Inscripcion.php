<?php
// models/Inscripcion.php

class Inscripcion {
    private $conexion;

    public function __construct($db) {
        $this->conexion = $db;
    }

    /**
     * Proceso completo de inscripción. Devuelve datos para el voucher en caso de éxito.
     * @return array|string Retorna un array con datos del pago en éxito, o un string con el error si falla.
     */
    /**
     * Proceso completo de inscripción usando una transacción.
     * Devuelve datos para el voucher en caso de éxito, o un string con el error si falla.
     */
    public function crear($id_curso, $id_usuario) {
        // --- Iniciamos la transacción para asegurar que todo o nada se ejecute ---
        $this->conexion->begin_transaction();

        try {
            // 1. Verificar si el usuario ya está inscrito
            $stmt_check = $this->conexion->prepare("SELECT id_inscripcion FROM Inscripciones WHERE id_curso = ? AND id_usuario = ?");
            $stmt_check->bind_param("ii", $id_curso, $id_usuario);
            $stmt_check->execute();
            $stmt_check->store_result();
            if ($stmt_check->num_rows > 0) {
                throw new Exception("El usuario ya está inscrito en este curso.");
            }
            $stmt_check->close();

            // 2. Verificar si hay cupos disponibles (y bloquear la fila para seguridad)
            $stmt_cupo = $this->conexion->prepare("SELECT cupo_disponible FROM Cursos WHERE id_curso = ? FOR UPDATE");
            $stmt_cupo->bind_param("i", $id_curso);
            $stmt_cupo->execute();
            $cupo_result = $stmt_cupo->get_result()->fetch_assoc();
            if ($cupo_result['cupo_disponible'] <= 0) {
                throw new Exception("No hay cupos disponibles para este curso.");
            }
            $stmt_cupo->close();

            // 3. Insertar la nueva inscripción
            $fecha_actual = date('Y-m-d H:i:s');
            $stmt_ins = $this->conexion->prepare("INSERT INTO Inscripciones (id_curso, id_usuario, fecha_inscripcion) VALUES (?, ?, ?)");
            $stmt_ins->bind_param("iis", $id_curso, $id_usuario, $fecha_actual);
            $stmt_ins->execute();
            $id_inscripcion_nueva = $this->conexion->insert_id;
            $stmt_ins->close();

            // 4. Insertar el pago simulado
            $stmt_pago = $this->conexion->prepare("INSERT INTO Pagos (id_inscripcion, monto, fecha_pago, estado_pago) VALUES (?, (SELECT precio FROM Cursos WHERE id_curso = ?), ?, 'completado')");
            $stmt_pago->bind_param("iis", $id_inscripcion_nueva, $id_curso, $fecha_actual);
            $stmt_pago->execute();
            $id_pago_nuevo = $this->conexion->insert_id;
            $stmt_pago->close();

            // 5. Actualizar el cupo y el estado del curso si se acaban los lugares
            $query_update = "UPDATE Cursos SET 
                                cupo_disponible = cupo_disponible - 1,
                                estado = IF(cupo_disponible = 1, 'cerrado', estado)
                             WHERE id_curso = ?";
            
            $stmt_update = $this->conexion->prepare($query_update);
            $stmt_update->bind_param("i", $id_curso);
            $stmt_update->execute();
            $stmt_update->close();

            // 6. Obtener los datos necesarios para el voucher
            $query_datos = "SELECT c.titulo, c.fecha_inicio, c.fecha_fin, p.monto, u.nombre AS nombre_instructor
                            FROM Cursos c
                            JOIN Usuarios u ON c.id_instructor = u.id_usuario
                            JOIN Pagos p ON p.id_pago = ?
                            WHERE c.id_curso = ?";

            $stmt_datos = $this->conexion->prepare($query_datos);
            $stmt_datos->bind_param("ii", $id_pago_nuevo, $id_curso);
            $stmt_datos->execute();
            $datos_voucher = $stmt_datos->get_result()->fetch_assoc();
            $stmt_datos->close();

            // --- Si todo salió bien, confirmamos los cambios ---
            $this->conexion->commit();
            
            // Devolvemos los datos para el voucher
            return [
                'id_inscripcion' => $id_inscripcion_nueva,
                'id_pago' => $id_pago_nuevo,
                'fecha_pago' => $fecha_actual,
                'titulo_curso' => $datos_voucher['titulo'],
                'monto' => $datos_voucher['monto'],
                'nombre_instructor' => $datos_voucher['nombre_instructor'],
                'fecha_inicio' => $datos_voucher['fecha_inicio'],
                'fecha_fin' => $datos_voucher['fecha_fin']
            ];

        } catch (Exception $e) {
            // --- Si algo falló, deshacemos todos los cambios ---
            $this->conexion->rollback();
            return $e->getMessage(); // Devolvemos el mensaje de error específico
        }
    }
    /**
     * Obtiene los nombres de los alumnos inscritos en un curso específico.
     */
    public function getInscritosPorCurso($id_curso) {
        $alumnos = [];
        // Hacemos un JOIN para cruzar Inscripciones con Usuarios y obtener los nombres
        $query = "SELECT u.id_usuario, u.nombre, u.email, i.fecha_inscripcion 
                  FROM Inscripciones i
                  JOIN Usuarios u ON i.id_usuario = u.id_usuario
                  WHERE i.id_curso = ?";
        
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $id_curso);
        $stmt->execute();
        $resultado = $stmt->get_result();

        while ($fila = $resultado->fetch_assoc()) {
            $alumnos[] = $fila;
        }
        return $alumnos;
    }
    /**
     * Obtiene los cursos de un alumno, con filtros opcionales.
     */
    public function getByAlumnoId($id_usuario, $filtros = []) {
        $cursos = [];
        // La consulta base que une las tablas
        $query = "SELECT c.*, u.nombre as nombre_instructor 
                  FROM Inscripciones i
                  JOIN Cursos c ON i.id_curso = c.id_curso
                  JOIN Usuarios u ON c.id_instructor = u.id_usuario
                  WHERE i.id_usuario = ?";
        
        $where = [];
        $params = [$id_usuario];
        $types = "i";

        // Añadimos los filtros dinámicamente
        if (!empty($filtros['q'])) {
            $where[] = "(c.titulo LIKE ? OR c.descripcion LIKE ?)";
            $types .= "ss";
            $params[] = "%" . $filtros['q'] . "%";
            $params[] = "%" . $filtros['q'] . "%";
        }
        if (!empty($filtros['categoria'])) {
            $where[] = "c.id_categoria = ?";
            $types .= "i";
            $params[] = $filtros['categoria'];
        }
        if (!empty($filtros['modalidad'])) {
            $where[] = "c.modalidad = ?";
            $types .= "s";
            $params[] = $filtros['modalidad'];
        }

        if (!empty($where)) {
            $query .= " AND " . implode(" AND ", $where);
        }

        $stmt = $this->conexion->prepare($query);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $resultado = $stmt->get_result();
        while ($fila = $resultado->fetch_assoc()) {
            $cursos[] = $fila;
        }
        return $cursos;
    }
}

?>