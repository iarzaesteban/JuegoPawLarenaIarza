<?php

namespace Src\App\Models;

use Src\Core\Model;
use Exception;

use src\Core\Exceptions\invalidValueFormatException;
use src\App\Models\jugador;
use src\App\Models\tipo_casillero;


class Casillero extends Model {


    // public function __construct($dc){
    //     $this->descripcionCasillero = new Tipo_casillero($dc);
    // }  

    public $table = 'casillero';

    public $descripcionCasillero;
    public $jugador;


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

    public $fields = [
        'id'    => null,
        'nombre'  => null,
        'descripcion'  => null,  
    ];


    public function tirarCarta(){

    }
    
    public function ocupar($jugador) {
        $this->setJugador($jugador);
    }

    public function findByTablero($tablero) {
        $this->logger->debug("Tablero: ". json_encode($tablero->fields));
        return $this->findByFields(["tablero" => $tablero->fields["id"]]);
    }
}

?>