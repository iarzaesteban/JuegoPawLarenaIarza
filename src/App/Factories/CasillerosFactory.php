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
            for ($k = 0 ; $k <$filas ; $k++) {
                $fila[] = CasillerosFactory::getCasilleroRandom($i ,$k);
            }
            $res[] = $fila;
        }
        return $res;
    }

    static function getCasilleroRandom($i ,$k) {
        $res = null;
        $random = rand(1,100);
        if ($random < 90) {
            $res = new CasilleroNormal($i ,$k);
        } else {
            $res = new CasilleroNormal($i ,$k);
        }
        return $res;
    }
}