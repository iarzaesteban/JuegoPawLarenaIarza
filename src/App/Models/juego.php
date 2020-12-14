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

    public function __construct($nom,$cantDados,$enfermedades,$comodines,$jugador){
        $this->nombre =$nom;
        for($contador = 0; $contador < count($cantDados); $contador++){
            $this->dados = new Dado();
        }
        for($contador = 0; $contador < count($enfermedades); $contador++){
            $this->enfermedad = new Enfermedad($enfermedades[$contador]);
            $this->jugadores = new Jugador($jugador[$contador]['nombre'],$jugador[$contador]['mail']);
            $this->jugadores[$contador]->setEnfermedad($this->enfermedad[$contador]);
        }
        for($contador = 0; $contador < count($comodines); $contador++){
            $this->comodines = new Carta($comodines[$contador]);//aca estara la decripcion de cada comodin dq nose de dnd sacar
        }
    }

    public $nombre;
    public $estado;
    public $dados= array();
    public $enfermedad = array();
    public $comodines = array();
    public $jugadores = array();
    public $jugadorEnTurno ;

    public function iniciarJuego(){
        $pos = 0;

        for($contador = 0; $contador < count($this->comodines); $contador++){//Mezclamos comodines
            $pos = mt_rand(0,count($this->comodines));
            $carta = $this->comodines[$contador];
            $this->comodines[$contador] = $this->comodines[$pos];
            $this->comodines[$pos] = $carta;
        }
        for($contador = 0; $contador < count($this->comodines); $contador++){//repartimos comodines
            for($contador1 = 0; $contador1 < count($this->jugadores); $contador1++){
                $this->jugadores[$contador1]->setCarta($this->comodines[$contador]);
            }
        }
        $id = mt_rand(0,3);
        $this->jugadorEnTurno =  $this->jugadores[$id];
    }

    
    public function find(){

    }

    public function tirarDado(){
        for($i = 0; $i < count($cantDados); $i++){
            $this->dados->tirarDado();
        }
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

    

    public function obtenerAgentes(){

    }
    
    public function inggresarSala(){

    }    
    
    
    
}

    


?>