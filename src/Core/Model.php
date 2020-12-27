<?php


namespace Src\Core;

use Src\Core\Database\MySQLHandler;
use Src\Core\Traits\loggable;
use Src\Core\Traits\connectable;
use PDO;

class Model {
    
    use loggable {
        setLogger as protected traitSetLogger;
    }

    use connectable {
        setConnection as protected traitSetConnection;
    }
    
    private $id;
    public $dbHandler;

    public function __construct($dbHandler = null) {
        if (is_null($dbHandler)){
            $this->dbHandler = new MySQLHandler();
        }
    }

    public function setDBHandler(dbHandler  $dbHandler){
        $this->dbHandler = $dbHandler;

    }

    public function save() {
        return $this->dbHandler->save();
    }

    public function exists () {
        return $this->dbHandler->exists();
    }

    public function hasValue($field, $value) {
        return $this->dbHandler->hasValue($field, $value);
    }

    public function queryByField($field, $value) {
        return $this->dbHandler->queryByField($field, $value);
    }

    public function load() {
        return $this->dbHandler->load();
    }

    public function findByFields($params) {
        return $this->dbHandler->findByFields($params);
    }

    public function loadByFields($params) {
        return $this->dbHandler->loadByFields($params);
    }

    public function update() {
        return $this->dbHandler->update();
    }

    public function setLogger($logger) {
        $this->traitSetLogger($logger);
        $this->logger = $logger;
        if (!is_null($this->dbHandler)) {
            $this->dbHandler->setLogger($logger);
        }
    }

    public function setConnection($connection) {
        $this->traitSetConnection($connection);
        if (!is_null($this->dbHandler)) {
            $this->dbHandler->setConnection($connection);
        }
    }

    public static $staticLogger;
    public static $staticConnection;

    public static function init($logger, $connection) {
        Model::$staticLogger = $logger;
        Model::$staticConnection = $connection;
    }

    public static function factory($nombre, $dbHandler = null) {
        $path = "Src\\App\\Models\\{$nombre}";
        $obj = new $path($dbHandler);
        $obj->setLogger(Model::$staticLogger);
        $obj->setConnection(Model::$staticConnection);
        return $obj;
    }
}