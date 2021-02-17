<?php

namespace Src\App\Models;

use src\Core\Model;
use Exception;


class Tablero extends Model
{

    public $table = 'tablero';

    public $casilleros;
    public $filas;
    public $columnas;

    public function __construct($dbHandler = null, $parameters = null)
    {//$descripcionesCasilleros array de desc de casilleros en tablero
        Parent::__construct($dbHandler);
        if (is_null($parameters)) {
            $this->dbHandler->addField("id");
            $this->dbHandler->addField("juegoID");
            $this->dbHandler->addField("cantidadColumnas");
        } else {
            if (array_key_exists("nombre", $parameters)) {
                $this->set("nombre", $parameters["nombre"]);
                $this->load();
            } else {
                $this->setParameters($parameters);
            }
        }
        $this->setTableName("tablero");
        //     for ($contador = 0; $contador < $fil; $contador++) {
        //         for ($contador1 = 0; $contador1 < $col; $contador1++) {
        //             $casillero = new Casillero($descripcionesCasilleros[$c]);
        //             $this->casilleros = $casillero;
        //             $c++;
        //        }
        //    }
    }

    public function inicializarTablero($fil = 0, $col = 0)
    {
        $this->filas = $fil;
        $this->columnas = $col;
        $this->dbHandler->setField("cantidadColumnas", $col);
    }

    public function getId()
    {
        return $this->dbHandler->getField("id");
    }

    public function ocuparPorPosicion($posicionX, $posicionY, $jugador)
    {
        $casillero = Model::factory("Casillero");
        $casillero->setTablero($this->dbHandler->fields["id"]);
        $casillero->set("posicionX", $posicionX);
        $casillero->set("posicionY", $posicionY);
        $casillero->setJugador($jugador);
        $casillero->update();
    }

