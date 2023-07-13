<?php

namespace Model;

class Proyecto extends ActiveRecord{

    protected static $tabla = 'proyecto';
    protected static $columnasDB = ['id', 'proyecto', 'url', 'propetarioId'];

    public $id;
    public $proyecto;
    public $url;
    public $propetarioId;

    public function __construct ($args = []){
        $this->id = $args['id'] ?? null;
        $this->proyecto = $args['proyecto'] ?? '';
        $this->url = $args['url'] ?? '';
        $this->propetarioId = $args['propetario'] ?? '';
    }

    public function validarProyecto(){
        if(!$this->proyecto){
            self::$alertas['error'][] = 'El nombre del proyecto es obligatorio';
        }
        return self::$alertas;
    }
}