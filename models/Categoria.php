<?php // models/Categoria.php
class Categoria {
    private $conexion;
    public function __construct($db) { $this->conexion = $db; }

    public function getAll() {
        $categorias = [];
        $resultado = $this->conexion->query("SELECT * FROM Categorias ORDER BY nombre");
        while ($fila = $resultado->fetch_assoc()) {
            $categorias[] = $fila;
        }
        return $categorias;
    }

    /**
     * Crea una nueva categoría.
     * @param string $nombre El nombre de la nueva categoría.
     * @return int|false El ID de la nueva categoría si se creó, o false si falló.
     */
    public function create($nombre) {
        $query = "INSERT INTO Categorias (nombre) VALUES (?)";
        $stmt = $this->conexion->prepare($query);
        $nombre_limpio = htmlspecialchars(strip_tags($nombre));
        $stmt->bind_param("s", $nombre_limpio);
        
        if ($stmt->execute()) {
            return $this->conexion->insert_id; // Devolvemos el ID de la nueva categoría
        }
        return false;
    }
}
?>