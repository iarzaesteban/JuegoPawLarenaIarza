<?php


namespace Src\App\Controllers;

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
        return $this->modelname->tirarDado();
    } 


    public function tirarComodin(Carta $carta){
        $this->modelname->tirarComodin($carta,$this->modelname->getListaJugadores($this->getJugadorTurno()));
    }

    public function obtenerComodines(){
        return $this->modelname->obtenerComodines();
    }

    public function ocuparCasilleros(Casillero $casilleros){
        $this->modelname->ocuparCasilleros($casilleros);
    }

    public function getJugadorTurno(){
        $this->molname->getJugadorTurno();
    }

    public function getListaJugadores(){
        $this->modelname->getListaJugadores();
    }







}