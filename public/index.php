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
$app->post('/generos/actualizar/{id}', GeneroController::class . ':actualizarGenero'); // convertido en PATCH por el formulario
// Ruta para obtener un genero
$app->get('/generos/obtener/{id}', GeneroController::class . ':obtenerGenero');

use App\Controllers\JuegoController;
// Ruta para mostrar todos los juegos
$app->get('/juegos/todos', JuegoController::class . ':listarJuegos');
// Ruta para crear un nuevo genero
$app->post('/juegos/crear', JuegoController::class . ':crearJuego');
// Ruta para eliminar un genero
$app->delete('/juegos/eliminar/{id}', JuegoController::class . ':eliminarJuego');
// Ruta para actualizar un genero
$app->post('/juegos/actualizar/{id}', JuegoController::class . ':actualizarJuego'); // convertido en PATCH por el formulario
// Ruta para obtener un genero
$app->get('/juegos/obtener/{id}', JuegoController::class . ':obtenerJuego');

use App\Controllers\PlataformaController;
// Ruta para mostrar todos los juegos
$app->get('/plataformas/todos', PlataformaController::class . ':listarPlataformas');
// Ruta para crear un nuevo genero
$app->post('/plataformas/crear', PlataformaController::class . ':crearPlataforma');
// Ruta para eliminar un genero
$app->delete('/plataformas/eliminar/{id}', PlataformaController::class . ':eliminarPlataforma');
// Ruta para actualizar un genero
$app->post('/plataformas/actualizar/{id}', PlataformaController::class . ':actualizarPlataforma'); // convertido en PATCH por el formulario
// Ruta para obtener un genero
$app->get('/plataformas/obtener/{id}', PlataformaController::class . ':obtenerPlataforma');



$app->run();     


//TODO: Al momento de buscar un dato en la tabla importa la mayuscula y minuscula!, resolver