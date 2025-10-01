<?php
// models/Curso.php

class Curso {
    private $conexion;

    public function __construct($db) {
        $this->conexion = $db;
    }

    /**
     * Crea un nuevo curso en la base de datos.
     * @param object $datos Objeto con todos los datos del curso.
     * @return bool True si la creación fue exitosa, false en caso contrario.
     */
    public function create($datos) {
        $query = "INSERT INTO Cursos 
                    (titulo, descripcion, fecha_inicio, fecha_fin, cupo_disponible, precio, estado, modalidad, id_instructor, id_categoria) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conexion->prepare($query);

        // Limpiamos los datos para evitar inyecciones
        $titulo = htmlspecialchars(strip_tags($datos->titulo));
        $descripcion = htmlspecialchars(strip_tags($datos->descripcion));
        $fecha_inicio = htmlspecialchars(strip_tags($datos->fecha_inicio));
        $fecha_fin = htmlspecialchars(strip_tags($datos->fecha_fin));
        $cupo_disponible = intval($datos->cupo_disponible);
        $precio = floatval($datos->precio);
        $estado = htmlspecialchars(strip_tags($datos->estado));
        $modalidad = htmlspecialchars(strip_tags($datos->modalidad));
        $id_instructor = intval($datos->id_instructor);
        $id_categoria = intval($datos->id_categoria);

        // Enlazamos los parámetros con los tipos de datos correctos
        // s: string, i: integer, d: double (decimal)
        $stmt->bind_param("ssssidssii", 
            $titulo, $descripcion, $fecha_inicio, $fecha_fin, 
            $cupo_disponible, $precio, $estado, $modalidad, 
            $id_instructor, $id_categoria
        );

        return $stmt->execute();
    }

    /**
     * Obtiene un solo curso por su ID, incluyendo el nombre del instructor.
     * @param int $id El ID del curso a buscar.
     * @return array|null Los datos del curso o null si no se encuentra.
     */
    public function getById($id) {
        $query = "SELECT c.*, u.nombre as nombre_instructor 
                  FROM Cursos c
                  JOIN Usuarios u ON c.id_instructor = u.id_usuario
                  WHERE c.id_curso = ?";
        
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    /**
     * Actualiza un curso existente en la base de datos.
     * @return int El número de filas afectadas (-1 si hay error, 0 si no hay cambios, 1 si se actualizó).
     */
    public function update($id, $datos) {
        $query = "UPDATE Cursos SET 
                    titulo = ?, descripcion = ?, fecha_inicio = ?, fecha_fin = ?, 
                    cupo_disponible = ?, precio = ?, estado = ?, modalidad = ?, 
                    id_instructor = ?, id_categoria = ? 
                  WHERE id_curso = ?";

        $stmt = $this->conexion->prepare($query);

        $stmt->bind_param("ssssidssiii", 
            $datos->titulo, $datos->descripcion, $datos->fecha_inicio, $datos->fecha_fin, 
            $datos->cupo_disponible, $datos->precio, $datos->estado, $datos->modalidad, 
            $datos->id_instructor, $datos->id_categoria,
            $id
        );

        // Intentamos ejecutar la consulta
        $stmt->execute();
        
        // ✅ DEVOLVEMOS DIRECTAMENTE LAS FILAS AFECTADAS
        return $stmt->affected_rows;
    }

     /**
     * Elimina un curso de la base de datos por su ID.
     * @param int $id El ID del curso a eliminar.
     * @return int El número de filas afectadas (-1 si hay error, 0 si no se encontró, 1 si se borró).
     */
    public function delete($id) {
        $query = "DELETE FROM Cursos WHERE id_curso = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->affected_rows;
    }

    /**
     * Obtiene todos los cursos creados por un instructor específico.
     * @param int $id_instructor El ID del instructor.
     * @return array Una lista de los cursos encontrados.
     */
    public function getByInstructorId($id_instructor) {
        $cursos = []; // Inicializamos un array para guardar los cursos
        $query = "SELECT * FROM Cursos WHERE id_instructor = ?";
        
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $id_instructor);
        $stmt->execute();
        $resultado = $stmt->get_result();

        // Recorremos todos los resultados y los añadimos al array
        while ($fila = $resultado->fetch_assoc()) {
            $cursos[] = $fila;
        }

        return $cursos;
    }

    /**
     * Busca cursos que coincidan con un término en el título o la descripción.
     * @param string $termino La palabra o frase a buscar.
     * @return array Una lista de los cursos encontrados.
     */
    public function buscarCursos($termino) {
        $cursos = [];
        
        // El símbolo '%' es un comodín en SQL. '%palabra%' significa que busca la palabra
        // en cualquier parte del texto (al principio, en medio o al final).
        $termino_busqueda = "%" . $termino . "%";

        $query = "SELECT * FROM Cursos WHERE titulo LIKE ? OR descripcion LIKE ?";
        
        $stmt = $this->conexion->prepare($query);
        // "ss" porque vamos a pasar dos parámetros de tipo string (s)
        $stmt->bind_param("ss", $termino_busqueda, $termino_busqueda);
        $stmt->execute();
        $resultado = $stmt->get_result();

        while ($fila = $resultado->fetch_assoc()) {
            $cursos[] = $fila;
        }

        return $cursos;
    }

    /**
     * Obtiene todos los cursos publicados, junto con el nombre del instructor.
     * @return array Una lista de todos los cursos.
     */
    public function getAll() {
        $cursos = [];
        $query = "SELECT c.*, u.nombre as nombre_instructor 
                  FROM Cursos c
                  JOIN Usuarios u ON c.id_instructor = u.id_usuario
                  WHERE c.estado = 'publicado' 
                  ORDER BY c.id_curso DESC";
        
        $resultado = $this->conexion->query($query);

        while ($fila = $resultado->fetch_assoc()) {
            $cursos[] = $fila;
        }
        return $cursos;
    }

    /**
     * Busca un curso por título e ID de instructor para evitar duplicados.
     * @param string $titulo El título del curso.
     * @param int $id_instructor El ID del instructor.
     * @return array|null Los datos del curso si se encuentra, o null.
     */
    public function findByTitleAndInstructor($titulo, $id_instructor) {
        $query = "SELECT id_curso FROM Cursos WHERE titulo = ? AND id_instructor = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("si", $titulo, $id_instructor);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

     

}
?>