<?php


namespace Paw\Core;

use Core\Model;
use Core\Database\QueryBuilder;

class Controller{

    public string $viewDir;

    public ?string $modelname = null;

    function __construct(){

        global $connection, $log; // peligroso o confuso.

        $this->viewDir = __DIR__."/../App/view/";
        $this->menu= [
            [
                "href" => "/",
                "name" => "Home",
            ],
            [
                "href" => "/turnos",
                "name" => "Turnos",
            ],
            

        ];




        if(!is_null($this->modelname)){
            $qb = new QueryBuilder($connection,$log);
            $model = new $this->modelname;
            $model->setQueryBuilder($qb);
            $this->setModel($model);
        }

    }

    public function setModel(Model $model){
        $this->model =$model;
    }



}