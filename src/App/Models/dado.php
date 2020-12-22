<?php

namespace Src\App\Models;

use Src\Core\Model;
use Exception;

use Src\Core\Exceptions\invalidValueFormatException;


class Dado extends Model {


    public function __construct(){
        $this->cantCaras = 6;
        $this->dbHanlder->fields = [
            'id'    => null,
            'nombre'  => null,
            'descripcion'  => null
        ];
        $this->dbHanlder->table = 'dado';
    }   
     
    public $cantCaras;
    public $caraSeleccionada;

    public function getCara(){
        return $this->caraSeleccionada;
    }

    public function tirar(){
        $this->caraSeleccionada = mt_rand(1,$this->cantCaras);
    }    
}

?>