<?php


namespace Src\App\Controllers;

use Src\Core\Controller;
use Src\App\Models\juego;
use Src\App\Models\Jugador;

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
        if (is_null($this->session->get("USUARIO"))) {
            $this->logger->warning("Acceso no autorizado");
            $this->twigLoader('guest.landingpage.twig', []);
        } else {
            if (is_null($this->request->get("nombre-sala"))) {
                $this->logger->warning("Prm.invalidos");
                $this->twigLoader('guest.landingpage.twig', []);
            } else {
                $juego = $this->instanciarJuego("nombre-sala");
                $notificacion = $juego->tirarDado($this->session->get("USUARIO"));
                $this->twigLoader('game.notificar.twig', compact("notificacion"));
            }
        }
        //return $this->modelname->tirarDado();
    } 


    public function tirarComodin(Carta $carta){
        $this->modelname->tirarComodin($carta,$this->modelname->getListaJugadores($this->getJugadorTurno()));
    }

    public function obtenerComodines(){
        return $this->modelname->obtenerComodines();
    }

    public function ocupar() {
        if (is_null($this->session->get("USUARIO"))) {
            $this->logger->warning("Acceso no autorizado");
            $this->twigLoader('guest.landingpage.twig', []);
        } else {
            if (is_null($this->request->get("nombre-sala"))) {
                $this->logger->warning("Prm.invalidos");
                $this->twigLoader('guest.landingpage.twig', []);
            } else {
                $juego = $this->instanciarJuego("nombre-sala");
                if ($juego->ocupar($this->request->get("seleccion"), $this->session->get("USUARIO"))){
                    echo "OK";
                } else {
                    echo "ERROR";
                }
            }
        }
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

    public function verTablero() {
        if (is_null($this->session->get("USUARIO"))) {
            $this->logger->warning("Acceso no autorizado");
            $this->twigLoader('guest.landingpage.twig', []);
        } else {
            if (is_null($this->request->get("nombre-sala"))) {
                $this->logger->warning("Prm.invalidos");
                $this->twigLoader('guest.landingpage.twig', []);
            } else {
                $juego = $this->instanciarJuego("nombre-sala");
                $jugador = $this->instanciarUsuario();
                $tablero = $juego->getTablero();
                $jugadores = $juego->getJugadores();
                $filasCasilleros = $juego->getFilasCasilleros();
                $ayuda = Juego::getAyuda();
                $this->twigLoader('game.twig', compact("tablero", "jugadores", "jugador", "juego", "filasCasilleros"));
            }
        }
    }

    public function getMenu() {
        if (is_null($this->session->get("USUARIO"))) {
            $this->logger->warning("Acceso no autorizado");
            $this->twigLoader('guest.landingpage.twig', []);
        } else {
            if (is_null($this->request->get("nombre-sala"))) {
                $this->logger->warning("Prm.invalidos");
                $this->twigLoader('guest.landingpage.twig', []);
            } else {
                $juego = $this->instanciarJuego("nombre-sala");
                if ($juego->isJugadorTirando($this->session->get("USUARIO"))){
                    //$this->mostrarMenuTirar();
                } else {
                    $this->notificar($juego);
                }
            }
        }
    }

    private function mostrarMenuTirar() {
        $this->twigLoader('game.menu.tirar.twig', []);
    }

    public function notificar($juego) {
        $notificacion = $juego->getNotificacion();
        $this->twigLoader('game.notificar.twig', compact("notificacion"));
    }

    public function getCartas() {
        if (is_null($this->session->get("USUARIO"))) {
            $this->logger->warning("Acceso no autorizado");
            $this->twigLoader('guest.landingpage.twig', []);
        } else {
            if (is_null($this->request->get("nombre-sala"))) {
                $this->logger->warning("Prm.invalidos");
                $this->twigLoader('guest.landingpage.twig', []);
            } else {
                
            }
        }
    }

    public function instanciarJuego($param){
        $juego = new Juego();
        $juego->setLogger($this->logger);
        $juego->setConnection($this->connection);
        $juego->setNombre($this->request->get($param));
        $juego->setEstadoIniciado();
        $juego->load();
        return $juego;
    }

    public function instanciarUsuario() {
        $usuario = new Jugador();
        $usuario->setLogger($this->logger);
        $usuario->setConnection($this->connection);
        $usuario->setNombre($this->session->get("USUARIO"));
        $usuario->load();
        return $usuario;
    }

    public function getCasillerosValidos() {
        if (is_null($this->session->get("USUARIO"))) {
            $this->logger->warning("Acceso no autorizado");
            $this->twigLoader('guest.landingpage.twig', []);
        } else {
            if (is_null($this->request->get("nombre-sala"))) {
                $this->logger->warning("Prm.invalidos");
                $this->twigLoader('guest.landingpage.twig', []);
            } else {
                $juego = $this->instanciarJuego("nombre-sala");
                $celdasValidas = $juego->getCeldasValidasStr($this->session->get("USUARIO"));
                $this->logger->debug("CELDAS VALIDAS: $celdasValidas");
                $this->twigLoader('game.cells.twig', compact("celdasValidas"));
            }
        }
    }

    public function actualizar() {
        if (is_null($this->session->get("USUARIO"))) {
            $this->logger->warning("Acceso no autorizado");
            $this->twigLoader('guest.landingpage.twig', []);
        } else {
            if (is_null($this->request->get("nombre-sala"))) {
                $this->logger->warning("Prm.invalidos");
                $this->twigLoader('user.landingpage.twig', []);
            } else {
                
            }
        }
    }

    public function puntuaciones() {
        if (is_null($this->session->get("USUARIO"))) {
            $this->logger->warning("Acceso no autorizado");
            $this->twigLoader('guest.landingpage.twig', []);
        } else {
            if (is_null($this->request->get("nombre-sala"))) {
                $this->logger->warning("Prm.invalidos");
                $this->twigLoader('user.landingpage.twig', []);
            } else {
                $juego = $this->instanciarJuego("nombre-sala");
                $jugadores = $juego->getJugadores();
                $jugadorTurno = $juego->getJugadorTurno();
                $this->twigLoader('game.players.twig', compact("jugadores", "jugadorTurno"));
            }
        }
    }
}