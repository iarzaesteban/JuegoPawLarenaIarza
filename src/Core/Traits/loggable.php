<?php

namespace Src\Core\Traits;

use Monolog\Logger;

trait loggable {

    public $logger;

    public  function  setLogger(Logger $logger){
        $this->logger = $logger;
    }

}
