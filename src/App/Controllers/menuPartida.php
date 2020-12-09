<?php


namespace App\Controllers;

use Core\Controller;

use App\Models\juego;

class MenuPartidaController extends Controller{

    public ?string $modelname = juego::class;




    public function index(){

        $titulo = 'Juego';
        //Example
        // $socios = $this->model->getAll();
        // var_dump($socios);
        // require $this->viewDir.'socios.index.view.php';

    }

    public function get(){
        //Example
        // global $request;
        // $socioId = $request->get('id');

        // $socio = $this->model->get($socioId);
        // $titulo = 'Socio';

        // require $this->viewDir.'socios.show.view.php';

    }

    public function set(){

    }

    public function edit(){

    }

    public function tirarDados(){

    } 

    public function tirarComodin(){

    }

    public function obtenerComodines(){

    }

    public function ocuparCasilleros(){

    }

    public function getJugadorTurno(){

    }

    public function getListaJugadores(){

    }







}