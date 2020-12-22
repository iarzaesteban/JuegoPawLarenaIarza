<?php

namespace Src\App\Models;

use Src\Core\Model;
use Exception;

use Ssrc\Core\Exceptions\invalidValueFormatException;
use Src\App\Models\jugador;
use Src\App\Models\tipo_casillero;


class Casillero extends Model {


    public function __construct($x = 0, $y = 0){
        $this->dbHanlder->fields = [
            'id'    => null,
            'posicionX'  => $x,
            'posicionY'  => $y,
            "tablero" => null,
            "jugador" => ""
        ];
        $this->x = $x;
        $this->y = $y;
        $this->dbHanlder->table = 'casillero';
    }  

    public $descripcionCasillero;
    public $jugador;

    private $x;
    private $y;

    public function setTablero($tablero) {
        $this->dbHanlder->fields["tablero"] = $tablero;
    }

    public function setPosicion($x, $y) {
        $this->dbHanlder->fields["posicionX"] = $x;
        $this->dbHanlder->fields["posicionY"] = $y;
    }

    public function setDescripcionCasillero($dc){
        $this->descripcionCasillero = $dc;
    }

    public function setCasillero(Jugador $jugador){
        $this->jugador = $jugador;
    }

    public function getJugador(){
        return $this->jugador;
    }

    public function setJugador($jugador) {
        $this->jugador = $jugador;
        $this->dbHanlder->fields["jugador"] = $jugador;
    }

    public function getDescipcionCasillero(){
        return $this->descipcionCasillero;
    }


    public function tirarCarta(){

    }
    
    public function ocupar($jugador) {
        $this->setJugador($jugador);
        $this->update();
    }

    public function findByTablero($tablero) {
        $this->logger->debug("Tablero: ". json_encode($tablero->fields));
        return $this->findByFields(["tablero" => $tablero->fields["id"]]);
    }

    public function load() {
        $this->loadByFields([
            "tablero" => $this->dbHanlder->fields["tablero"],
            "posicionX" => $this->dbHanlder->fields["posicionX"],
            "posicionY" => $this->dbHanlder->fields["posicionY"],
        ]);
    }

    public function isVacio() {
        if (is_null($this->dbHanlder->fields["jugador"])){
            return true;
        }
        return $this->dbHanlder->fields["jugador"] == "";
    }

    public function toJson() {
        return "{\"posicionX\" : \"" . $this->dbHanlder->fields["posicionX"] . "\", \"posicionY\" : \"" . $this->dbHanlder->fields["posicionY"] . "\", \"jugador\" : \"" . $this->dbHanlder->fields["jugador"] ."\" },";
    }
}

?>