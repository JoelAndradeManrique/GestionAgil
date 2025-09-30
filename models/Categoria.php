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
}
?>