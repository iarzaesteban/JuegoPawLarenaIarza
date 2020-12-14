<?php

namespace App\Models;

use Src\Core\Model;
use Exception;

use Src\Core\Exceptions\invalidValueFormatException;


class Posicion extends Model {

    public $table = 'posicion';
    private $queryBuilder;

    public function __construct($x,$y){
        $this->x = x;
        $this->y = y;
    }

    public $x;
    public $y;

    public function setX($x){
        $this->x = $x;
    }


    public function setY($y){
        $this->y = $y;
    }

    public function getX(){
        return $this->x;
    }


    public function getY(){
        return $this->y;
    }

    public function setQueryBuilder(QueryBuilder  $qb){
        $this->queryBuilder = $qb;
    }
    public $fields = [
        'x'    => null,
        'y'  => null
    ];


    
}

?>