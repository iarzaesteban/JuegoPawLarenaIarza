<?php

namespace Src\App\Models;

use Src\Core\Model;
use Exception;
use PDO;

use Src\Core\Exceptions\invalidValueFormatException;


class Juego extends Model {

    public $estadoNoIniciado = "NO_INICIADO";
    
    public function __construct($nom = null,$cantDados = [],$enfermedades = [],$comodines = [],$jugador = null) {
        $this->fields = [
            'id'    => null,
            'nombre'  => null,
            'estado'  => null,
            'dados'  => null,
            'comodin'  => null,  
            'jugadorEnTurno'  => null,  
            'creador' => null
        ];
        $this->table = 'juego';
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

    private $enfermedades = array();
    public $nombre;
    public $estado;
    public $dados= array();
    public $enfermedad = array();
    public $comodines = array();
    public $jugadores = array();
    public $jugadorEnTurno ;
    public $tablero;
    
    public function obtenerSalasAbiertas() {
        $this->logger->debug("juego->obtenerSalasAbiertas()");
        $query = "SELECT * FROM $this->table WHERE estado = 'abierta'";
        $sentencia = $this->connection->prepare($query);
        $sentencia->setFetchMode(PDO::FETCH_ASSOC);
        $sentencia->execute();
        return $sentencia->fetchAll();
    }

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

    public function tirarCarta(Carta $carta,Jugador $jugador){
        if(in_array($jugador ,$this->enfermedades) ){
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
    
    public function setNombre($nombre) {
        $this->fields["nombre"] = $nombre;
    }

    public function getJugadores() {
        $query = "SELECT * FROM $this->table WHERE nombre=:nombre and estado='$this->estadoNoIniciado'";
        $sentencia = $this->connection->prepare($query);
        $sentencia->bindValue(":nombre", $this->fields["nombre"]);
        $sentencia->setFetchMode(PDO::FETCH_ASSOC);
        $sentencia->execute();
        return $sentencia->fetchAll();
    }
    
    public function crear($usuario) {
        if ($this->hasValue("nombre", $this->fields["nombre"])) {
            return false;
        }
        $this->fields["estado"] = $this->estadoNoIniciado;
        $this->fields["creador"] = $usuario;
        $this->jugadores[] = $usuario;
        return $this->save();
    }
}

    


?>