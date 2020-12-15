<?php

namespace Src\App\Models;

use Src\Core\Model;
use Exception;

use Src\Core\Exceptions\invalidValueFormatException;


class Jugador extends Model {

    public function __construct($nombre, $mail){
        $this->fields = [
            'id'    => null,
            'carta'  => null,
            'casillero'  => null,
            'nombre' => null,
            'password' => null,
            'mail' => null
        ]; 
        $this->nombre = $nombre;
        $this->mail = $mail;
        $this->fields["nombre"] = $nombre;
        $this->fields["mail"] = $mail;
        $this->table = 'jugador';
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

    public function save() {
        $this->logger->debug("Jugador->save()");
        if ($this->hasValue("mail", $this->fields["mail"])){
            return false;
        }
        return parent::save();
    }    
}

?>
