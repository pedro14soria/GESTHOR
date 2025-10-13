<?php
namespace Model;

class Categoria extends ActiveRecord {
    protected static $tabla = 'categorias';
    protected static $columnasDB = ['id', 'nombre', 'usuario_id', 'creado_en'];

    public $id;
    public $nombre;
    public $usuario_id;
    public $creado_en;
    
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->usuario_id = $args['usuario_id'] ?? '';
        $this->creado_en = $args['creado_en'] ?? null;
    }
}