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
$app->get('/generos/todos', GeneroController::class . ':listarGeneros');
// Ruta para crear un nuevo genero
$app->post('/generos/crear', GeneroController::class . ':crearGenero');
// Ruta para eliminar un genero
$app->delete('/generos/eliminar/{id}', GeneroController::class . ':eliminarGenero');
// Ruta para actualizar un genero

// Ruta para obtener un genero


use App\Controllers\JuegoController;
// Ruta para mostrar todos los juegos
$app->get('/juegos/todos', JuegoController::class . ':listarJuegos');
// Ruta para crear un nuevo genero
$app->post('/juegos/crear', JuegoController::class . ':crear');
// Ruta para eliminar un genero
$app->delete('/juegos/eliminar/{id}', JuegoController::class . ':eliminarJuego');
// Ruta para actualizar un genero
$app->post('/juegos/actualizar/{id}', JuegoController::class . ':actualizarJuego'); // convertido en PATCH por el formulario
// Ruta para obtener un genero
$app->get('/juegos/obtener/{id}', JuegoController::class . ':obtenerJuego');





$app->run();     


//TODO: Al momento de buscar un dato en la tabla importa la mayuscula y minuscula, resolver