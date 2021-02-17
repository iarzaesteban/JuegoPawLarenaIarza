<?php

namespace Src\App\Models;

use Src\Core\Model;
use Exception;

use Src\Core\Exceptions\invalidValueFormatException;


class Agente extends Model
{

    public function __construct($dbHandler = null, $parametros = null)
    {
        Parent::__construct($dbHandler);
        if (is_null($parametros))
        {
            $this->dbHandler->addField("id");
            $this->dbHandler->addField("nombre");
            $this->dbHandler->addField("descripcion");
        } else {
            if (array_key_exists("nombre", $parametros)) {
                $this->set("nombre", $parametros["nombre"]);
                $this->load();
            } else {
                $this->setParameters($parametros);
            }
        }
        $this->setTableName("agente");
    }

    public string $nombreAgente;

    public function setAgente(string $nombreAgente)
    {
        $this->nombreAgente = $nombreAgente;
    }

    public function getAgente(): string
    {
        return $this->nombreAgente;
    }

    public function load($find = null): bool
    {
        return $this->dbHandler->load(["nombre"]);
    }

    public function delete($find = null)
    {
        $this->dbHandler->delete(["nombre"]);
    }

    public function update($find = null, $updateFields = null): bool
    {
        return $this->dbHandler->update(["nombre", $updateFields]);
    }

}