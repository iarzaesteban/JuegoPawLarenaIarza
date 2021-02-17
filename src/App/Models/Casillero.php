<?php

namespace Src\App\Models;

use Src\Core\Model;
use Exception;

use Ssrc\Core\Exceptions\invalidValueFormatException;
use Src\App\Models\jugador;
use Src\App\Models\TipoCasillero;


class Casillero extends Model
{


    public function __construct($dbHandler = null, $parametros = null)
    {
        Parent::__construct($dbHandler);
        if (is_null($parametros)) {
            $this->addField("id");
            $this->addField("posicionX");
            $this->addField("posicionY");
            $this->addField("tablero");
            $this->addField("jugador");
            $this->setTableName('casillero');
        } else {
            $this->setParameters($parametros);
        }
    }

    //todo: quitar
    public function incializarCasillero($x = 0, $y = 0)
    {
        $this->x = $x;
        $this->y = $y;
        $this->dbHandler->setField("posicionX", $x);
        $this->dbHandler->setField("posicionY", $y);
    }

    public $descripcionCasillero;
    public $jugador;

    private $x;
    private $y;

    public function setJugador($jugador)
    {
        $this->jugador = $jugador;
        $this->dbHandler->fields["jugador"] = $jugador;
    }

    public function getDescipcionCasillero()
    {
        return $this->descipcionCasillero;
    }


    public function tirarCarta()
    {

    }

    public function ocupar($jugador)
    {
        $this->setJugador($jugador);
        $this->update();
    }

    public function findByTablero($tablero)
    {
        return $this->findByFields(["tablero" => $tablero->getId()]);
    }

    public function load($find = null) : bool
    {
        return $this->loadByFields([
            "tablero" => $this->get("tablero"),
            "posicionX" => $this->get("posicionX"),
            "posicionY" => $this->get("posicionY"),
        ]);
    }

    public function isVacio()
    {
        if (is_null($this->dbHandler->fields["jugador"])) {
            return true;
        }
        return $this->dbHandler->fields["jugador"] == "";
    }

    public function toJson()
    {
        return "Casillero:{\"posicionX\" : \"" . $this->dbHandler->fields["posicionX"] . "\", \"posicionY\" : \"" . $this->dbHandler->fields["posicionY"] . "\", \"jugador\" : \"" . $this->dbHandler->fields["jugador"] . "\" },";
    }
}
