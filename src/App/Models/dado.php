<?php

namespace App\Models;

use Src\Core\Model;
use Exception;

use Src\Core\Exceptions\invalidValueFormatException;


class Dado extends Model {


    public function __construct(){
        $this->cantCaras = 6;
    }   
     
    public $table = 'dado';
    private $queryBuilder;

    public $cantCaras;
    public $caraSeleccionada;

    public function getCara(){
        return $this->caraSeleccionada;
    }

    public function setCara($c){
        $this->caraSleccionada = $c;
    }

    public function tirar(){
        $this->caraSeleccionada = mt_rand(1,$cantCaras);
    }

    public function setQueryBuilder(QueryBuilder  $qb){
        $this->queryBuilder = $qb;

    }
    public $fields = [
        'id'    => null,
        'nombre'  => null,
        'descripcion'  => null
    ];

    puclic


    
}

?>