<?php


namespace Src\Core;

use Core\Model;
use Core\Database\QueryBuilder;
use Src\Core\Traits\tSession;
use Src\Core\Traits\tRequest;
use Src\Core\Traits\loggable;
use Src\Core\Traits\connectable;

class Controller{

    use tSession;
    use tRequest;
    use loggable;
    use connectable;

    public string $viewDir;

    public ?string $modelname = null;

    function __construct(){
        $this->viewDir = __DIR__."/../App/Views/";
    }

    public function twigLoader($file, $array) {
        $loader = new \Twig\Loader\FilesystemLoader( __DIR__ . '/../App/Views');
        $twig = new \Twig\Environment($loader, array('auto_reload' => true));
        echo $twig->render($file, $array);
    }


    public function setModel(Model $model){
        $this->model =$model;
    }



}