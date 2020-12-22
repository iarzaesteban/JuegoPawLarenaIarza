<?php

namespace Src\App\Models;

use Src\Core\Model;
use Exception;

use Src\Core\Exceptions\invalidValueFormatException;


class Jugador extends Model {

    public function __construct($nombre = "", $juego = ""){
        $this->dbHanlder->fields = [
            'nombre' => null,
            'juego'  => null,
            "puntuacion" => null,
            "estado" => null
        ]; 
        $this->nombre = $nombre;
        $this->dbHanlder->fields["nombre"] = $nombre;
        $this->dbHanlder->fields["juego"] = $juego;
        $this->dbHanlder->table = 'jugador';
    }  

    public $nombre ;
    public $id;
    public $enfermedad;
    public $mail;
    public $cartas = array();
    public $casillerosOcupados = array();

    // public $fields = [
    //     'id'    => null,
    //     'carta'  => null,
    //     'casillero'  => null,
    //     'nombre' => null,
    //     'password' => null,
    //     'mail' => null
    // ];

    public function setNombre($nombre) {
        $this->dbHanlder->fields["nombre"] = $nombre;
    }

    public function setPuntuacion($puntuacion) {
        $this->dbHanlder->fields["puntuacion"] = $puntuacion;
    }

    public function setJuego($juego) {
        $this->dbHanlder->fields["juego"] = $juego;
    }

    public function setEstado($estado) {
        $this->dbHanlder->fields["estado"] = $estado;
    }

    public function setID($id){
        $this->id = $id;
    }

    public function getID(){
        return $this->id ;
    }

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
        $user = $this->queryByField("nombre", $this->dbHanlder->fields["nombre"]);
        if (count($user) == 1) {
            foreach ($user[0] as $clave => $valor){
                if (array_key_exists($clave, $this->dbHanlder->fields)) {
                    $this->dbHanlder->fields[$clave] = $valor;
                }
            }
            return true;
        }
        return false;
    }
    
    public function save(){
        $user = $this->findByFields([
            "nombre" => $this->dbHanlder->fields["nombre"],
            "estado" => $this->dbHanlder->fields["estado"],
            "juego" => $this->dbHanlder->fields["juego"]
        ]);
        if (count($user) == 0) {
            $this->logger->debug("guardando jugador");
            return parent::save();
        }
        return false;
    }

    public function findByJuego($juego) {
        return $this->findByFields([
            "juego" => $juego->fields["nombre"],
            "estado" => $juego->fields["estado"]
        ]);
    }
}

?>
