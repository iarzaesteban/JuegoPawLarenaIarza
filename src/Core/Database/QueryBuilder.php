<?php

namespace Core\Database;

use PDO;

use Monolog\logger;

class QueryBuilder{

    public function __construct(PDO $pdo, Logger $logger = null)
    {
        $this->pdo = $pdo;
        $this->logger = $logger;

    }


    public function select ($table,$params= []){
        //              EXAMPLE
        // $where = "1=1";
        
        // if (isset($params['id'])){
        //     $where= 'id = :id';
        // }
        // $query = "SELECT * FROM {$table} WHERE {$where}";
        // $sentencia = $this->pdo->prepare($query);

        // if (isset($params['id'])){
        //     $sentencia->bindValue(":id",$params['id']);
        // }
        // $sentencia->setFetchMode(PDO::FETCH_ASSOC);
        // $sentencia->execute();
        // return $sentencia->fetchAll();

    }
    


    public function update(){

    }


    public function delete(){

    }

    //queda para despues
    public function getColumnsNames($table){
        $query = "select column_name from information_schema.columns where table_name = '{$table}'";
        $sentencia = $this->pdo->prepare($query);
        $sentencia->setFetchMode(PDO::FETCH_ASSOC);
        $sentencia->execute();
        return $sentencia->fetchAll();
    }
}