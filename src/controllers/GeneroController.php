<?php
namespace App\Controllers; // -> esta linea determina la carpeta donde se encuentra el archivo

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

use App\Models\Genero;
use App\Views\GeneroView;

class GeneroController {
  // Obtenemos los datos del nuevo genero desde el cuerpo de la solicitud
  public function crearGenero(Request $req, Response $res, $args) {
    $data = $req->getParsedBody();
      // getParseBody() -> Si el método de solicitud es POST y el tipo de contenido es application/x-www-form-urlencoded o multipart/form-data, se puede recuperar todos los parámetros POS.

    // Validmos los datos recibidos()
    if(empy($data['nombre'])) {
      // Devolvemos una respuesta de error 400 Bad Request
      return $res->withStatus(400)->withJson([
        'error' => 'El nombre del genero es requerido'
      ]);
    }

    // Crear una nueva instancia del modelo Genero y asignamos los datos recibidos
    $genero = new Genero();
    $genero->setNombre($data['nombre']);

    // Guardamos el nuevo genero en la base de datos
    $genero->save();

    // Devolvemos una respuesta de exito 200 ok, con el genero y el id del genero creado 
    return $res->withStatus(200)->withJson([
      'genero' => $genero,
      'id' => $genero->getId()
    ]);
  }
  // Obtenemos todos los generos de la base de datos
  public function listarGeneros(Request $req, Response $res, $args) {
    $generos = Genero::all();

    if (empty($generos)) {
      // Devolvemos una respuesta de error 404 Not Found
      $res->getBody()->write(json_encode([
        'error' => 'No existen generos en la base de datos'
      ]));

      return $res
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(404);
    }

    $res->getBody()->write(json_encode($generos));
    return $res
      ->withHeader('Content-Type', 'application/json')
      ->withStatus(200);
  }
}
