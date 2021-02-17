<?php

namespace Src\App\Models;

use Src\Core\Model;
use Exception;

use Src\Core\Exceptions\invalidValueFormatException;


class Carta extends Model
{


    public function __construct($dbHandler = null, $parametros = null)
    {
        Parent::__construct($dbHandler);
        if (is_null($parametros))
        {
            $this->dbHandler->addField("id");
            $this->dbHandler->addField("comodin");
            $this->dbHandler->addField("intensidad");
            $this->dbHandler->addField("jugadorId");
            $this->setTableName("carta");
        } else {
            $this->setParameters($parametros);
        }
    }

    private $queryBuilder;
    public $descripcionCarta;


    public function setDescripcionCarta($cd)
    {
        $this->descripcionCarta = $cd;
    }

    public function getDescripcionCarta()
    {
        return $this->descripcionCarta;
    }

    public function setQueryBuilder(QueryBuilder $qb)
    {
        $this->queryBuilder = $qb;
    }


    public function invocar()
    {
        return $this->descripcionCarta;
    }

    public function getCartasDelJugador()
    {
        return $this->findByFields(["jugadorId" => $this->get("jugadorId")]);
    }

}
