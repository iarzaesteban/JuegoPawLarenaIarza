<?php

namespace App\Models;

use Paw\Core\Model;
use Exception;

use Paw\Core\Exceptions\invalidValueFormatException;


class Posicion extends Model {

    public $table = 'posicion';
    private $queryBuilder;

    public function setQueryBuilder(QueryBuilder  $qb){
        $this->queryBuilder = $qb;

    }
    public $fields = [
        'x'    => null,
        'y'  => null
    ];


    
}

?>