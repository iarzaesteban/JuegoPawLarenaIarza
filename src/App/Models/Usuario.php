<?php

namespace Src\App\Models;

use Src\Core\Model;
use Exception;
use PDO;

use Src\Core\Exceptions\invalidValueFormatException;


class Usuario extends Model
{

    public function __construct($dbHandler = null, $parameters = null)
    {
        Parent::__construct($dbHandler);
        if (is_null($parameters)) {
            $this->dbHandler->addField("id");
            $this->dbHandler->addField("password");
            $this->dbHandler->addField("mail");
        } else {
            $this->setParameters($parameters);
        }
        $this->setTableName("usuario");
    }

    public function inicializarUsuario($nombre, $password, $mail = null)
    {
        $this->set("nombre", $nombre);
        $this->set("password", $password);
        $this->set("mail", $mail);
    }

    public function autenticar()
    {
        $query = "SELECT * FROM " . $this->dbHandler->getTable() . " WHERE nombre=:nombre AND password=:password";
        $this->logger->debug("Query: $query");
        $sentencia = $this->connection->prepare($query);
        $sentencia->bindValue(":nombre", $this->dbHandler->getField("nombre"));
        $sentencia->bindValue(":password", $this->dbHandler->getField("password"));
        $this->logger->debug("Query: " . $this->dbHandler->getField("nombre"));
        $this->logger->debug("Query: " . $this->dbHandler->getField("password"));
        $sentencia->setFetchMode(PDO::FETCH_ASSOC);
        $sentencia->execute();
        return count($sentencia->fetchAll()) <> 0;
    }

    public function save($find = null): bool
    {
        $this->logger->debug("Jugador->save()");
        if ($this->hasValue("mail", $this->get("mail"))) {
            return false;
        }
        return parent::save();
    }

    public function load($find = null) : bool
    {
        $user = $this->queryByField("nombre", ["nombre" => $this->get("nombre")]);
        if (count($user) == 1) {
            foreach ($user[0] as $clave => $valor) {
                if (array_key_exists($clave, $this->dbHandler->fields)) {
                    $this->dbHandler->fields[$clave] = $valor;
                }
            }
            return true;
        }
        return false;
    }
}