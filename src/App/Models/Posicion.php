<?php

namespace Src\App\Models;

use Src\Core\Model;

class Posicion extends Model
{

    public function __construct($dbHandler = null, $parametros = null)
    {
        Parent::__construct($dbHandler);
        if (is_null($parametros)) {
            $this->addField('id');
            $this->addField('posicionX');
            $this->addField('posicionY');
        } else {
            $this->setParameters($parametros);
        }
        $this->dbHandler->table = 'posicion';
    }

    public function load($find = null): bool
    {
        $res = $this->findByFields([
            "posicionX" => $this->get("posicionX"),
            "posicionY" => $this->get("posicionY")
        ]);
        if (count($res) >= 1) {
            $this->set("id", $res[0]["id"]);
        } else {
            if ($this->save()) {
                $this->loadByFields([
                    "posicionX" => $this->get("posicionX"),
                    "posicionY" => $this->get("posicionY")
                ]);
            }
        }
        return true;
    }

}

?>