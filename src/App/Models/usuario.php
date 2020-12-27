<?php

namespace Src\App\Models;

use Src\Core\Model;
use Exception;
use PDO;

use Src\Core\Exceptions\invalidValueFormatException;


class Usuario extends Model {

    public function __construct($class, $dbHandler = null){
        parent::__construct($class, $dbHandler);
        $this->dbHandler->setFields([
            'nombre' => null,
            'password' => null,
            'mail' => null
        ]); 
        $this->dbHandler->table = 'usuario';
    }  
    
    public function inicializarUsuario($nombre, $password, $mail = null) {
        $this->dbHandler->fields["nombre"] = $nombre;
        $this->dbHandler->fields["password"] = $password;
        $this->dbHandler->fields["mail"] = $mail;
    }

    public function autenticar() {
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

    public function save() {
        $this->logger->debug("Jugador->save()");
        if ($this->hasValue("mail", $this->dbHandler->fields["mail"])){
            return false;
        }
        return parent::save();
    }     
    
    public function load(){
        $user = $this->queryByField("nombre", $this->dbHandler->fields["nombre"]);
        if (count($user) == 1) {
            foreach ($user[0] as $clave => $valor){
                if (array_key_exists($clave, $this->dbHandler->fields)) {
                    $this->dbHandler->fields[$clave] = $valor;
                }
            }
            return true;
        }
        return false;
    }
}

?>
