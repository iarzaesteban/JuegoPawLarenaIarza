<?php


namespace Src\Core\Database;

use Src\Core\Database\QueryBuilder;
use Src\Core\Traits\loggable;
use Src\Core\Traits\connectable;
use PDO;

class DBHandler {
    use loggable;
    use connectable;

    public function save() {
        $this->logger->error("DBHandler->save() no implementado");
    }

    public function exists () {
        $this->logger->error("DBHandler->exists() no implementado");
    }

    public function hasValue($field, $value) {
        $this->logger->error("DBHandler->hasValue($field, $value) no implementado");
    }

    public function queryByField($field, $value) {
        $this->logger->error("DBHandler->hasValue($field, $value) no implementado");
    }

    public function load() {
        $this->logger->error("DBHandler->load() no implementado");
    }

    public function findByFields($params) {
        $this->logger->error("DBHandler->findByFields($params) no implementado");
    }

    public function loadByFields($params) {
        $this->logger->error("DBHandler->loadByFields($params) no implementado");
    }

    public function update() {
        $this->logger->error("DBHandler->update() no implementado");
    }
}