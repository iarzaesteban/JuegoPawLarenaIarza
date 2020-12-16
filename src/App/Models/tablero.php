<?php

namespace Src\App\Models;

use src\Core\Model;
use Exception;

use Src\Core\Exceptions\invalidValueFormatException;
use Src\App\Models\casillero;
use Src\App\Models\jugador;


class Tablero extends Model {

    public $table = 'tablero';

    public $casilleros;
    public $filas;
    public $columnas;

    public function __construct($fil = 0,$col = 0){//$descripcionesCasilleros array de desc de casilleros en tablero
        $c = 0;
        $this->filas = $fil;
        $this->columnas = $col;
        $this->fields = [
            'id'    => null,
            "juegoID" => null,
            "cantidadColumnas" => $col
        ];
    //     for ($contador = 0; $contador < $fil; $contador++) {
    //         for ($contador1 = 0; $contador1 < $col; $contador1++) {
    //             $casillero = new Casillero($descripcionesCasilleros[$c]);
    //             $this->casilleros = $casillero;
    //             $c++;
    //        }
    //    }
    }

    public function setCasilleros($casilleros) {
        $this->casilleros = $casilleros;
    }

    public function getCasillerosOcupados(){
        return $this->casilleros;
    }

    // public function setCasilleros(Jugador $jugador,$fila,$columna){//fila y culumna array para setear los lugaros ocupados por el jugador
    //     $this->casilleros[$fila][$columna]->setJugador($jugador);
    // }

    public function getCasillerosOcupadosJugador(Jugador $jugador){
        $tableroAux = array();
        for ($contador = 0; $contador < $this->filas; $contador++) {
            for ($contador1 = 0; $contador1 < $this->columnas; $contador1++) {
                if ($casilleros[$contador][$contador1]->getJugador($jugador)) {
                    $tableroAux[$contador][$contador1] = $jugador;
                }else{
                    $tableroAux[$contador][$contador1] = '0';
                }
           }
       }
        return $tableroAux;
    }  
    
    public function setJuegoId($id) {
        $this->fields["juegoID"] = $id;
    }

    public function load() {
        $this->loadByFields(["juegoID" => $this->fields["juegoID"]]);
        $casillero = new casillero(0,0,$this->logger, $this->connection);
        $casillero->setLogger($this->logger);
        $casillero->setConnection($this->connection);
        $flatCasilleros = $casillero->findByTablero($this);
        $this->casilleros = array();
        $c = 1;
        $fila = array();
        foreach($flatCasilleros as $fields) {
            $this->logger->debug(json_encode($fields));
            $casillero = new casillero;
            $casillero->setPosicion($fields["posicionX"], $fields["posicionY"]);
            array_push($fila, $casillero);
            $c += 1;
            if ($c > $this->fields["cantidadColumnas"]) {
                array_push($this->casilleros, $fila);
                $fila = array();
                $c = 1;
            }
        }
    }

    public function getFilasCasilleros() {
        return $this->casilleros;
    }

    public function save() {
        $res = true;
        $this->loadByFields(["juegoID" => $this->fields["juegoID"]]);
        $this->logger->debug("ID de tablero: ".$this->fields["id"] );
        if (is_null($this->fields["id"])){
            $res = parent::save();
            $this->loadByFields(["juegoID" => $this->fields["juegoID"]]);
            $this->logger->debug("ID de tablero ahora: ".$this->fields["id"] );
        }
        //$this->logger->debug("casilleros: " .json_encode($this->casilleros));
        foreach($this->casilleros as $fila) {
            //$this->logger->debug("fila: " . json_encode($fila));
            foreach($fila as $casillero) {
                $casillero->setTablero($this->fields["id"]);
                $casillero->setLogger($this->logger);
                $casillero->setConnection($this->connection);
                $casillero->save();
            }
        }
        return $res;
    }
}

?>