<?php

namespace Model;

class Servicio extends ActiveRecord{
    //base de datos
    protected static $tabla = 'servicios';
    protected static $columnasDB = ['id', 'nombre', 'precio'];

    public $id;
    public $nombre;
    public $precio;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->precio = $args['precio'] ?? '';
    }

    public function validar(){
        if(!$this->nombre){
            self::$errores['error'][] = 'El Nombre ser servicio es Obligario';
        }
        if(!$this->precio){
            self::$errores['error'][] = 'El precio ser servicio es Obligario';
        }
        if(!is_numeric($this->precio)){
            self::$errores['error'][] = 'Formato no valido';
        }

        return self::$errores;
    }
}