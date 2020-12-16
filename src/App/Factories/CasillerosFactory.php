<?php

namespace Src\App\Factories;

use Src\App\Models\CasilleroNormal;
use Src\App\Models\CasilleroBosque;


class CasillerosFactory {

    static function getRandom($filas, $columnas) {
        $res = array();        $i = 0;
        $k = 0;
        for ($i = 0 ; $i <$filas ; $i++) {
            $fila = array();
            for ($i = 0 ; $i <$filas ; $i++) {
                $fila[] = CasillerosFactory::getCasilleroRandom();
            }
            $res[] = $fila;
        }
        return $res;
    }

    static function getCasilleroRandom() {
        $res = null;
        $random = rand(1,100);
        if ($random < 90) {
            $res = new CasilleroNormal();
        } else {
            $res = new CasilleroNormal();
        }
        return $res;
    }
}