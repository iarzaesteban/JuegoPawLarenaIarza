<?php


namespace Src\App\Controllers;

use Src\Core\Controller;

use Src\App\Models\MenuPrincipal;
use Src\Core\Model;
use Src\App\Models\CasilleroNormal;
use Src\App\Models\Juego;
use Src\App\Controllers\MenuPartidaController;


class Menu_principal extends Controller{

    public ?string $modelname = MenuPrincipal::class;


    public function index(){
        $titulo = 'Menu';
        $ayuda = Juego::getAyuda();
        if (is_null($this->session->get("USUARIO"))) {
            $this->twigLoader('guest.landingpage.twig', compact("ayuda"));
        } else {
            $this->twigLoader('user.landingpage.twig', compact("ayuda"));
        }
    }

    public function crearCuenta(){
        $titulo = 'Menu';
        if (is_null($this->session->get("USUARIO"))) {
            $this->twigLoader('guest.createAccount.twig', []);
        } else {
            $this->index();
        }
    }

    public function crearCuentaAlmacenar(){
        $titulo = 'Menu';
        $nombre = $this->request->get('usuario');
        $password = $this->request->get('password');
        $mail = $this->request->get('mail');
        $jugador = Model::factory("Usuario");
        $jugador->inicializarUsuario($nombre, $password, $mail);
        $jugador->setLogger($this->logger);
        $jugador->setConnection($this->connection);
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
            $this->index();
        }
    }

    public function loginAutenticar(){
        $titulo = 'Menu';
        if (is_null($this->session->get("USUARIO"))) {
            $nombre = $this->request->get('usuario');
            $password = $this->request->get('password');
            $jugador = Model::factory("Usuario");
            $jugador->inicializarUsuario($nombre, $password);
            if ($jugador->autenticar()) {
                $this->session->put("USUARIO", $nombre);
                $this->twigLoader('user.loginCorrect.twig', []);
            } else {
                $this->twigLoader('guest.loginIncorect.twig', []);
            }
        } else {
            $this->index();
        }
    }

    public function sala(){
        $titulo = 'Menu';
        if (is_null($this->session->get("USUARIO"))) {
            $this->index();
        } else {
            if ($this->request->get("nombre")) {
                $this->ingresarSala();
                // $juego = new Juego();
                // $juego->setLogger($this->logger);
                // $juego->setConnection($this->connection);
                // $juego->setNombre($this->request->get("nombre"));
                // $jugadores = $juego->getJugadores();
                // $nombre = $this->request->get("nombre");
                // $this->twigLoader('user.room.creation.twig', compact("jugadores", "nombre"));
            } else {
                /*$juego = new Juego();
                $juego->setLogger($this->logger);
                $juego->setConnection($this->connection);*/
                $juego = Model::factory("Juego");
                $juegos = $juego->obtenerSalasAbiertas();
                $this->twigLoader('user.room.twig', compact("juegos"));
            }
        }
    }

    public function crearSala(){
        $titulo = 'Menu';
        if (is_null($this->session->get("USUARIO"))) {
            $this->index();
        } else {
            if (is_null($this->request->get("nombre-sala"))) {
                $this->logger->warning("Nombre de sala nulo");
                $this->twigLoader('user.room.name.setting.twig', []);
            } else {
                $nombreSala = $this->request->get("nombre-sala");
                $usuario = $this->session->get("USUARIO");
                /*$juego = new Juego();
                $juego->setLogger($this->logger);
                $juego->setConnection($this->connection);*/
                $juego = Model::factory("Juego");
                $juego->setNombre($this->request->get("nombre-sala"));
                if ($juego->crear($this->session->get("USUARIO"))) {
                    $juego->ingresarSala($this->session->get("USUARIO"));
                    $this->twigLoader('user.room.admin.twig', compact("nombreSala", "usuario"));
                } else {
                    $mensaje = "Nombre ya existe";
                    $this->twigLoader('user.room.name.setting.twig', compact("mensaje"));
                }
            }
        }
    }

    public function obtenerListaJugadores() {
        $titulo = 'Menu';
        if (is_null($this->session->get("USUARIO"))) {
            $this->logger->warning("Acceso no autorizado");
            $this->index();
        } else {
            if (is_null($this->request->get("nombre-sala"))) {
                $this->logger->warning("Prm.invalidos");
                //ignore
            } else {
                $nombreSala = $this->request->get("nombre-sala");
                $usuario = $this->session->get("USUARIO");
                /*$juego = new Juego();
                $juego->setLogger($this->logger);
                $juego->setConnection($this->connection);*/
                $juego = Model::factory("Juego");
                $juego->setNombre($nombreSala);
                $juego->setEstadoNoIniciado();
                $jugadores = $juego->getJugadores();
                $this->logger->debug("jugadores: ". json_encode($jugadores));
                $this->twigLoader('user.room.players.twig', compact("nombreSala", "jugadores"));
            }
        }
    }

    public function crearSalaIngresarNombre() {
        $titulo = 'Menu';
        if (is_null($this->session->get("USUARIO"))) {
            $this->index();
        } else {
            if (is_null($this->request->get("nombreSala"))) {
                //ignore
            } else {
                $nombreSala = $this->request->get("nombreSala");
                $usuario = $this->session->get("USUARIO");
                /*$juego = new Juego();
                $juego->setLogger($this->logger);
                $juego->setConnection($this->connection);*/
                $juego = Model::factory("Juego");
                $juego->setNombre($nombreSala);
                $jugadores = $juego->getJugadores();
                $this->twigLoader('user.room.players.twig', compact("nombreSala", "usuario"));
            }
        }
    }

    public function isJuegoListo() {
        if (is_null($this->session->get("USUARIO"))) {
            $this->index();
        } else {
            if (is_null($this->request->get("nombre-sala"))) {
                //ignore
            } else {
                $nombreSala = $this->request->get("nombre-sala");
                $usuario = $this->session->get("USUARIO");
                /*$juego = new Juego();
                $juego->setLogger($this->logger);
                $juego->setConnection($this->connection);*/
                $juego = Model::factory("Juego");
                $juego->setNombre($nombreSala);
                if ($juego->isListo()) {
                    echo "OK";
                }
            }
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
        $juego = Model::factory("Juego");
        $juego->setNombre($this->request->get("nombre"));
        if (!$juego->ingresarSala($this->session->get("USUARIO"))){
            $this->logger->error("Jugador no ingresado");
        }
        $jugadores = $juego->getJugadores();
        $nombre = $this->request->get("nombre");
        $this->twigLoader('user.room.creation.twig', compact("jugadores", "nombre"));
    }

    public function obtenerAgentes(){

    }

    public function iniciarJuego(){
        if (is_null($this->session->get("USUARIO"))) {
            $this->index();
        } else {
            if (is_null($this->request->get("nombre-sala"))) {
                $this->index();
            } else {
                $nombreSala = $this->request->get("nombreSala");
                $juego = Model::factory("Juego");
                $juego->setNombre($this->request->get("nombre-sala"));
                $juego->setEstadoNoIniciado();
                $juego->iniciarJuego();
                $this->session->put("nombre-sala", $nombreSala);
                $menuPartida = new MenuPartidaController();
                $menuPartida->setLogger($this->logger);
                $menuPartida->setConnection($this->connection);
                $menuPartida->setRequest($this->request);
                $menuPartida->setSession($this->session);
                $menuPartida->verTablero();
            }
        }
    }

    public function verTop(){

    }

    public function verVistaJugadores(){

    }



}