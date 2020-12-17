<?php

namespace Src\App\Models;

use Src\Core\Model;
use Exception;

use Ssrc\Core\Exceptions\invalidValueFormatException;
use Src\App\Models\jugador;
use Src\App\Models\tipo_casillero;


class Casillero extends Model {


    public function __construct($x = 0, $y = 0){
        $this->fields = [
            'id'    => null,
            'posicionX'  => $x,
            'posicionY'  => $y,
            "tablero" => null,
            "jugador" => null
        ];
        $this->x = $x;
        $this->y = $y;
        $this->table = 'casillero';
    }  

    public $descripcionCasillero;
    public $jugador;

    private $x;
    private $y;

    public function setTablero($tablero) {
        $this->fields["tablero"] = $tablero;
    }

    public function setPosicion($x, $y) {
        $this->fields["posicionX"] = $x;
        $this->fields["posicionY"] = $y;
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
    }

    public function getDescipcionCasillero(){
        return $this->descipcionCasillero;
    }


    public function tirarCarta(){

    }
    
    public function ocupar($jugador) {
        $this->setJugador($jugador);
    }

    public function findByTablero($tablero) {
        $this->logger->debug("Tablero: ". json_encode($tablero->fields));
        return $this->findByFields(["tablero" => $tablero->fields["id"]]);
    }

    public function load() {
        $this->loadByFields([
            "tablero" => $this->fields["tablero"],
            "posicionX" => $this->fields["posicionX"],
            "posicionY" => $this->fields["posicionY"],
        ]);
        if ($this->fields["jugador"] != "") {
            $this->logger->debug("JGUADOR ENCONTRADO: " . $this->fields["jugador"]);
        } else {
            $this->logger->debug("JGUADOR NO ENCONTRADO..");
        }
    }

    public function isVacio() {
        if (is_null($this->fields["jugador"])){
            return false;
        }
        return $this->fields["jugador"] != "";
    }

    public function toJson() {
        return "{'posicionX' : '" . $this->fields["posicionX"] . "', 'posicionY' : '" . $this->fields["posicionY"] . "', 'jugador' : '" . $this->fields["jugador"] ."' }";
    }
}

?>