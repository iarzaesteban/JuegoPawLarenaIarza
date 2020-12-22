<?php

namespace Src\App\Models;

use Src\Core\Model;
use Exception;
use PDO;

use Src\Core\Exceptions\invalidValueFormatException;


class Usuario extends Model {

    public function __construct($nombre, $password, $mail = null){
        $this->dbHanlder->fields = [
            'nombre' => null,
            'password' => null,
            'mail' => null
        ]; 
        $this->nombre = $nombre;
        $this->mail = $mail;
        $this->dbHanlder->fields["nombre"] = $nombre;
        $this->dbHanlder->fields["password"] = $password;
        $this->dbHanlder->fields["mail"] = $mail;
        $this->dbHanlder->table = 'usuario';
    }  

    public $nombre ;
    public $mail;

    public function autenticar() {
        $query = "SELECT * FROM $this->dbHanlder->table WHERE nombre=:nombre AND password=:password";
        $sentencia = $this->connection->prepare($query);
        $sentencia->bindValue(":nombre", $this->dbHanlder->fields["nombre"]);
        $sentencia->bindValue(":password", $this->dbHanlder->fields["password"]);
        $sentencia->setFetchMode(PDO::FETCH_ASSOC);
        $sentencia->execute();
        return count($sentencia->fetchAll()) <> 0;
    }

    public function save() {
        $this->logger->debug("Jugador->save()");
        if ($this->hasValue("mail", $this->dbHanlder->fields["mail"])){
            return false;
        }
        return parent::save();
    }     
    
    public function load(){
        $user = $this->queryByField("nombre", $this->dbHanlder->fields["nombre"]);
        if (count($user) == 1) {
            foreach ($user[0] as $clave => $valor){
                if (array_key_exists($clave, $this->dbHanlder->fields)) {
                    $this->dbHanlder->fields[$clave] = $valor;
                }
            }
            return true;
        }
        return false;
    }
}

?>
