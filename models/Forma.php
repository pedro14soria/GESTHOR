<?php
namespace Model;

class Forma extends ActiveRecord {
    protected static $tabla = 'formas_pago';
    protected static $columnasDB = ['id', 'usuario_id', 'nombre', 'creado_en'];

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