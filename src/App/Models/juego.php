<?php

namespace Src\App\Models;

use Src\Core\Model;
use Exception;
use PDO;
use Src\App\Models\Jugador;
use Src\App\Factories\CasillerosFactory;
use Src\Core\Exceptions\invalidValueFormatException;


class Juego extends Model {

    public $estadoNoIniciado = "NO_INICIADO";
    public $estadoIniciado = "INICIADO";
    public $columnas = 10;
    public $filasCadaPar = 10;
    
    public function __construct($nom = null,$cantDados = [],$enfermedades = [],$comodines = [],$jugador = null) {
        $this->fields = [
            'id'    => null,
            'nombre'  => null,
            'estado'  => null, 
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
        $query = "SELECT * FROM $this->table WHERE estado = '$this->estadoNoIniciado'";
        $sentencia = $this->connection->prepare($query);
        $sentencia->setFetchMode(PDO::FETCH_ASSOC);
        $sentencia->execute();
        return $sentencia->fetchAll();
    }

    public function setEstadoIniciado() {
        $this->fields["estado"] = $this->estadoIniciado;
    }

    public function iniciarJuego(){
        $pos = 0;
        $this->load();
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
        //$casilleros = $this->generarCasilleros();
        //$this->logger->debug("Casilleros: " . json_encode($casilleros));
        $this->tablero = new Tablero($this->obtenerCantidadFilas(), $this->columnas);
        $this->tablero->setLogger($this->logger);
        $this->tablero->setConnection($this->connection);
        $this->tablero->fields["juegoID"] = $this->fields["id"];
        $this->tablero->fields["cantidadColumnas"] = $this->columnas;
        $this->tablero->setCasilleros($this->generarCasilleros());
        $this->tablero->save();
        $this->jugadores = $this->getJugadores();
        $this->setEstadoJugadores($this->estadoIniciado);
        $this->fields["estado"] = $this->estadoIniciado;
        //$this->logger->debug("Jugadores: " . json_encode($this->jugadores));
        $this->jugadorEnTurno =  $this->jugadores[0]["nombre"];
        $this->fields["jugadorEnTurno"] = $this->jugadores[0]["nombre"];
        $this->save();
    }

    public function obtenerCantidadFilas() {
        return $this->filasCadaPar * intdiv((count($this->jugadores)), 2);
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
    
    public function ingresarSala($jugador){
        $this->load();
        if (count($this->jugadores) <=3 ){
            return $this->agregarJugador($jugador);
        }else{
            print("Maximo 4 jugadores");
            return false;
        }
        
    }   
    
    public function setNombre($nombre) {
        $this->fields["nombre"] = $nombre;
    }

    public function getJugadores() {
        $this->logger->debug("juego->getJugadores()");
        $this->load();
        $query = "SELECT P.* FROM $this->table J JOIN jugador P ON P.juego=J.nombre WHERE ";
        $query .= " J.nombre=:nombre and J.estado='" . $this->fields["estado"];
        $this->logger->debug("query: $query");
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
        return $this->save();
    }

    public function agregarJugador($jugadorNombre) {
        $this->logger->debug("juego->agregarJugador($jugadorNombre)");
        $jugador = new Jugador($jugadorNombre, $this->fields["nombre"]);
        $jugador->fields["estado"] = $this->estadoNoIniciado;
        $jugador->setLogger($this->logger);
        $jugador->setConnection($this->connection);
        if ($jugador->save()) {
            $this->logger->debug("jugador guardado");
            $this->jugadores[] = $jugador;
            return true;
        } else {
            $this->logger->debug("jugador no guardado");
            return false;
        }
    }

    public function isListo() {
        $juego = $this->queryByField("nombre", $this->fields["nombre"]);
        //$this->logger->debug("Estado del juego: ". json_encode($juego));
        return $juego[0]["estado"] == $this->estadoIniciado;
    }

    private function generarCasilleros() {
        return CasillerosFactory::getRandom($this->obtenerCantidadFilas(), $this->columnas);
    }

    public function load() {
        $this->loadByFields([
            "nombre" => $this->fields["nombre"],
            "estado" => $this->fields["estado"]
        ]);
        //$this->logger->debug("Juego: " . json_encode($this->fields));
        $jugador = new Jugador();
        $jugador->setLogger($this->logger);
        $jugador->setConnection($this->connection);
        $this->jugadores = $jugador->findByJuego($this);
        $tablero = new Tablero();
        $tablero->setLogger($this->logger);
        $tablero->setConnection($this->connection);
        $tablero->setJuegoId($this->fields["id"]);
        $tablero->load();
        $this->tablero = $tablero;
    }

    public function getTablero() {
        return $this->tablero;
    }

    public function save() {
        $res = parent::save();
        if (!is_null($this->tablero)) {
            //
            //$this->tablero->save();
        }
        if (!is_null($this->jugadores)) {
            foreach ($this->jugadores as $jugador) {
                $jug = new Jugador();
                $jug->setLogger($this->logger);
                $jug->setConnection($this->connection);
                $jug->setNombre($jugador["nombre"]);
                $jug->setEstado($this->fields["estado"]);
                $jug->setPuntuacion(0);
                $jug->setJuego($this->fields["nombre"]);
                $jug->save();
            }
        }
        if (!is_null($this->comodines)) {
            foreach ($this->comodines as $jugador) {
                
            }
        }
        return $res;
    }

    public function setEstadoJugadores($estado) {
        $jugadores = $this->getJugadores();
        foreach ($jugadores as $flatJug) {
            $jugador = new Jugador($flatJug["nombre"], $flatJug["juego"]);
            $jugador->setLogger($this->logger);
            $jugador->setConnection($this->connection);
            $jugador->fields["estado"] = $estado;
            $jugador->fields["puntuacion"] = $flatJug["puntuacion"];
            $jugador->save();
        }
    }

    public function getFilasCasilleros() {
        $this->load();     
        $this->logger->debug("juego->getFilasCasilleros()");
        //$this->logger->debug("tablero: " . json_encode($this->tablero));
        return $this->tablero->getFilasCasilleros();   
    }

    static public function getAyuda() {
        return "Reglas:
        
        Comienza el juego, y se elige un orden de tirada al azar.
        Cada jugador tira un dado, por turnos.
        Para tirar un dado presionar el botón \"tirar\".
        Luego de tirar, puede ocupar tantos casilleros como indique el número que sacó con el dado.
        Algunas veces, esta cantidad puede verse afectada. 
        Como cuando se está bajo los efectos de una carta, o de alguna casilla.
        Cuando saque un 1, puede usar una carta.
        Para ver las cartas, presionar el botón \"cartas\".
        Las cartas tienen diferentes efectos sobre las fichas del rival.
        Se pueden leer los diferentes efectos en el menú de cartas.
        
        Luego de ocupar un casillero los rivales no pueden quitarseló.
        Pero puede perder el casillero con efectos de cartas.
        Luego de 12 turnos, o cuando no hayan más casilleros disponibles,
        gana el jugador con más cantidad de casilleros.";
    }
}

    


?>
