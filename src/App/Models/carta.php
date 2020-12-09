<?php

namespace App\Models;

use Paw\Core\Model;
use Exception;

use Paw\Core\Exceptions\invalidValueFormatException;


class Carta extends Model {

    public $table = 'carta';
    private $queryBuilder;

    public function setQueryBuilder(QueryBuilder  $qb){
        $this->queryBuilder = $qb;

    }
    public $fields = [
        'id'    => null,
        'nombre'  => null,
        'descripcion'  => null,  
    ];

    public function tirarCarta(){

    }

    public function getDescipcion(){

    }


    
}

?>