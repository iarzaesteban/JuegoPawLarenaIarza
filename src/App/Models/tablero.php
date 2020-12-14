<?php

namespace Src\App\Models;

use src\Core\Model;
use Exception;

use Src\Core\Exceptions\invalidValueFormatException;
use src\App\Models\casillero;
use src\App\Models\jugador;


class Tablero extends Model {

    public $table = 'tablero';
    private $queryBuilder;

    public $casilleros = array();
    public $filas;
    public $columnas;

    public function __construct($fil,$col,$descripcionesCasilleros){//$descripcionesCasilleros array de desc de casilleros en tablero
        $c = 0;
        $this->filas = $fil;
        $this->columnas = $col;
        for ($contador = 0; $contador < $fil; $contador++) {
            for ($contador1 = 0; $contador1 < $col; $contador1++) {
                $casillero = new Casillero($descripcionesCasilleros[$c]);
                $this->casilleros = $casillero;
                $c++;
           }
       }
    }

    public function getCasillerosOcupados(){
        return $this->casilleros;
    }

    public function setCasilleros(Jugador $jugador,$fila,$columna){//fila y culumna array para setear los lugaros ocupados por el jugador
        $this->casilleros[$fila][$columna]->setJugador($jugador);
    }

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


    public function setQueryBuilder(QueryBuilder  $qb){
        $this->queryBuilder = $qb;

    }
    public $fields = [
        'id'    => null,
        'casillero'  => null
    ];

   
    
}

?>