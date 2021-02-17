<?php

namespace Src\App\Models;

use Src\Core\Model;

class TipoCasillero extends Model {

    public $table = 'tipo_casillero';

    public function __construct($dbHandler = null, $parameters = null)
    {
        Parent::__construct($dbHandler);
        if (is_null($parameters)) {
            $this->dbHandler->addField("id");
            $this->dbHandler->addField("descripcion");
        } else {
            $this->setParameters($parameters);
        }
        $this->setTableName("tipo_casillero");
    }
}