<?php

namespace App\Models;

use Src\Core\Model;
use Exception;

use Src\Core\Exceptions\invalidValueFormatException;


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