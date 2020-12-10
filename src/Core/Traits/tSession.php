<?php

namespace Src\Core\Traits;

trait tSession {

    public $session;

    public  function  setSession($session){
        $this->session = $session;
    }

}