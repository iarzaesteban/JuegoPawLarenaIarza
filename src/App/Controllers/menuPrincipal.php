<?php


namespace Src\App\Controllers;

use Src\Core\Controller;

use Src\App\Models\menu_principal;
use Src\App\Models\Usuario;
use Src\App\Models\CasilleroNormal;
use Src\App\Models\Juego;
use Src\App\Controllers\MenuPartidaController;


class MenuPrincipal extends Controller{

    public ?string $modelname = menu_principal::class;


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
        $jugador = new Usuario($nombre, $password, $mail);
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
            $jugador = new Usuario($nombre, $password);
            $jugador->setLogger($this->logger);
            $jugador->setConnection($this->connection);
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
                $juego = new Juego();
                $juego->setLogger($this->logger);
                $juego->setConnection($this->connection);
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
                $juego = new Juego();
                $juego->setLogger($this->logger);
                $juego->setConnection($this->connection);
                $juego->setNombre($this->request->get("nombre-sala"));
                if ($juego->crear($this->session->get("USUARIO"))) {
                    $juego->agregarJugador($this->session->get("USUARIO"));
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
                $juego = new Juego();
                $juego->setLogger($this->logger);
                $juego->setConnection($this->connection);
                $juego->setNombre($nombreSala);
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
                $juego = new Juego();
                $juego->setLogger($this->logger);
                $juego->setConnection($this->connection);
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
                $juego = new Juego();
                $juego->setLogger($this->logger);
                $juego->setConnection($this->connection);
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
        $juego = new Juego();
        $juego->setLogger($this->logger);
        $juego->setConnection($this->connection);
        $juego->setNombre($this->request->get("nombre"));
        if (!$juego->agregarJugador($this->session->get("USUARIO"))){
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
                $juego = new Juego();
                $juego->setLogger($this->logger);
                $juego->setConnection($this->connection);
                $juego->setNombre($this->request->get("nombre-sala"));
                $juego->setEstadoIniciado();
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