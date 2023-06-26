<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;


require_once '../src/config/config.php';
require __DIR__ . '/../vendor/autoload.php';

use Slim\Middleware\MethodOverrideMiddleware;
use Slim\Routing\RouteCollectorProxy;


$app = AppFactory::create();
Header("Access-Control-Allow-Origin: *");
Header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS");
Header("Access-Control-Allow-Headers: Content-Type");
$app->addErrorMiddleware(true, true, true);
$app->setBasePath("/public"); // No pude hacer que no sea necesario poner /public en la url :(

$app->get('/', function (Request $req, Response $res, array $args) {
    $res->getBody()->write('index');
    return $res;
});

use App\Controllers\GeneroController;
// Ruta para mostrar todos los generos
$app->get('/generos', GeneroController::class . ':listarGeneros');
// Ruta para crear un nuevo genero
$app->post('/generos', GeneroController::class . ':crearGenero');
// Ruta para eliminar un genero
$app->delete('/generos/{id}', GeneroController::class . ':eliminarGenero');
// Ruta para actualizar un genero
$app->patch('/generos/{id}', GeneroController::class . ':actualizarGenero'); 

use App\Controllers\JuegoController;
// Ruta para crear un nuevo juegos
$app->post('/juegos', JuegoController::class . ':crearJuego');
// Ruta para eliminar un juego
$app->delete('/juegos/{id}', JuegoController::class . ':eliminarJuego');
// Ruta para actualizar un juegos
$app->patch('/juegos/{id}', JuegoController::class . ':actualizarJuego'); 
// Ruta para buscar un juegos
$app->get('/juegos', JuegoController::class . ':buscarJuegos'); // juegos/buscar?nombre=&id_genero=&id_plataforma=&orden=

use App\Controllers\PlataformaController;
// Ruta para mostrar todos las plataformas
$app->get('/plataformas', PlataformaController::class . ':listarPlataformas');
// Ruta para crear una nueva plataforma
$app->post('/plataformas', PlataformaController::class . ':crearPlataforma');
// Ruta para eliminar una plataforma
$app->delete('/plataformas/{id}', PlataformaController::class . ':eliminarPlataforma');
// Ruta para actualizar una plataforma
$app->patch('/plataformas/{id}', PlataformaController::class . ':actualizarPlataforma'); 

// Ruta para cargar los datos en la base de datos
$app->post('/generos/todos', GeneroController::class . ':cargarDatos');
$app->post('/plataformas/todos', PlataformaController::class . ':cargarDatos');
$app->post('/juegos/todos', JuegoController::class . ':cargarDatos');


$app->run();     


