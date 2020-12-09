<?php


namespace App\Controllers;

use Core\Controller;

use App\Models\menu_principal;

class MenuPartidaController extends Controller{

    public ?string $modelname = menu_principal::class;




    public function index(){

        $titulo = 'Menu';
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

    public function seleccionrCantidadJugadores(){

    } 

    public function ingresarSala(){

    }

    public function obtenerAgentes(){

    }

    public function iniciarJuego(){

    }

    public function verTop(){

    }

    public function verVistaJugadores(){

    }



}