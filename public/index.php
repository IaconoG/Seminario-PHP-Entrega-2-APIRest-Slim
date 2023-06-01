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
$app->get('/generos/obtener/todos', GeneroController::class . ':listarGeneros');
// Ruta para crear un nuevo genero
$app->post('/generos/crear', GeneroController::class . ':crearGenero');
// Ruta para eliminar un genero
$app->delete('/generos/eliminar/{id}', GeneroController::class . ':eliminarGenero');
// Ruta para actualizar un genero
$app->post('/generos/actualizar/{id}', GeneroController::class . ':actualizarGenero'); // POST porque PATCH no quiere evniar los datos | El formulario tiene un campo que cambio el metodo enviado a patch

use App\Controllers\JuegoController;
// Ruta para mostrar todos los juegos
$app->get('/juegos/obtener/todos', JuegoController::class . ':listarJuegos');
// Ruta para crear un nuevo juego
$app->post('/juegos/crear', JuegoController::class . ':crearJuego');
// Ruta para eliminar un juego
$app->delete('/juegos/eliminar/{id}', JuegoController::class . ':eliminarJuego');
// Ruta para actualizar un juego
$app->post('/juegos/actualizar/{id}', JuegoController::class . ':actualizarJuego'); // POST porque PATCH no quiere evniar los datos | El formulario tiene un campo que cambio el metodo enviado a patch
// Ruta para obtener un juego
$app->get('/juegos/buscar', JuegoController::class . ':buscarJuegos'); // juegos/buscar?nombre=&id_genero=&id_plataforma=&orden=

use App\Controllers\PlataformaController;
// Ruta para mostrar todos las plataformas
$app->get('/plataformas/obtener/todos', PlataformaController::class . ':listarPlataformas');
// Ruta para crear una nueva plataforma
$app->post('/plataformas/crear', PlataformaController::class . ':crearPlataforma');
// Ruta para eliminar una plataforma
$app->delete('/plataformas/eliminar/{id}', PlataformaController::class . ':eliminarPlataforma');
// Ruta para actualizar una plataforma
$app->post('/plataformas/actualizar/{id}', PlataformaController::class . ':actualizarPlataforma'); // POST porque PATCH no quiere evniar los datos | El formulario tiene un campo que cambio el metodo enviado a patch



$app->run();     


//TODO: Al momento de buscar un dato en la tabla importa la mayuscula y minuscula!, resolver
