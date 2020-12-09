<?php

namespace App\Models;

use Paw\Core\Model;
use Exception;

use Paw\Core\Exceptions\invalidValueFormatException;


class Tablero extends Model {

    public $table = 'tablero';
    private $queryBuilder;

    public function setQueryBuilder(QueryBuilder  $qb){
        $this->queryBuilder = $qb;

    }
    public $fields = [
        'id'    => null,
        'casillero'  => null
    ];

    public function getPosicionesOcupadas(){

    }

    public function getListaJugadores($jugador){

    }

    public function getPosicionesValidas(){

    }
    
}

?>