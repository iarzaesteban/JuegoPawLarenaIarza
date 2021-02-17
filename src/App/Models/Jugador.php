<?php

namespace Src\App\Models;

use Src\Core\Model;
use Exception;

use Src\Core\Exceptions\invalidValueFormatException;


class Jugador extends Model
{

    public function __construct($dbHandler = null, $parametros = null)
    {
        Parent::__construct($dbHandler);
        if (is_null($parametros)) {
            $this->addField('id');
            $this->addField('nombre');
            $this->addField('juego');
            $this->addField('puntuacion');
            $this->addField('estado');
        } else {
            $this->setParameters($parametros);
        }
        $this->dbHandler->table = 'jugador';
    }

    public function inicializarJugador($nombre = "", $juego = "")
    {
        $this->nombre = $nombre;
        $this->dbHandler->fields["nombre"] = $nombre;
        $this->dbHandler->fields["juego"] = $juego;
    }

    public $nombre;
    public $enfermedad;
    public $cartas = array();
    public $casillerosOcupados = array();

    public function setCarta(Carta $carta)
    {
        $this->cartas[] = $carta;
    }

    public function getCarta($carta)
    {
        return $this->cartas[$carta];
    }

    public function getCartas()
    {
        return $this->cartas;
    }

    public function setEnfermedad($e)
    {
        $this->enfermedad = $e;
    }

    public function getEnfermedad()
    {
        return $this->enfermedad;
    }

    public function getCasillerosOcupados()
    {
        return $this->casillerosOcupados;
    }

    public function tirarCarta(Carta $carta)
    {
        if (in_array($carta, $this->cartas)) {
            $carta->invocar();
        }
    }

    public function setCasillerosOcupados($posciones)
    {
        $c = 1;
        for ($contador = 0; $contador < count($posciones); $contador + 2) {
            $this->casillerosOcupados = new Casilleros_ocupados($posciones[$contador], $posciones[$c]);
            $c = $c + 2;
        }

    }

    public function load($find = null) : bool
    {
        $user = $this->queryByField("nombre", $this->get("nombre"));
        if (count($user) == 1) {
            foreach ($user[0] as $clave => $valor) {
                if (array_key_exists($clave, $this->dbHandler->fields)) {
                    $this->dbHandler->fields[$clave] = $valor;
                }
            }
            return true;
        }
        return false;
    }

    public function save() : bool
    {
        $user = $this->findByFields([
            "nombre" => $this->get("nombre"),
            "estado" => $this->get("estado"),
            "juego" => $this->get("juego")
        ]);
        if (count($user) == 0) {
            $this->logger->debug("guardando jugador");
            return parent::save();
        }
        return false;
    }

    public function findByJuego($juego)
    {
        return $this->findByFields([
            "juego" => $juego->getNombre(),
            "estado" => $juego->getEstado()
        ]);
    }
}

?>
