<?php 
namespace Model;

class Ingresos extends ActiveRecord{
    protected static $tabla = 'ingresos';
    protected static $columnasDB = ['id', 'descripcion', 'monto', 'fecha', 'usuario_id', 'creado_en', 'forma_pago_id'];

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->descripcion = $args['descripcion'] ?? '';
        $this->usuario_id = $args['usuario_id'] ?? '';
        $this->creado_en = $args['creado_en'] ?? null;
        $this->monto = $args['monto'] ?? '';
        $this->fecha = $args['fecha'] ?? '';
        $this->forma_pago_id = $args['forma_pago_id'] ?? '';
    }

    public function validar() {
        if(!$this->descripcion) {
            self::$alertas['error'][] = 'La descripción es Obligatoria';
        }
        if(!$this->monto  || !filter_var($this->monto, FILTER_VALIDATE_FLOAT)) {
            self::$alertas['error'][] = 'El monto es obligatorio';
        }
        if(!$this->fecha) {
            self::$alertas['error'][] = 'La fecha es obligatoria';
        }   
        if(!$this->forma_pago_id  || !filter_var($this->forma_pago_id, FILTER_VALIDATE_INT)) {
            self::$alertas['error'][] = 'Seleccione una forma de pago';
        }

        return self::$alertas;
    }
}