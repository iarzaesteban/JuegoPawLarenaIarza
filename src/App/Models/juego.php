<?php

namespace Src\App\Models;

use Src\Core\Model;
use Exception;
use PDO;
use Src\App\Models\Jugador;
use Src\App\Models\Dado;
use Src\App\Factories\CasillerosFactory;
use Src\Core\Exceptions\invalidValueFormatException;


class Juego extends Model {

    public $estadoNoIniciado = "NO_INICIADO";
    public $estadoIniciado = "INICIADO";
    public $columnas = 10;
    public $filasCadaPar = 10;
    
    public function __construct($class, $dbHandler = null) {
        Parent::__construct($class, $dbHandler);
        $this->dbHandler->fields = [
            'id'    => null,
            'nombre'  => null,
            'estado'  => null, 
            'jugadorEnTurno'  => null,  
            'creador' => null,
            'notificacion' => null,
            'ultimoNumero' => null,
            'esperandoTirada' => null
        ];
        $this->dbHandler->table = 'juego';
    }

    public function inicializarJuego($nom = null,$cantDados = [],$enfermedades = [],$comodines = [],$jugador = null) {
        $this->nombre =$nom;
        for($contador = 0; $contador < count($cantDados); $contador++){
            $this->dados = new Dado();
        }
        for($contador = 0; $contador < count($enfermedades); $contador++){
            $this->enfermedad = new Enfermedad($enfermedades[$contador]);
            //todo:
            //$this->jugadores = new Jugador($jugador[$contador]['nombre'],$jugador[$contador]['mail']);
            $this->jugadores[$contador]->setEnfermedad($this->enfermedad[$contador]);
        }
        for($contador = 0; $contador < count($comodines); $contador++){
            $this->comodines = new Carta($comodines[$contador]);//aca estara la decripcion de cada comodin dq nose de dnd sacar
        }
    }

    public function getId() { return $this->dbHandler->getField("id"); }

    public function setId($id) { return $this->dbHandler->setField("id", $id); }

    public function getNombre() { return $this->dbHandler->getField("nombre"); }

    public function setNombre($nombre) { return $this->dbHandler->setField("nombre", $nombre); }

    public function getEstado() { return $this->dbHandler->getField("estado"); }

    public function setEstado($estado) { return $this->dbHandler->setField("estado", $estado); }

    public function getJugadorEnTurno() { return $this->dbHandler->getField("jugadorEnTurno"); }

    public function setJugadorEnTurno($jugadorEnTurno) { return $this->dbHandler->setField("jugadorEnTurno", $jugadorEnTurno); }

    public function getCreador() { return $this->dbHandler->getField("creador"); }

    public function setCreador($creador) { return $this->dbHandler->setField("creador", $creador); }

    public function getNotificacion() { return $this->dbHandler->getField("notificacion"); }

    public function setNotificacion($notificacion) { return $this->dbHandler->setField("notificacion", $notificacion); }

    public function getUltimoNumero() { return $this->dbHandler->getField("ultimoNumero"); }

    public function setUltimoNumero($ultimoNumero) { return $this->dbHandler->setField("ultimoNumero", $ultimoNumero); }

    public function isEsperandoTirada() { return $this->dbHandler->getField("esperandoTirada"); }

