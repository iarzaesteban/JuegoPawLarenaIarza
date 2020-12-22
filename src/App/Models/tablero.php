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
        $this->dbHanlder->fields = [
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

    public function ocuparPorPosicion($posicionX, $posicionY, $jugador) {
        $casillero = new Casillero();
        $casillero->setLogger($this->logger);
        $casillero->setConnection($this->connection);
        $casillero->setTablero($this->dbHanlder->fields["id"]);
        $casillero->setJugador($jugador);
        $casillero->update();
    }

    public function ocupar($json, $jugador) {
        $res = false;
        try {
            foreach($json as $flatCasillero) {
                $array = explode("_",$flatCasillero);
                $casillero = new Casillero();
                $casillero->setLogger($this->logger);
                $casillero->setConnection($this->connection);
                $casillero->fields["posicionX"] = $array[1];
                $casillero->fields["posicionY"] = $array[2];
                $casillero->fields["tablero"] = $this->dbHanlder->fields["id"];
                $casillero->load();
                if ($casillero->isVacio()) {
                    $casillero->ocupar($jugador);
                    $res = true;
                }
            }
        } catch (Exception $e) {
            return false;
        }

        return $res;
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
        $this->dbHanlder->fields["juegoID"] = $id;
    }

    public function load() {
        $this->loadByFields(["juegoID" => $this->dbHanlder->fields["juegoID"]]);
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
            $casillero->setTablero($this->dbHanlder->fields["id"]);
            $casillero->setLogger($this->logger);
            $casillero->setConnection($this->connection);
            $casillero->load();
            array_push($fila, $casillero);
            $c += 1;
            if ($c > $this->dbHanlder->fields["cantidadColumnas"]) {
                array_push($this->casilleros, $fila);
                $fila = array();
                $c = 1;
            }
        }
    }

    public function getFilasCasilleros() {
        $this->logger->debug("tablero->getFilasCasilleros(): " . json_encode($this->casilleros));
        return $this->casilleros;
    }

    public function save() {
        $res = true;
        $this->loadByFields(["juegoID" => $this->dbHanlder->fields["juegoID"]]);
        $this->logger->debug("ID de tablero: ".$this->dbHanlder->fields["id"] );
        if (is_null($this->dbHanlder->fields["id"])){
            $res = parent::save();
            $this->loadByFields(["juegoID" => $this->dbHanlder->fields["juegoID"]]);
            $this->logger->debug("ID de tablero ahora: ".$this->dbHanlder->fields["id"] );
        }
        //$this->logger->debug("casilleros: " .json_encode($this->casilleros));
        foreach($this->casilleros as $fila) {
            //$this->logger->debug("fila: " . json_encode($fila));
            foreach($fila as $casillero) {
                $casillero->setTablero($this->dbHanlder->fields["id"]);
                $casillero->setLogger($this->logger);
                $casillero->setConnection($this->connection);
                $casillero->save();
            }
        }
        return $res;
    }

    public function getCeldasValidasStr($jugador) {
        $this->logger->debug("tablero->getCeldasValidasStr($jugador)");
        $res = "[";
        $x = 0;
        $this->logger->debug("Casilleros cargados en tablero: " . json_encode($this->casilleros));
        for ($x = 0; $x < count($this->casilleros); $x++){
            $fila = $this->casilleros[$x];
            for ($y = 0; $y < count($this->casilleros[$x]); $y++){
                if ($this->casilleros[$x][$y]->fields["jugador"] == $jugador) {
                    $this->logger->debug("Casilleros disponibles: jugador-> $jugador  Fila: $x -> Columna: $y" . json_encode($this->casilleros[$x][$y]));
                    //todo: verificar paridad
                    if ($x % 2 == 0) {
                        if ((($x -1) > -1) && (($y + 1) < count($this->casilleros[$x]))) {
                            if ($this->casilleros[$x - 1][$y + 1]->isVacio()){
                                $res .= $this->casilleros[$x - 1][$y + 1]->toJson();
                                $this->logger->debug("Casilleros disponibles: jugador-> $jugador  Fila: $x -> Columna: $y" . json_encode($this->casilleros[$x][$y]));
                            }
                        }
                        if ((($x + 1) < count($this->casilleros)) && (($y -1) > -1)) {
                            if ($this->casilleros[$x + 1][$y - 1]->isVacio()){
                                $res .= $this->casilleros[$x + 1][$y - 1]->toJson();
                                $this->logger->debug("Casilleros disponibles: jugador-> $jugador  Fila: $x -> Columna: $y" . json_encode($this->casilleros[$x][$y]));
                            }
                        }
                    } else {
                        if ((($y -1) > -1) && (($x + 1) < count($this->casilleros))) {
                            if ($this->casilleros[$x + 1][$y - 1]->isVacio()){
                                $res .= $this->casilleros[$x + 1][$y - 1]->toJson();
                                $this->logger->debug("Casilleros disponibles: jugador-> $jugador  Fila: $x -> Columna: $y" . json_encode($this->casilleros[$x][$y]));
                            }
                        }
                        if ((($y + 1) < count($this->casilleros[$x])) && (($x -1) > -1)) {
                            if ($this->casilleros[$x - 1][$y + 1]->isVacio()){
                                $res .= $this->casilleros[$x - 1][$y + 1]->toJson();
                                $this->logger->debug("Casilleros disponibles: jugador-> $jugador  Fila: $x -> Columna: $y" . json_encode($this->casilleros[$x][$y]));
                            }
                        }
                    }
                    if (($x -1) > -1) {
                        if ($this->casilleros[$x - 1][$y]->isVacio()){
                            $res .= $this->casilleros[$x - 1][$y]->toJson();
                            $this->logger->debug("Casilleros disponibles: jugador-> $jugador  Fila: $x -> Columna: $y" . json_encode($this->casilleros[$x][$y]));
                        }
                    }
                    if (($x + 1) < count($this->casilleros)) {
                        if ($this->casilleros[$x + 1][$y]->isVacio()){
                            $res .= $this->casilleros[$x + 1][$y]->toJson();
                            $this->logger->debug("Casilleros disponibles: jugador-> $jugador  Fila: $x -> Columna: $y" . json_encode($this->casilleros[$x][$y]));
                        }
                    }
                    if (($y -1) > -1) {
                        if ($this->casilleros[$x][$y - 1]->isVacio()){
                            $res .= $this->casilleros[$x][$y - 1]->toJson();
                            $this->logger->debug("Casilleros disponibles: jugador-> $jugador  Fila: $x -> Columna: $y" . json_encode($this->casilleros[$x][$y]));
                        }
                    }
                    if (($y + 1) < count($this->casilleros[$x])) {
                        if ($this->casilleros[$x][$y + 1]->isVacio()){
                            $res .= $this->casilleros[$x][$y + 1]->toJson();
                            $this->logger->debug("Casilleros disponibles: jugador-> $jugador  Fila: $x -> Columna: $y" . json_encode($this->casilleros[$x][$y]));
                        }
                    }
                }
            }
        }
        if (strlen($res) > 1){
            $res = substr($res,0,strlen($res) - 1); # sin la ultima coma
        }
        $res .= "]";
        return $res;
    }
}

?>