    public function ocupar($json, $jugador)
    {
        $res = false;
        try {
            foreach ($json as $flatCasillero) {
                $array = explode("_", $flatCasillero);
                $casillero = Model::factory("Casillero");
                $casillero->fields["posicionX"] = $array[1];
                $casillero->fields["posicionY"] = $array[2];
                $casillero->fields["tablero"] = $this->dbHandler->fields["id"];
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

    public function setCasilleros($casilleros)
    {
        $this->casilleros = $casilleros;
    }

    public function getCasillerosOcupados()
    {
        return $this->casilleros;
    }

    // public function setCasilleros(Jugador $jugador,$fila,$columna){//fila y culumna array para setear los lugaros ocupados por el jugador
    //     $this->casilleros[$fila][$columna]->setJugador($jugador);
    // }

    public function getCasillerosOcupadosJugador(Jugador $jugador)
    {
        $tableroAux = array();
        for ($contador = 0; $contador < $this->filas; $contador++) {
            for ($contador1 = 0; $contador1 < $this->columnas; $contador1++) {
                if ($this->casilleros[$contador][$contador1]->getJugador($jugador)) {
                    $tableroAux[$contador][$contador1] = $jugador;
                } else {
                    $tableroAux[$contador][$contador1] = '0';
                }
            }
        }
        return $tableroAux;
    }

    public function setJuegoId($id)
    {
        $this->dbHandler->fields["juegoID"] = $id;
    }

    public function load($find = null): bool
    {
        $this->loadByFields(["juegoID" => $this->dbHandler->fields["juegoID"]]);
        $casillero = Model::factory("Casillero");
        $flatCasilleros = $casillero->findByTablero($this);
        $this->casilleros = array();
        $c = 1;
        $fila = array();
        foreach ($flatCasilleros as $fields) {
            $this->logger->debug(json_encode($fields));
            $casillero = Model::factory("Casillero");
            $casillero->setPosicion($fields["posicionX"], $fields["posicionY"]);
            $casillero->setTablero($this->dbHandler->fields["id"]);
            $casillero->load();
            array_push($fila, $casillero);
            $c += 1;
            if ($c > $this->dbHandler->fields["cantidadColumnas"]) {
                array_push($this->casilleros, $fila);
                $fila = array();
                $c = 1;
            }
        }
        return true;
    }

    public function getFilasCasilleros()
    {
        $this->logger->debug("tablero->getFilasCasilleros(): " . json_encode($this->casilleros));
        return $this->casilleros;
    }

    public function save($find = null) : bool
    {
        $res = true;
        $this->loadByFields(["juegoID" => $this->dbHandler->fields["juegoID"]]);
        $this->logger->debug("ID de tablero: " . $this->dbHandler->fields["id"]);
        if (is_null($this->dbHandler->fields["id"])) {
            $res = parent::save();
            $this->loadByFields(["juegoID" => $this->dbHandler->fields["juegoID"]]);
            $this->logger->debug("ID de tablero ahora: " . $this->dbHandler->fields["id"]);
        }
        //$this->logger->debug("casilleros: " .json_encode($this->casilleros));
        foreach ($this->casilleros as $fila) {
            //$this->logger->debug("fila: " . json_encode($fila));
            foreach ($fila as $casillero) {
                $casillero->setTablero($this->dbHandler->fields["id"]);
                $casillero->setLogger($this->logger);
                $casillero->setConnection($this->connection);
                $casillero->save();
            }
        }
        return $res;
    }

    public function getCeldasValidasStr($jugador)
    {
        $this->logger->debug("tablero->getCeldasValidasStr($jugador)");
        $res = "[";
        $this->logger->debug("Casilleros cargados en tablero: " . json_encode($this->casilleros));
        for ($x = 0; $x < count($this->casilleros); $x++) {
            for ($y = 0; $y < count($this->casilleros[$x]); $y++) {
                if ($this->casilleros[$x][$y]->fields["jugador"] == $jugador) {
                    $this->logger->debug("Casilleros disponibles: jugador-> $jugador  Fila: $x -> Columna: $y" . json_encode($this->casilleros[$x][$y]));
                    //todo: verificar paridad
                    if ($x % 2 == 0) {
                        if ((($x - 1) > -1) && (($y + 1) < count($this->casilleros[$x]))) {
                            if ($this->casilleros[$x - 1][$y + 1]->isVacio()) {
                                $res .= $this->casilleros[$x - 1][$y + 1]->toJson();
                                $this->logger->debug("Casilleros disponibles: jugador-> $jugador  Fila: $x -> Columna: $y" . json_encode($this->casilleros[$x][$y]));
                            }
                        }
                        if ((($x + 1) < count($this->casilleros)) && (($y - 1) > -1)) {
                            if ($this->casilleros[$x + 1][$y - 1]->isVacio()) {
                                $res .= $this->casilleros[$x + 1][$y - 1]->toJson();
                                $this->logger->debug("Casilleros disponibles: jugador-> $jugador  Fila: $x -> Columna: $y" . json_encode($this->casilleros[$x][$y]));
                            }
                        }
                    } else {
                        if ((($y - 1) > -1) && (($x + 1) < count($this->casilleros))) {
                            if ($this->casilleros[$x + 1][$y - 1]->isVacio()) {
                                $res .= $this->casilleros[$x + 1][$y - 1]->toJson();
                                $this->logger->debug("Casilleros disponibles: jugador-> $jugador  Fila: $x -> Columna: $y" . json_encode($this->casilleros[$x][$y]));
                            }
                        }
                        if ((($y + 1) < count($this->casilleros[$x])) && (($x - 1) > -1)) {
                            if ($this->casilleros[$x - 1][$y + 1]->isVacio()) {
                                $res .= $this->casilleros[$x - 1][$y + 1]->toJson();
                                $this->logger->debug("Casilleros disponibles: jugador-> $jugador  Fila: $x -> Columna: $y" . json_encode($this->casilleros[$x][$y]));
                            }
                        }
                    }
                    if (($x - 1) > -1) {
                        if ($this->casilleros[$x - 1][$y]->isVacio()) {
                            $res .= $this->casilleros[$x - 1][$y]->toJson();
                            $this->logger->debug("Casilleros disponibles: jugador-> $jugador  Fila: $x -> Columna: $y" . json_encode($this->casilleros[$x][$y]));
                        }
                    }
                    if (($x + 1) < count($this->casilleros)) {
                        if ($this->casilleros[$x + 1][$y]->isVacio()) {
                            $res .= $this->casilleros[$x + 1][$y]->toJson();
                            $this->logger->debug("Casilleros disponibles: jugador-> $jugador  Fila: $x -> Columna: $y" . json_encode($this->casilleros[$x][$y]));
                        }
                    }
                    if (($y - 1) > -1) {
                        if ($this->casilleros[$x][$y - 1]->isVacio()) {
                            $res .= $this->casilleros[$x][$y - 1]->toJson();
                            $this->logger->debug("Casilleros disponibles: jugador-> $jugador  Fila: $x -> Columna: $y" . json_encode($this->casilleros[$x][$y]));
                        }
                    }
                    if (($y + 1) < count($this->casilleros[$x])) {
                        if ($this->casilleros[$x][$y + 1]->isVacio()) {
                            $res .= $this->casilleros[$x][$y + 1]->toJson();
                            $this->logger->debug("Casilleros disponibles: jugador-> $jugador  Fila: $x -> Columna: $y" . json_encode($this->casilleros[$x][$y]));
                        }
                    }
                }
            }
        }
        if (strlen($res) > 1) {
            $res = substr($res, 0, strlen($res) - 1); # sin la ultima coma
        }
        $res .= "]";
        return $res;
    }
}

?>