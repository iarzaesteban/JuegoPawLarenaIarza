<?php

namespace App\Models;

use src\Core\Model;
use Exception;

use src\Core\Exceptions\invalidValueFormatException;
use src\App\Models\posicion;

class Casilleros_ocupados extends Model {

    public $table = 'casilleros_ocupados';
    private $queryBuilder;

    public $ubicacion;

    public function __construct($x,$y){
        $this->ubicacion = new Posicion($x,$y);
    }

    public function setQueryBuilder(QueryBuilder  $qb){
        $this->queryBuilder = $qb;

    }
    public $fields = [
        'id'    => null,
        'ubicacion'  => null,
        'descripcion'  => null
    ];


    
}

?>