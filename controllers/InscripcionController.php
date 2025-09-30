<?php
// controllers/InscripcionController.php

// Incluimos el modelo y la librería FPDF
require_once '../models/Inscripcion.php';
require_once '../lib/fpdf/fpdf.php';

class InscripcionController {
    private $modeloInscripcion;
    public $conexion;

    public function __construct($db) {
        $this->conexion = $db;
        $this->modeloInscripcion = new Inscripcion($db);
    }

    public function inscribirAlumno($datos) {
        // 1. AÑADIMOS LA VALIDACIÓN PARA LOS DATOS DE LA TARJETA
        if (!isset($datos->id_usuario) || !isset($datos->id_curso) || !isset($datos->numero_tarjeta) || !isset($datos->fecha_vencimiento) || !isset($datos->cvv)) {
            return ['estado' => 400, 'mensaje' => 'Se requieren todos los datos: ID de usuario, curso y detalles de la tarjeta.'];
        }

        // --- La lógica para crear la inscripción en la DB no cambia ---
        $resultado = $this->modeloInscripcion->crear($datos->id_curso, $datos->id_usuario);

        if (!is_array($resultado)) {
            return ['estado' => 409, 'mensaje' => 'No se pudo completar la inscripción: ' . $resultado];
        }

        $stmt_alumno = $this->conexion->prepare("SELECT nombre, email FROM Usuarios WHERE id_usuario = ?");
        $stmt_alumno->bind_param("i", $datos->id_usuario);
        $stmt_alumno->execute();
        $alumno = $stmt_alumno->get_result()->fetch_assoc();
        $stmt_alumno->close();
        
        // 2. AHORA PASAMOS LOS DATOS DE LA TARJETA A LA FUNCIÓN DEL PDF
        $ruta_voucher = $this->_generarVoucherPDF($resultado, $alumno, $datos);
        
        return [
            'estado' => 201, 
            'mensaje' => 'Inscripción y pago realizados con éxito.',
            'url_voucher' => $ruta_voucher
        ];
    }
    
    // Función para obtener la lista de inscritos (la dejamos como estaba)
    /**
     * Obtiene la lista de alumnos inscritos en un curso.
     */
    public function obtenerInscritos($id_curso) {
        // Validación para asegurar que el ID es válido
        if (empty($id_curso) || !is_numeric($id_curso)) {
            return ['estado' => 400, 'mensaje' => 'Se requiere un ID de curso válido.'];
        }

        // Llama a la función del modelo para buscar en la base de datos
        $alumnos = $this->modeloInscripcion->getInscritosPorCurso($id_curso);
        
        // Devuelve los datos encontrados (la lista puede estar vacía, y eso está bien)
        return ['estado' => 200, 'datos' => $alumnos];
    }


    // --- NUEVA FUNCIÓN PRIVADA PARA CREAR EL PDF ---
   private function _generarVoucherPDF($datos_inscripcion, $datos_alumno, $datos_pago) {
        $pdf = new FPDF('P', 'mm', 'Letter');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Voucher de Pago - GestionAgil', 0, 1, 'C');
        $pdf->Ln(10);

        // --- Detalles del Alumno (sin cambios) ---
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Detalles del Alumno', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(50, 8, 'Nombre:', 0, 0);
        $pdf->Cell(0, 8, utf8_decode($datos_alumno['nombre']), 0, 1);
        $pdf->Cell(50, 8, 'Correo:', 0, 0);
        $pdf->Cell(0, 8, $datos_alumno['email'], 0, 1);
        $pdf->Ln(5);
        
        // --- ✅ SECCIÓN DE DETALLES DEL CURSO ACTUALIZADA ---
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Detalles del Curso', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        
        $pdf->Cell(50, 8, 'Curso:', 0, 0);
        $pdf->Cell(0, 8, utf8_decode($datos_inscripcion['titulo_curso']), 0, 1);
        
        $pdf->Cell(50, 8, 'Instructor:', 0, 0);
        $pdf->Cell(0, 8, utf8_decode($datos_inscripcion['nombre_instructor']), 0, 1);
        
        // Formateamos las fechas para que se vean mejor
        $fecha_inicio_f = date('d/m/Y', strtotime($datos_inscripcion['fecha_inicio']));
        $fecha_fin_f = date('d/m/Y', strtotime($datos_inscripcion['fecha_fin']));
        
        $pdf->Cell(50, 8, 'Fecha de Inicio:', 0, 0);
        $pdf->Cell(0, 8, $fecha_inicio_f, 0, 1);
        
        $pdf->Cell(50, 8, 'Fecha de Fin:', 0, 0);
        $pdf->Cell(0, 8, $fecha_fin_f, 0, 1);
        $pdf->Ln(5);

        // --- Informacion de Pago (sin cambios) ---
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Informacion de Pago (Simulado)', 0, 1);
        // ... (el resto del código para el pago y guardado del archivo no cambia)
        $pdf->SetFont('Arial', '', 12);
        $tarjeta_oculta = '**** **** **** ' . substr($datos_pago->numero_tarjeta, -4);
        $pdf->Cell(50, 8, 'Tarjeta terminacion:', 0, 0);
        $pdf->Cell(0, 8, $tarjeta_oculta, 0, 1);
        $pdf->Cell(50, 8, 'Fecha de Vencimiento:', 0, 0);
        $pdf->Cell(0, 8, $datos_pago->fecha_vencimiento, 0, 1);
        $pdf->Cell(50, 8, 'Monto Pagado:', 0, 0);
        $pdf->Cell(0, 8, '$' . number_format($datos_inscripcion['monto'], 2) . ' MXN', 0, 1);
        
        $nombre_archivo = 'voucher_' . $datos_inscripcion['id_inscripcion'] . '_' . $datos_pago->id_usuario . '.pdf';
        $ruta_completa = '../vouchers/' . $nombre_archivo;
        $pdf->Output('F', $ruta_completa);

        return 'vouchers/' . $nombre_archivo;
    }

   // controllers/InscripcionController.php
    
    public function obtenerInscripcionesPorAlumno($id_usuario, $filtros) {
        if (empty($id_usuario) || !is_numeric($id_usuario)) {
            return ['estado' => 400, 'mensaje' => 'Se requiere un ID de alumno válido.'];
        }

        $cursos = $this->modeloInscripcion->getByAlumnoId($id_usuario, $filtros);
        return ['estado' => 200, 'datos' => $cursos];
    }
}
?>