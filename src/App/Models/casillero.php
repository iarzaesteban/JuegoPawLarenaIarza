<?php

namespace Src\App\Models;

use Src\Core\Model;
use Exception;

use Ssrc\Core\Exceptions\invalidValueFormatException;
use Src\App\Models\jugador;
use Src\App\Models\tipo_casillero;


class Casillero extends Model {


    public function __construct($class, $dbHandler = null){
        Parent::__construct($class, $dbHandler);
        $this->dbHandler->fields = [
            'id'    => null,
            'posicionX'  => null,
            'posicionY'  => null,
            "tablero" => null,
            "jugador" => ""
        ];
        $this->dbHandler->table = 'casillero';
    }  

    public function incializarCasillero($x = 0, $y = 0) {
        $this->x = $x;
        $this->y = $y;
        $this->dbHandler->setField("posicionX", $x);
        $this->dbHandler->setField("posicionY", $y);
    }

    public $descripcionCasillero;
    public $jugador;

    private $x;
    private $y;

    public function setTablero($tablero) {
        $this->dbHandler->fields["tablero"] = $tablero;
    }

    public function setPosicion($x, $y) {
        $this->dbHandler->fields["posicionX"] = $x;
        $this->dbHandler->fields["posicionY"] = $y;
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
        $this->dbHandler->fields["jugador"] = $jugador;
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
        return $this->findByFields(["tablero" => $tablero->getId()]);
    }

    public function load() {
        $this->loadByFields([
            "tablero" => $this->dbHandler->fields["tablero"],
            "posicionX" => $this->dbHandler->fields["posicionX"],
            "posicionY" => $this->dbHandler->fields["posicionY"],
        ]);
    }

    public function isVacio() {
        if (is_null($this->dbHandler->fields["jugador"])){
            return true;
        }
        return $this->dbHandler->fields["jugador"] == "";
    }

    public function toJson() {
        return "{\"posicionX\" : \"" . $this->dbHandler->fields["posicionX"] . "\", \"posicionY\" : \"" . $this->dbHandler->fields["posicionY"] . "\", \"jugador\" : \"" . $this->dbHandler->fields["jugador"] ."\" },";
    }
}

?>