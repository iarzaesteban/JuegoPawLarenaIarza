<?php

namespace App\Models;

use Paw\Core\Model;
use Exception;

use Paw\Core\Exceptions\invalidValueFormatException;


class Puntaje extends Model {

    public $table = 'puntaje';
    private $queryBuilder;

    public function setQueryBuilder(QueryBuilder  $qb){
        $this->queryBuilder = $qb;

    }
    public $fields = [
        'id'    => null,
        'nombreJugador'  => null,
        'casilleroOcupado' => null,
        'tiempo' => null
    ];


    
}

?>