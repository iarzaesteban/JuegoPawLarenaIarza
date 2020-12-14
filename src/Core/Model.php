<?php


namespace Src\Core;

use Src\Core\Database\QueryBuilder;
use Src\Core\Traits\loggable;
use Src\Core\Traits\connectable;

class Model{
    use loggable;
    use connectable;

    private $table;
    private $id;
    public $queryBuilder;

    public function setQueryBuilder(QueryBuilder  $qb){
        $this->queryBuilder = $qb;

    }

    public function save() {
        return true;
        // $datos = get_object_vars($this);
        // $existe = false;
        // if ($this->id) {
        //     $existe = true;
        //     $query = "SELECT * FROM {$this->table} WHERE ID=:id";
        //     $sentencia = $this->pdo->prepare($query);
        // }
        // if ($existe) {
        //     //insert
        // } else {
        //     //update
        // }
        // foreach ($datos as $key => $val) {
            
        // }
    }

}