<?php

namespace Src\App\Models;

use Src\Core\Model;
use Exception;

use Src\Core\Exceptions\invalidValueFormatException;


class Agente extends Model {

    public function __construct($a){
        $this->nombreAgente = $a;
    }   

    public $table = 'agente';
    public $nombreAgente;

    public function setAgente($a){
        $this->nombreAgente = $a;
    }

    public function getAgente(){
        return $this->nombreAgente;
    }

    //podria tener un atributo que sea una lista de defectos 

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