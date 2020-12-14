<?php


namespace Src\App\Controllers;

use Src\Core\Controller;

use Src\App\Models\menu_principal;
use Src\App\Models\Jugador;

class MenuPrincipal extends Controller{

    public ?string $modelname = menu_principal::class;


    public function index(){
        $titulo = 'Menu';
        if (is_null($this->session->get("USUARIO"))) {
            $this->twigLoader('guest.landingpage.twig', []);
        } else {
            $this->twigLoader('user.landingpage.twig', []);
        }
    }

    public function crearCuenta(){
        $titulo = 'Menu';
        if (is_null($this->session->get("USUARIO"))) {
            $this->twigLoader('guest.createAccount.twig', []);
        } else {
            $this->twigLoader('user.landingpage.twig', []);
        }
    }

    public function crearCuentaAlmacenar(){
        $titulo = 'Menu';
        $nombre = $this->request->get('usuario');
        $password = $this->request->get('password');
        $mail = $this->request->get('mail');
        $jugador = new Jugador($nombre, $password, $mail);
        if ($jugador->save()) {
            $this->twigLoader('user.accountCreated.twig', []);
        } else {
            $this->twigLoader('guest.accountCreationFailled.twig', []);
        }
    }

    public function login(){
        $titulo = 'Menu';
        if (is_null($this->session->get("USUARIO"))) {
            $this->twigLoader('guest.login.twig', []);
        } else {
            $this->twigLoader('user.landingpage.twig', []);
        }
    }

    public function loginAutenticar(){
        $titulo = 'Menu';
        if (is_null($this->session->get("USUARIO"))) {
            $nombre = $this->request->get('usuario');
            $password = $this->request->get('password');
            $jugador = new Jugador($nombre, $password);
            if ($jugador->autenticar()) {
                $this->session->put("USUARIO", $nombre);
                $this->twigLoader('user.loginCorrect.twig', []);
            } else {
                $this->twigLoader('guest.loginIncorect.twig', []);
            }
        } else {
            $this->twigLoader('user.landingpage.twig', []);
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