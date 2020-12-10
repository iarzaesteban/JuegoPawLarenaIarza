<?php

namespace App\Models;

use Src\Core\Model;
use Exception;

use Src\Core\Exceptions\invalidValueFormatException;


class Casilleros_ocupados extends Model {

    public $table = 'casilleros_ocupados';
    private $queryBuilder;

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