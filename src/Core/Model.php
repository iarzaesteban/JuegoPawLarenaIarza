<?php


namespace Src\Core;

use Src\Core\Database\MySQLHandler;
use Src\Core\Traits\loggable;
use Src\Core\Traits\connectable;

class Model
{

    use loggable {
        setLogger as protected traitSetLogger;
    }

    use connectable {
        setConnection as protected traitSetConnection;
    }

    protected int $id;
    protected $dbHandler;

    public function __construct($dbHandler = null)
    {
        if (is_null($dbHandler)) {
            $this->dbHandler = new MySQLHandler();
        }
    }

    public function setDBHandler(dbHandler $dbHandler)
    {
        $this->dbHandler = $dbHandler;

    }

    public function save(): bool
    {
        return $this->dbHandler->save();
    }

    public function exists(): bool
    {
        return $this->dbHandler->exists();
    }

    public function hasValue($field, $value): int
    {
        return $this->dbHandler->hasValue($field, $value);
    }

    public function queryByField($field, $value)
    {
        return $this->dbHandler->queryByField($field, $value);
    }

    public function load($find = null): bool
    {
        return $this->dbHandler->load($find);
    }

    public function findByFields($params)
    {
        return $this->dbHandler->findByFields($params);
    }

    public function loadByFields($params): bool
    {
        return $this->dbHandler->loadByFields($params);
    }

    public function update($find = null, $updateFields = null): bool
    {
        return $this->dbHandler->update($find, $updateFields);
    }

    public function delete($find = null)
    {
        $this->dbHandler->delete($find);
    }

    public function setLogger($logger)
    {
        $this->traitSetLogger($logger);
        $this->logger = $logger;
        if (!is_null($this->dbHandler)) {
            $this->dbHandler->setLogger($logger);
        }
    }

    public function setConnection($connection)
    {
        $this->traitSetConnection($connection);
        if (!is_null($this->dbHandler)) {
            $this->dbHandler->setConnection($connection);
        }
    }

    public static $staticLogger;
    public static $staticConnection;

    public static function init($logger, $connection)
    {
        Model::$staticLogger = $logger;
        Model::$staticConnection = $connection;
    }

    public static function factory($nombre, $dbHandler = null)
    {
        $path = "Src\\App\\Models\\{$nombre}";
        $obj = new $path($dbHandler);
        $obj->setLogger(Model::$staticLogger);
        $obj->setConnection(Model::$staticConnection);
        return $obj;
    }
}