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
    public $multiplciador;
    public $turnoDeVida;
    public $isVigente;  
    
    
    public function setMultiplicador($m){
        $this->multiplicador = $m;
    }

    public function setTurnoDeVida($tv){
        $this->turnoDeVida = $tv;
    }

    public function setIsVigente($v){
        $this->isVigente = $v;
    }

    public function getMultiplicador(){
        return $this->multiplicador;
    }


    public function getTurnoDeVida(){
        return $this->turnoDeVida;
    }

    public function getIsVigente(){
        return $this->isVigente;
    }


}

?>