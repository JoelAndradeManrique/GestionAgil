<?php // controllers/CategoriaController.php
require_once '../models/Categoria.php';
class CategoriaController {
    private $modeloCategoria;
    public function __construct($db) { $this->modeloCategoria = new Categoria($db); }

    public function obtenerTodas() {
        $categorias = $this->modeloCategoria->getAll();
        return ['estado' => 200, 'datos' => $categorias];
    }
}
?>