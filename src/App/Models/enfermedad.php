<?php

namespace Src\App\Models;

use Src\Core\Model;
use Exception;

use Src\Core\Exceptions\invalidValueFormatException;


class Enfermedad extends Model {



    public function __construct($e){
        $this->nombreEnfermedad = $e;
    }  


    public $table = 'enfermedad';
    public $nombreEnfermedad;

    public function setEnfermedad($e){
        $this->nombreEnfermedad = $e;
    }

    public function getEnfermedad(){
        return $this->nombreEnfermedad;
    }
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