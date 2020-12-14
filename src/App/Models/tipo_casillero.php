<?php

namespace App\Models;

use Src\Core\Model;
use Exception;

use Src\Core\Exceptions\invalidValueFormatException;


class Tipo_casillero extends Model {

    public $table = 'tipo_casillero';
    private $queryBuilder;

    public function __construct($desc){
        $this->descripcion = $desc;
    }

    public $descripcion;

    public function setQueryBuilder(QueryBuilder  $qb){
        $this->queryBuilder = $qb;

    }
    public $fields = [
        'id'    => null,
        'descripcion'  => null
    ];


    
}

?>