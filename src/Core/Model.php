<?php


namespace Src\Core;

use Src\Core\Database\QueryBuilder;
use Src\Core\Traits\loggable;
use Src\Core\Traits\connectable;
use PDO;

class Model{
    use loggable;
    use connectable;

    public $table;
    public $fields = [

    ];
    private $id;
    public $queryBuilder;

    public function setQueryBuilder(QueryBuilder  $qb){
        $this->queryBuilder = $qb;

    }

    public function save() {
        $this->logger->debug("Guardando en $this->table -> " . implode($this->fields));
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
}