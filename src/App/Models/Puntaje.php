<?php

namespace Src\App\Models;

class Puntaje  {

    public $table = 'puntaje';

    public $nombreJugador;
    public $casillerosOcupados;
    public $tiempo;

    public function __construct($nj,$co,$t){
        $this->nombreJugador = $nj;
        $this->casillerosOcupados = $co;
        $this->tiempo = $t;

    }

    public function getNombreJugador(){
        return $this->nombreJugador;
    }

    public function getCasillerosOcupados(){
        return $this->casillerosOcupados;
    }

    public function getTiempo(){
        return $this->tiempo;
    }
    
}

?>