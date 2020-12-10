<?php


namespace Src\App\Controllers;

use Src\Core\Controller;

use Src\App\Models\menu_principal;

class MenuPrincipal extends Controller{

    public ?string $modelname = menu_principal::class;


    public function index(){
        $titulo = 'Menu';
        if (is_null($this->session->get("USUARIO"))) {
            //todo: url para usuarios no logeados (landing)
            $this->twigLoader('landingpage.twig', []);
        } else {
            $this->twigLoader('login.twig', []);
        }
    }

    public function get(){
        
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