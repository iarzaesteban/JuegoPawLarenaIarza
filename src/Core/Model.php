<?php


namespace Src\Core;

use Src\Core\Database\QueryBuilder;
use Src\Core\Database\DBHanlder;
use Src\Core\Database\MySQLHanlder;
use Src\Core\Traits\loggable;
use Src\Core\Traits\connectable;
use PDO;

class Model{
    use loggable;
    use connectable;
    
    private $id;
    private $dbHandler;

    public function __construct($dbHandler = null) {
        if (is_null($dbHandler)){
            $this->dbHandler = new MySQLHandler();
            $this->dbHandler->setLogger($this->logger);
            $this->dbHandler->setConnection($this->connection);
        }
    }

    public function setQueryBuilder(QueryBuilder  $qb){
        $this->queryBuilder = $qb;

    }

    public function setDBHandler(DBHanlder  $dbHandler){
        $this->dbHandler = $dbHandler;

    }

    public function save() {
        return $this->queryBuilder->save();
    }

    public function exists () {
        return $this->queryBuilder->exists();
    }

    public function hasValue($field, $value) {
        return $this->queryBuilder->hasValue($field, $value);
    }

    public function queryByField($field, $value) {
        return $this->queryBuilder->queryByField($field, $value);
    }

    public function load() {
        return $this->queryBuilder->load();
    }

    public function findByFields($params) {
        return $this->queryBuilder->findByFields($params);
    }

    public function loadByFields($params) {
        return $this->queryBuilder->loadByFields($params);
    }

    public function update() {
        return $this->queryBuilder->update();
    }
}