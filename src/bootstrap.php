<?php



// autoload es un mecanismo de php que me dice que cargue de forma automatica todos los objetos 
// y clases que haya en este proyecto.


require __DIR__. "/../vendor/autoload.php";

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Dotenv\Dotenv;


use Src\Core\Request;
use Src\Core\Router;
use Src\Core\Session;
use Src\Core\Config;
use Src\Core\Database\ConnectionBuilder;

if(session_id() == ''){
    //session has not started
    session_start();
}
$whoops = new \Whoops\Run;
$whoops ->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();



$dotenv = Dotenv::createUnsafeImmutable(__DIR__.'/../');
$dotenv->load();//luego de que se ejecuto esto las variables de .env pasan a estar disponibles

$config = new Config;   

        //getenv("LOG_LEVEL");
//$_ENV["LOG_ENV"];//puede ser menos eficiente que la funcion.
$log = new Logger('Epidemiology');//ese nombre es un identificador ya que monolog nos permite tener varios logger para difertes cosas.
$handler = new StreamHandler($config->get("LOG_PATH"));
$handler->setLevel($config->get("LOG_LEVEL"));
$log->pushHandler($handler);



//conexion a la base de datos
$connectionBuilder = new ConnectionBuilder();
$connectionBuilder->setLogger($log);
$connection = $connectionBuilder->make($config);



$whoops = new \Whoops\Run;
$whoops ->pushHandler(new \Whoops\Handler\PrettyPageHandler);//esto significa que puedo generar mi propio handler.

$whoops->register();// con esto le estoy indicando que sobre escrib las funciones de errores de php y que pase el a manejar los errores
//throw new \Exception("Mensaje de error para desarrollador");


$request = new Request;


$router = new Router;

$router->setLogger($log);
$router->setConnection($connection);
$router->setRequest($request);
$router->setSession($session);
$router->get('/','menuPrincipal@index');
$router->get('/crearCuenta','menuPrincipal@crearCuenta');
$router->post('/crearCuenta','menuPrincipal@crearCuentaAlmacenar');
$router->get('/login','menuPrincipal@login');
$router->post('/login','menuPrincipal@loginAutenticar');