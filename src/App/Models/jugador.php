<?php

namespace Src\App\Models;

use Src\Core\Model;
use Exception;

use Src\Core\Exceptions\invalidValueFormatException;


class Jugador extends Model {
    public static $algo = "algo";
    public $table = 'jugador';

    public $fields = [
        'id'    => null,
        'carta'  => null,
        'casillero'  => null,
        'nombre' => null,
        'password' => null,
        'mail' => null
    ];

    public function __construct($nombre, $password, $mail = null) {
        $fields['nombre'] = $nombre;
        $fields['password'] = password_hash($password, PASSWORD_DEFAULT);
        $fields['mail'] = $mail;
    }

    public function save() {
        return true;
    }

    public function autenticar() {
        return true;
    }

    public function tirarCarta(){

    }

    public function obtenerComodines(){

    }

    
}

?>