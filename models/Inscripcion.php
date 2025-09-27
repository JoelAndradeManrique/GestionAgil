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
    public function crear($id_curso, $id_usuario) {
        $this->conexion->begin_transaction();
        try {
            // --- (Las validaciones de cupo, etc. no cambian) ---
            $stmt_check = $this->conexion->prepare("SELECT id_inscripcion FROM Inscripciones WHERE id_curso = ? AND id_usuario = ?");
            $stmt_check->bind_param("ii", $id_curso, $id_usuario);
            $stmt_check->execute();
            $stmt_check->store_result();
            if ($stmt_check->num_rows > 0) throw new Exception("El usuario ya está inscrito en este curso.");
            $stmt_check->close();

            $stmt_cupo = $this->conexion->prepare("SELECT cupo_disponible FROM Cursos WHERE id_curso = ? FOR UPDATE");
            $stmt_cupo->bind_param("i", $id_curso);
            $stmt_cupo->execute();
            $cupo_result = $stmt_cupo->get_result()->fetch_assoc();
            if ($cupo_result['cupo_disponible'] <= 0) throw new Exception("No hay cupos disponibles para este curso.");
            $stmt_cupo->close();

            // --- (La inserción en Inscripciones y Pagos no cambia) ---
            $fecha_actual = date('Y-m-d H:i:s');
            $stmt_ins = $this->conexion->prepare("INSERT INTO Inscripciones (id_curso, id_usuario, fecha_inscripcion) VALUES (?, ?, ?)");
            $stmt_ins->bind_param("iis", $id_curso, $id_usuario, $fecha_actual);
            $stmt_ins->execute();
            $id_inscripcion_nueva = $this->conexion->insert_id;
            $stmt_ins->close();

            $stmt_pago = $this->conexion->prepare("INSERT INTO Pagos (id_inscripcion, monto, fecha_pago, estado_pago) VALUES (?, (SELECT precio FROM Cursos WHERE id_curso = ?), ?, 'completado')");
            $stmt_pago->bind_param("iis", $id_inscripcion_nueva, $id_curso, $fecha_actual);
            $stmt_pago->execute();
            $id_pago_nuevo = $this->conexion->insert_id;
            $stmt_pago->close();

            $stmt_update = $this->conexion->prepare("UPDATE Cursos SET cupo_disponible = cupo_disponible - 1 WHERE id_curso = ?");
            $stmt_update->bind_param("i", $id_curso);
            $stmt_update->execute();
            $stmt_update->close();

            // --- ✅ AQUÍ ESTÁ LA CONSULTA MEJORADA ---
            // Ahora también pide el nombre del instructor (u.nombre) y las fechas del curso (c.fecha_inicio, c.fecha_fin)
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

            $this->conexion->commit();
            
            // DEVOLVEMOS TODOS LOS DATOS NUEVOS
            return [
                'id_inscripcion' => $id_inscripcion_nueva,
                'id_pago' => $id_pago_nuevo,
                'fecha_pago' => $fecha_actual,
                'titulo_curso' => $datos_voucher['titulo'],
                'monto' => $datos_voucher['monto'],
                'nombre_instructor' => $datos_voucher['nombre_instructor'], // Nuevo
                'fecha_inicio' => $datos_voucher['fecha_inicio'],       // Nuevo
                'fecha_fin' => $datos_voucher['fecha_fin']            // Nuevo
            ];

        } catch (Exception $e) {
            $this->conexion->rollback();
            return $e->getMessage();
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
}
?>