<?php

namespace Src\App\Factories;

use Src\Core\Model;
use Exception;
use src\App\Models\Casillero;
use src\App\Models\CasilleroNormal;
use src\App\Models\CasilleroBosque;


class CasillerosFactory {

    static function getRandom($filas, $columnas) {
        $res = array();        $i = 0;
        $k = 0;
        for ($i = 0 ; $i <$filas ; $i++) {
            $fila = array();
            for ($i = 0 ; $i <$filas ; $i++) {
                $fila[] = CasilleroFactory::getCasilleroRandom();
            }
            $res[] = $fila;
        }
        return $res;
    }

    static function getCasilleroRandom() {
        $random = rand(1,100);
        if ($random < 90) {
            return new CasilleroNormal;
        }
        return new CasilleroBosque;
    }
}