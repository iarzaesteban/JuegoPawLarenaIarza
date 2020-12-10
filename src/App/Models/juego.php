<?php

namespace App\Models;

use Src\Core\Model;
use Exception;

use Src\Core\Exceptions\invalidValueFormatException;


class Juego extends Model {

    public $table = 'juego';
    private $queryBuilder;

    public function setQueryBuilder(QueryBuilder  $qb){
        $this->queryBuilder = $qb;

    }
    
    public $fields = [
        'id'    => null,
        'nombre'  => null,
        'estado'  => null,
        'dados'  => null,
        'enfermedades'  => null,  
        'comodin'  => null,  
        'jugadores'  => null,  
        'jugadorEnTurno'  => null,  
    ];

    
    public function find(){

    }

    public function tirarDado(){

    }

    public function obtenerEnfermedad(){

    } 

    public function tirarCarta(){

    }

    public function obtenerComodines(){

    }

    public function ocuparCasilleros(){

    }

    public function getJugadorTurno(){

    }

    public function getListaJugadores(){

    }

    public function seleccionarCantidadJugadores(){

    }

    public function iniciarJuego(){

    }

    public function obtenerAgentes(){

    }
    
    public function inggresarSala(){

    }    
    
    
    
}

    


?>