<?php

namespace Src\App\Models;

use Src\Core\Model;
use Exception;

use Src\Core\Exceptions\invalidValueFormatException;


class Usuario extends Model {

    public function __construct($nombre, $password, $mail = null){
        $this->fields = [
            'nombre' => null,
            'password' => null,
            'mail' => null
        ]; 
        $this->nombre = $nombre;
        $this->mail = $mail;
        $this->fields["nombre"] = $nombre;
        $this->fields["password"] = $password;
        $this->fields["mail"] = $mail;
        $this->table = 'usuario';
    }  

    public $nombre ;
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

    public function serCarta(Carta $carta){
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
