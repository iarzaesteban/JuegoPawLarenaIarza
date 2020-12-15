<?php

namespace Src\App\Models;

use Src\Core\Model;
use Exception;

use Src\Core\Exceptions\invalidValueFormatException;


class Menu_principal extends Model {

    public $table = 'menu_principal';
    private $queryBuilder;

    public function setQueryBuilder(QueryBuilder  $qb){
        $this->queryBuilder = $qb;

    }
    public $fields = [
        'juegos'    => null,
        'puntajes'  => null
    ];

    public function obtenerSalas(){

    }

    public function ingresarSala(){

    }

    public function verTop(){

    }

    public function obtenerEnfermedades(){

    }

    public function verListaJugadores(){

    }

    public function inicarJuego(){

    }
}

?>