<?php

namespace Src\App\Models;

use Src\Core\Model;
use Exception;

use Src\Core\Exceptions\invalidValueFormatException;


class Jugador extends Model {

    public function __construct($class, $dbHandler = null) {
        Parent::__construct($class, $dbHandler);
        $this->dbHandler->setFields([
            'nombre' => null,
            'juego'  => null,
            "puntuacion" => null,
            "estado" => null
        ]); 
        $this->dbHandler->table = 'jugador';
    }  

    public function inicializarJugador($nombre = "", $juego = "") {
        $this->nombre = $nombre;
        $this->dbHandler->fields["nombre"] = $nombre;
        $this->dbHandler->fields["juego"] = $juego;
    }

    public $nombre ;
    public $id;
    public $enfermedad;
    public $mail;
    public $cartas = array();
    public $casillerosOcupados = array();

    public function setNombre($nombre) { $this->dbHandler->fields["nombre"] = $nombre; }

    public function getNombre() { return $this->dbHandler->getField("nombre");}

    public function setPuntuacion($puntuacion) { $this->dbHandler->fields["puntuacion"] = $puntuacion; }

    public function getPuntuacion() { return $this->dbHandler->getField("puntuacion");}

    public function setJuego($juego) { $this->dbHandler->fields["juego"] = $juego; }

    public function getJuego() { return $this->dbHandler->getField("juego");}

    public function setEstado($estado) { $this->dbHandler->fields["estado"] = $estado; }

    public function getEstado() { return $this->dbHandler->getField("estado");}

    public function setID($id){ $this->id = $id; }

    public function getID(){ return $this->id ; }

    public function setCarta(Carta $carta){
        $this->cartas[] = $carta;
    }

    public function getCarta($carta){
        return $this->cartas[$carta];
    }

    public function getCartas(){
        return $this->cartas;
    }

    public function setEnfermedad($e){
        $this->enfermedad = $e;
    }

    public function getEnfermedad(){
        return $this->enfermedad;
    }

   public function getCasillerosOcupados(){
       return $this->casillerosOcupados;
   }

   public function tirarCarta(Carta $carta){
    if(in_array($carta ,$this->cartas) ){
        $carta->invocar();
    } 
   }

    public function setCasillerosOcupados($posciones){
        $c = 1;
        for($contador = 0; $contador < count($posciones); $contador+2){
            $this->casillerosOcupados = new Casilleros_ocupados($posciones[$contador],$posciones[$c]);
            $c = $c +2;
        }

    }
    
    public function load(){
        $user = $this->queryByField("nombre", $this->dbHandler->fields["nombre"]);
        if (count($user) == 1) {
            foreach ($user[0] as $clave => $valor){
                if (array_key_exists($clave, $this->dbHandler->fields)) {
                    $this->dbHandler->fields[$clave] = $valor;
                }
            }
            return true;
        }
        return false;
    }
    
    public function save(){
        $user = $this->findByFields([
            "nombre" => $this->dbHandler->fields["nombre"],
            "estado" => $this->dbHandler->fields["estado"],
            "juego" => $this->dbHandler->fields["juego"]
        ]);
        if (count($user) == 0) {
            $this->logger->debug("guardando jugador");
            return parent::save();
        }
        return false;
    }

    public function findByJuego($juego) {
        return $this->findByFields([
            "juego" => $juego->getNombre(),
            "estado" => $juego->getEstado()
        ]);
    }
}

?>
