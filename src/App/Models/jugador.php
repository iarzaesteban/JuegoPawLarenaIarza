<?php

namespace App\Models;

use Paw\Core\Model;
use Exception;

use Paw\Core\Exceptions\invalidValueFormatException;


class Jugador extends Model {

    public $table = 'jugador';
    private $queryBuilder;

    public function setQueryBuilder(QueryBuilder  $qb){
        $this->queryBuilder = $qb;

    }
    public $fields = [
        'id'    => null,
        'carta'  => null,
        'casillero'  => null,
         
    ];

    public function tirarCarta(){

    }

    public function obtenerComodines(){

    }

    
}

?>