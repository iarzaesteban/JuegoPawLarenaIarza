<?php

namespace App\Models;

use Src\Core\Model;
use Exception;

use Src\Core\Exceptions\invalidValueFormatException;


class Carta extends Model {


    public function __construct($cd){
        $this->descripcionCarta = $cd;
    }  

    public $table = 'carta';
    private $queryBuilder;
    public $descripcionCarta;


    public function setDescripcionCarta($cd){
        $this->descripcionCarta = $cd;
    }

    public function getDescipcionCarta(){
        return $this->descipcionCarta;
    }

    public function setQueryBuilder(QueryBuilder  $qb){
        $this->queryBuilder = $qb;
    }
    public $fields = [
        'id'    => null,
        'nombre'  => null,
        'descripcion'  => null,  
    ];


    public function invocar(){
        return $this->descripcionCarta;
    }



    
}

?>