<?php


namespace Src\Core\Database;

use Src\Core\Database\QueryBuilder;
use Src\Core\Database\DBHandler;
use PDO;

class MySQLHandler extends DBHandler {

    public $table;
    public $fields = [

    ];
    private $id;

    public function setFields($fields = []) {
        $this->fields = $fields;
    }

    public function getField($field) {
        if (array_key_exists($field, $this->fields)) {
            return $this->fields["$field"];
        }
        return null;
    }

    public function setField($field, $value) {
        if (array_key_exists($field, $this->fields)) {
            $this->fields["$field"] = $value;
            return true;
        }
        return false;
    }

    public function getTable() {
        return $this->table;
    }

    public function save() {
        $this->logger->debug("Guardando en $this->table -> " . json_encode($this->fields));
        if (! $this->exists()) {
            $this->logger->debug("Insert");
            $campos = "(";
            $valores = "(";
            foreach ($this->fields as $clave => $valor){
                $campos .= $clave . ",";
                $valores .= ":" . $clave . ",";
                if (is_null($valor)) {
                    $this->fields[$clave] = 0;
                }
            }
            $campos  = substr($campos,0,strlen($campos) -1); # sin la ultima coma
            $valores  = substr($valores,0,strlen($valores) -1); # sin la ultima coma
            $insert = "INSERT INTO " . $this->table . " " . $campos . ") VALUES " . $valores . ")";
            $sentencia = $this->connection->prepare($insert);
            foreach ($this->fields as $clave => $valor){
                $this->logger->debug("bind value -> : $clave     valor -> $valor");
                $sentencia->bindValue(":" . $clave, $valor);
            }
            $this->logger->debug("query: $insert");
            $sentencia->setFetchMode(PDO::FETCH_ASSOC);
            $sentencia->execute();
            return true;
        } else {
            $this->logger->debug("Update");
            $update = "UPDATE " . $this->table . " SET ";
            foreach ($fields as $clave => $valor){
                $update .= $clave . "= :" . $clave;
            }
            $update .= " WHERE ID=:ID";
            $sentencia = $this->connection->prepare($update);
            foreach ($fields as $clave => $valor){
                $sentencia->bindValue(":" + $clave, $valor);
            }
            $sentencia->bindValue(":id", $this->id);
            $this->logger->debug("query: $update");
            $sentencia->setFetchMode(PDO::FETCH_ASSOC);
            $sentencia->execute();
            return true;
        }
    }

    public function exists () {
        return false;
    }

    public function hasValue($field, $value) {
        $this->logger->debug("Models->hasValue($field, $value)");
        $query = "SELECT * FROM $this->table WHERE $field = :field";
        $sentencia = $this->connection->prepare($query);
        $sentencia->bindValue(":field", $value);
        $sentencia->setFetchMode(PDO::FETCH_ASSOC);
        $sentencia->execute();
        return count($sentencia->fetchAll()) <> 0;
    }

    public function queryByField($field, $value) {
        $this->logger->debug("Models->queryByField($field, $value)");
        $query = "SELECT * FROM $this->table WHERE $field = :field";
        $sentencia = $this->connection->prepare($query);
        $sentencia->bindValue(":field", $value);
        $sentencia->setFetchMode(PDO::FETCH_ASSOC);
        $sentencia->execute();
        return $sentencia->fetchAll();
    }

    public function load() {
        $this->logger->debug("Models->load()");
        $query = "SELECT * FROM $this->table WHERE id = :id";
        $sentencia = $this->connection->prepare($query);
        $sentencia->bindValue(":id", $this->id);
        $sentencia->setFetchMode(PDO::FETCH_ASSOC);
        $sentencia->execute();
        $res = $sentencia->fetchAll();
        if (count($res) == 1) {
            foreach ($res[0] as $clave => $valor){
                if (array_key_exists($clave, $this->fields)) {
                    $this->fields[$clave] = $valor;
                }
            }
            return true;
        }
        return false;
    }

    public function findByFields($params) {
        $query = "SELECT * FROM $this->table WHERE ";
        foreach ($params as $clave => $valor){
            $query .= "$clave = :$clave AND ";
        }
        $query  = substr($query,0,strlen($query) - 4); # sin la ultima coma
        $sentencia = $this->connection->prepare($query);
        foreach ($params as $clave => $valor){
            $this->logger->debug("bind value -> : $clave     valor -> $valor");
            $sentencia->bindValue(":" . $clave, $valor);
        }
        $this->logger->debug("query: $query");
        $sentencia->setFetchMode(PDO::FETCH_ASSOC);
        $sentencia->execute();
        return $sentencia->fetchAll();
    }

    public function loadByFields($params) {
        $res = $this->findByFields($params);
        //$this->logger->debug(json_encode($res));
        if (count($res) == 1) {
            foreach ($res[0] as $clave => $valor){
                if (array_key_exists($clave, $this->fields)){
                    $this->fields[$clave] = $valor;
                }
            }
            return true;
        }
        return false;
    }

    public function update() {
        $this->logger->debug("models->update()" . json_encode($this->fields));
        $update = "UPDATE " . $this->table . " SET ";
        foreach ($this->fields as $clave => $valor){
            if ($clave != "id") {
                $update .= $clave . "= :" . $clave . ",";
            }
        }
        $update  = substr($update,0,strlen($update) - 1); # sin la ultima coma
        $update .= " WHERE id=:id";
        $this->logger->debug("update: $update");
        $sentencia = $this->connection->prepare($update);
        foreach ($this->fields as $clave => $valor){
            if ($clave != "id") {
                $this->logger->debug("bind value -> : $clave     valor -> $valor");
                $sentencia->bindValue(":$clave", $valor);
            }
        }
        if (!array_key_exists("id", $this->fields)) {
            $sentencia->bindValue(":id", $this->id);
        } else {
            $sentencia->bindValue(":id", $this->fields["id"]);
        }
        $this->logger->debug("query: $update");
        $sentencia->setFetchMode(PDO::FETCH_ASSOC);
        $sentencia->execute();
        return true;
    }
}