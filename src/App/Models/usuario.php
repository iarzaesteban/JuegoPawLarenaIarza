<?php

namespace Src\App\Models;

use Src\Core\Model;
use Exception;
use PDO;

use Src\Core\Exceptions\invalidValueFormatException;


class Usuario extends Model {

    public function __construct($nombre, $password, $mail = null){
        $this->fields = [
            'nombre' => null,
            'password' => null,
            'mail' => null
        ]; 
        $this->nombre = $nombre;
        $this->mail = $mail;
        $this->fields["nombre"] = $nombre;
        $this->fields["password"] = $password;
        $this->fields["mail"] = $mail;
        $this->table = 'usuario';
    }  

    public $nombre ;
    public $mail;

    public function autenticar() {
        $query = "SELECT * FROM $this->table WHERE nombre=:nombre AND password=:password";
        $sentencia = $this->connection->prepare($query);
        $sentencia->bindValue(":nombre", $this->fields["nombre"]);
        $sentencia->bindValue(":password", $this->fields["password"]);
        $sentencia->setFetchMode(PDO::FETCH_ASSOC);
        $sentencia->execute();
        return count($sentencia->fetchAll()) <> 0;
    }

    public function save() {
        $this->logger->debug("Jugador->save()");
        if ($this->hasValue("mail", $this->fields["mail"])){
            return false;
        }
        return parent::save();
    }      
}

?>
