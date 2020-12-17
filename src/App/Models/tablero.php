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
            $casillero->setTablero($this->fields["id"]);
            $casillero->setLogger($this->logger);
            $casillero->setConnection($this->connection);
            $casillero->load();
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

    public function getCeldasValidasStr($jugador) {
        $res = "[";
        $x = 0;
        for ($x = 0; $x < count($this->casilleros); $x++){
            $fila = $this->casilleros[$x];
            for ($y = 0; $y < count($this->casilleros[$x]); $y++){
                if ($this->casilleros[$x][$y]->fields["jugador"] == $jugador) {
                    $this->logger->debug("Casilleros disponibles: Fila: $x -> Columna: $y" . json_encode($this->casilleros[$x][$y]));
                    //todo: verificar paridad
                    if ($x % 2 == 0) {
                        if ((($x -1) > -1) && (($y + 1) < count($this->casilleros[$x]))) {
                            if ($this->casilleros[$x - 1][$y + 1]->isVacio()){
                                $res .= $this->casilleros[$x - 1][$y + 1].toJson();
                            }
                        }
                        if ((($x + 1) < count($this->casilleros[$x])) && (($y -1) > -1)) {
                            if ($this->casilleros[$x + 1][$y - 1]->isVacio()){
                                $res .= $this->casilleros[$x + 1][$y - 1].toJson();
                            }
                        }
                    } else {
                        
                    }
                    if (($x -1) > -1) {
                        if ($this->casilleros[$x - 1][$y]->isVacio()){
                            $res .= $this->casilleros[$x - 1][$y].toJson();
                        }
                    }
                    if (($y + 1) < count($this->casilleros[$x])) {
                        if ($this->casilleros[$x + 1][$y]->isVacio()){
                            $res .= $this->casilleros[$x + 1][$y].toJson();
                        }
                    }
                    if (($y -1) > -1) {
                        if ($this->casilleros[$x][$y - 1]->isVacio()){
                            $res .= $this->casilleros[$x][$y - 1].toJson();
                        }
                    }
                    if (($y + 1) < count($this->casilleros)) {
                        if ($this->casilleros[$x][$y + 1]->isVacio()){
                            $res .= $this->casilleros[$x][$y + 1].toJson();
                        }
                    }
                }
            }
        }
        $res .= "]";
    }
}

?>