    public function setEsperandoTirada($esperandoTirada) { return $this->dbHandler->setField("esperandoTirada", $esperandoTirada); }

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
        $query = "SELECT * FROM " . $this->dbHandler->table ." WHERE estado = '$this->estadoNoIniciado'";
        $sentencia = $this->connection->prepare($query);
        $sentencia->setFetchMode(PDO::FETCH_ASSOC);
        $sentencia->execute();
        return $sentencia->fetchAll();
    }

    public function setEstadoIniciado() {
        $this->dbHandler->fields["estado"] = $this->estadoIniciado;
    }

    public function setEstadoNoIniciado() {
        $this->dbHandler->fields["estado"] = $this->estadoNoIniciado;
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
        $this->tablero = Model::factory("Tablero");
        $this->tablero->inicializarTablero($this->obtenerCantidadFilas(), $this->columnas);
        $this->tablero->setLogger($this->logger);
        $this->tablero->setConnection($this->connection);
        $this->tablero->fields["juegoID"] = $this->dbHandler->fields["id"];
        $this->tablero->fields["cantidadColumnas"] = $this->columnas;
        $this->tablero->setCasilleros($this->generarCasilleros());
        //todo: agregar jugadores a casillero
        $this->tablero->save();
        $this->jugadores = $this->getJugadores();
        $this->setEstadoJugadores($this->estadoIniciado);
        $this->dbHandler->fields["estado"] = $this->estadoIniciado;
        $this->logger->debug("Iniciar juego: Jugadores: " . json_encode($this->jugadores));
        $this->jugadorEnTurno =  $this->jugadores[0]["nombre"];
        $this->dbHandler->fields["jugadorEnTurno"] = $this->jugadores[0]["nombre"];
        $this->setEsperandoTirada("S");
        $this->setEstadoIniciado();
        $this->update();
    }

    public function obtenerCantidadFilas() {
        return $this->filasCadaPar * intdiv((count($this->jugadores)), 2);
    }
    
    public function find(){

    }

    public function tirarDado($jugador){
        if ($jugador == $this->dbHandler->fields["jugadorEnTurno"]){
            if ($this->isEsperandoTirada()) {
                $this->dado = new Dado();
                $this->dado->tirar();
                $this->dbHandler->fields["ultimoNumero"] = $this->dado->getCara();
                $this->setTiraron();
                $this->update();
                return "Haz tirado un dado, sacaste: " . $this->dbHandler->fields["ultimoNumero"];
            } else {
                return "Acabas de tirar...";
            }
        } else {
            return "Tirada no autorizada. " . $this->getNotificacion();
        }
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

    public function ocupar($json, $jugador) {
        if ($this->dbHandler->fields["jugadorEnTurno"] == $jugador){
            $res = $this->tablero->ocupar($json, $jugador);
            if ($res) {
                $this->asignarSiguienteJugador();
                $this->update();
            }
            return $res;
        }
        return true;
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

    public function getJugadores() {
        $this->logger->debug("juego->getJugadores()");
        $this->load();
        $query = "SELECT P.* FROM " . $this->dbHandler->table . " J JOIN jugador P ON P.juego=J.nombre WHERE ";
        $query .= " J.nombre=:nombre and P.estado=:estado";
        $this->logger->debug("query: $query");
        $sentencia = $this->connection->prepare($query);
        $sentencia->bindValue(":nombre", $this->dbHandler->fields["nombre"]);
        $sentencia->bindValue(":estado", $this->dbHandler->fields["estado"]);
        $sentencia->setFetchMode(PDO::FETCH_ASSOC);
        $sentencia->execute();
        return $sentencia->fetchAll();
    }
    
    public function crear($usuario) {
        if ($this->hasValue("nombre", $this->dbHandler->fields["nombre"])) {
            return false;
        }
        $this->dbHandler->fields["estado"] = $this->estadoNoIniciado;
        $this->dbHandler->fields["creador"] = $usuario;
        return $this->save();
    }

    public function agregarJugador($jugadorNombre) {
        $this->logger->debug("juego->agregarJugador($jugadorNombre)");
        $jugador = Model::factory("Jugador");
        $jugador->inicializarJugador($jugadorNombre, $this->dbHandler->fields["nombre"]);
        $jugador->fields["estado"] = $this->estadoNoIniciado;
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
        $juego = $this->queryByField("nombre", $this->dbHandler->fields["nombre"]);
        //$this->logger->debug("Estado del juego: ". json_encode($juego));
        return $juego[0]["estado"] == $this->estadoIniciado;
    }

    private function generarCasilleros() {
        return CasillerosFactory::getRandom($this->obtenerCantidadFilas(), $this->columnas);
    }

    public function load() {
        $this->loadByFields([
            "nombre" => $this->dbHandler->fields["nombre"],
            "estado" => $this->dbHandler->fields["estado"]
        ]);
        $this->logger->debug("Juego: " . json_encode($this->dbHandler->fields));
        $jugador = Model::factory("Jugador");
        $this->jugadores = $jugador->findByJuego($this);
        $tablero = Model::factory("Tablero");
        $tablero->setJuegoId($this->dbHandler->fields["id"]);
        $tablero->load();
        $i = 0;
        foreach($this->jugadores as $jugador){
            if (($i % 2) == 1){
                $tablero->ocuparPorPosicion(($i + 10), 10, $jugador["nombre"]);
                $i += 1;
            } else {
                $tablero->ocuparPorPosicion(($i + 10), 1, $jugador["nombre"]);
            }
        }
        $this->logger->debug("Tablero load: " . json_encode($tablero));
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
                $jugador = Model::factory("Jugador");
                $jug->setNombre($jugador["nombre"]);
                $jug->setEstado($this->dbHandler->fields["estado"]);
                $jug->setPuntuacion(0);
                $jug->setJuego($this->dbHandler->fields["nombre"]);
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
            $jugador = Model::factory("Jugador");
            $jugador->inicializarJugador($flatJug["nombre"], $flatJug["juego"]);
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

    public function isJugadorTirando($jugador) {
        return $this->dbHandler->fields["jugadorEnTurno"] == $jugador;
    }

    /*public function setEsperandoTirada() {
        $this->dbHandler->fields["esperandoTirada"] = "S";
    }*/

    public function setTiraron() {
        $this->dbHandler->fields["esperandoTirada"] = "N";
    }

    /*public function isEsperandoTirada() {
        return $this->dbHandler->fields["esperandoTirada"] == "S";
    }*/

    public function update() {
        //$this->dbHandler->fields["id"] = intval($this->dbHandler->fields["id"]);
        parent::update();
    }

    public function getCeldasValidasStr($jugador) {
        $this->logger->debug("juego->getCeldasValidasStr($jugador)");
        return $this->tablero->getCeldasValidasStr($jugador);
    }

    public function asignarSiguienteJugador() {
        $idx = 0;
        $sigIdx = 0;
        for ($idx = 0; $idx < count($this->jugadores) ; $idx++) {
            if ($this->jugadores[$idx]["nombre"] == $this->dbHandler->fields["jugadorEnTurno"]) {
                $sigIdx = ($idx + 1) % count($this->jugadores);
            }
        }
        $this->dbHandler->fields["jugadorEnTurno"] = $this->jugadores[$sigIdx]["nombre"];
        $this->setEsperandoTirada("S");
        $this->update();
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
