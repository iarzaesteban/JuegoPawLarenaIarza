<?php

namespace App\Models;

use Paw\Core\Model;
use Exception;

use Paw\Core\Exceptions\invalidValueFormatException;


class Enfermedad extends Model {

    public $table = 'enfermedad';
    private $queryBuilder;

    public function setQueryBuilder(QueryBuilder  $qb){
        $this->queryBuilder = $qb;

    }
    public $fields = [
        'id'    => null,
        'nombre'  => null,
        'descripcion'  => null
    ];


    
}

?>