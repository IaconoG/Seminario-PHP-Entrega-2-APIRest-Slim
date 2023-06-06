<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;


require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);
$app->setBasePath("/public");

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



$app->run();     


