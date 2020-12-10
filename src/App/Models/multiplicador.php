<?php

namespace App\Models;

use Src\Core\Model;
use Exception;

use Src\Core\Exceptions\invalidValueFormatException;


class Multiplicador extends Model {

    public $table = 'multiplicador';
    private $queryBuilder;

    public function setQueryBuilder(QueryBuilder  $qb){
        $this->queryBuilder = $qb;

    }
    public $fields = [
        'id'    => null,
        'multiplicador'  => null,
        'turnosDeVida'  => null
    ];

    public function isVigente(){

    }
    
}

?>