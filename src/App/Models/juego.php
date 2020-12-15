<?php

namespace Src\App\Models;

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
    public $tablero;

    public function iniciarJuego($configuraciones,$descripciones){
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
                if($contador < 4){
                    $this->jugadores[$contador]->setID($contador);
                }
            }
        }
        $this->tablero = new Tablero($configuraciones->get("filas"), $configuraciones->get("columnas"),$descripciones);

        $id = mt_rand(0,3);

        $this->jugadorEnTurno =  $this->jugadores[$id];
    }

    
    public function find(){

    }

    public function tirarDado(){
        for($i = 0; $i < count($cantDados); $i++){
            $this->dados->tirarDado();
        }
        return $this->dados;
    }

    public function obtenerEnfermedades(){
        return $this->enfermedades;
    } 

    public function tirarComodin(Carta $carta,Jugador $jugador){
        if(in_array($jugador ,$this->jugadores) ){
           $jugador->tirarCarta($carta);
        }
    }

    public function obtenerComodines(){
        return $this->jugadorEnTurno->getCartas();
    }

    public function ocuparCasilleros(Casillero $casilleros){
        $this->tablero->setCasilleros($this->jugadorEnTurno,$casilleros);
    }

    public function cambiarJugador(){
        $id = $this->jugadorEnTurno->getID();
        if(($id + 1) < 4){
            $this->jugadorEnTurno = $this->jugadores[$id + 1];
        }else{
            $this->jugadorEnTurno = $this->jugadores[0];
        }
    }

    public function getJugadorTurno(){
        return $this->jugadorEnTurno;
    }

    public function getListaJugadores(){
        return $this->jugadores;
    }

    public function seleccionarCantidadJugadores(){
        return count($this->jugadores);
    }


    public function obtenerAgentes(){
        return $this->agentes;
    }
    
    public function ingresarSala(Jugador $jugador){
        if (count($this->jugadores) <=3 ){
            $this->jugadores = $jugador;
        }else{
            print("Maximo 4 jugadores");
        }
        
    }    
    
    
    
}

    


?>
