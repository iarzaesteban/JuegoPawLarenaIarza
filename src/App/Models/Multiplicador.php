<?php

namespace Src\App\Models;

use Src\Core\Model;


class Multiplicador extends Model
{

    public $turnoDeVida;
    public $isVigente;

    public function __construct($dbHandler = null, $parametros = null)
    {
        Parent::__construct($dbHandler);
        if (is_null($parametros)) {
            $this->addField('id');
            $this->addField('multiplicador');
            $this->addField('turnosDeVida');
        } else {
            $this->setParameters($parametros);
        }
        $this->dbHandler->table = 'multiplicador';
    }

    public function setMultiplicador($m)
    {
        $this->multiplicador = $m;
    }

    public function setTurnoDeVida($tv)
    {
        $this->turnoDeVida = $tv;
    }

    public function setIsVigente($v)
    {
        $this->isVigente = $v;
    }


    public function getTurnoDeVida()
    {
        return $this->turnoDeVida;
    }

    public function getIsVigente()
    {
        return $this->isVigente;
    }


}

?>