<?php

namespace Src\Core\Traits;

trait connectable {

    public $connection;

    public  function  setConnection($connection){
        $this->connection = $connection;
    }

}