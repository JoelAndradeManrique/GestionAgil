<?php // controllers/CategoriaController.php
require_once '../models/Categoria.php';
class CategoriaController {
    private $modeloCategoria;
    public function __construct($db) { $this->modeloCategoria = new Categoria($db); }

    public function obtenerTodas() {
        $categorias = $this->modeloCategoria->getAll();
        return ['estado' => 200, 'datos' => $categorias];
    }

     /**
     * Procesa la creación de una nueva categoría.
     */
    public function crearCategoria($datos) {
        if (!isset($datos->nombre) || empty(trim($datos->nombre))) {
            return ['estado' => 400, 'mensaje' => 'Se requiere el nombre de la categoría.'];
        }
        $nuevo_id = $this->modeloCategoria->create($datos->nombre);
        if ($nuevo_id) {
            return ['estado' => 201, 'mensaje' => 'Categoría creada con éxito.', 'nueva_categoria' => ['id' => $nuevo_id, 'nombre' => $datos->nombre]];
        } else {
            return ['estado' => 500, 'mensaje' => 'Error al crear la categoría.'];
        }
    }
}
?>