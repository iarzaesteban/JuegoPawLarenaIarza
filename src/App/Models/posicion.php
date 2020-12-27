<?php

namespace Src\App\Models;

use Src\Core\Model;
use Exception;

use Src\Core\Exceptions\invalidValueFormatException;


class Posicion extends Model {

    public function __construct($x,$y){
        $this->x = $x;
        $this->y = $y;
        $this->dbHandler->fields = [
            "id" => null,
            'posicionX'    => $x,
            'posicionY'  => $y
        ];
        $this->dbHandler->table = 'posicion';
    }

    public $x;
    public $y;

    public function setX($x){
        $this->x = $x;
    }


    public function setY($y){
        $this->y = $y;
    }

    public function getX(){
        return $this->x;
    }


    public function getY(){
        return $this->y;
    }

    public $fields = [
        'x'    => null,
        'y'  => null
    ];

    public function load() {
        $res = $this->findByFields([
            "posicionX" => $this->dbHandler->fields["posicionX"],
            "posicionY" => $this->dbHandler->fields["posicionY"]
        ]);
        if (count($res) == 1) {
            $this->dbHandler->fields["id"] = $res[0]["id"];
        } else {
            if ($this->save()){
                $this->loadByFields([
                    "posicionX" => $this->dbHandler->fields["posicionX"],
                    "posicionY" => $this->dbHandler->fields["posicionY"]
                ]);
            }            
        }
    }
    
}

?>