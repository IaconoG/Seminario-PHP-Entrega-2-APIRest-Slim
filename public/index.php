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


use App\Controllers\JuegoController;
// Ruta para mostrar todos los juegos
$app->get('/juegos/todos', JuegoController::class . ':listarJuegos');
// Ruta para crear un nuevo genero
$app->post('/juegos/crear', JuegoController::class . ':crearJuego');
// Ruta para eliminar un genero
$app->delete('/juegos/eliminar/{nombre}', JuegoController::class . ':eliminarJuego');
// Ruta para actualizar un genero
$app->post('/juegos/actualizar/{id}', JuegoController::class . ':actualizarJuego'); // convertido en PATCH por el formulario
// Ruta para obtener un genero
$app->get('/juegos/obtener/{nombre}', JuegoController::class . ':obtenerJuego');





$app->run();     


