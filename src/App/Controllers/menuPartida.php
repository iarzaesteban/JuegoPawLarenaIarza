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

    public function verTablero() {
        if (is_null($this->session->get("USUARIO"))) {
            $this->logger->warn("Acceso no autorizado");
            $this->twigLoader('guest.landingpage.twig', []);
        } else {
            if (is_null($this->request->get("nombre-sala"))) {
                $this->logger->warn("Prm.invalidos");
                $this->twigLoader('guest.landingpage.twig', []);
            } else {

            }
        }
    }


}