<?php

require __DIR__. "/../vendor/autoload.php";

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Dotenv\Dotenv;


use Src\Core\Request;
use Src\Core\Router;
use Src\Core\Session;
use Src\Core\Config;
use Src\Core\Database\ConnectionBuilder;


$whoops = new \Whoops\Run;
$whoops ->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

$dotenv = Dotenv::createUnsafeImmutable(__DIR__.'/../');
$dotenv->load();

$config = new Config;


$log = new Logger('mvc-app');
$handler = new StreamHandler($config->get("LOG_PATH"));
$handler->setLevel($config->get("LOG_LEVEL"));
$log->pushHandler($handler);


$connectionBuilder = new ConnectionBuilder();
$connectionBuilder->setLogger($log);
$connection = $connectionBuilder->make($config);

$request = new Request;

$router = new Router;
$session = new Session;

$router->setLogger($log);
$router->setConnection($connection);
$router->setRequest($request);
$router->setSession($session);
$router->get('/','menuPrincipal@index');
$router->get('/crearCuenta','menuPrincipal@crearCuenta');