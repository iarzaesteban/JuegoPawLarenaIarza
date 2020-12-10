<?php


namespace Src\Core;

class Session {

    public function get($key){
        return $_SESSION[$key] ?? null;
    }
}