<?php
namespace App\Controllers; // -> esta linea determina la carpeta donde se encuentra el archivo

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use App\Models\DB;

$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);
$app->setBasePath("/src/controllers");

// Home
$app->get('/juegos/all', function(Request $req, Response $res, $args) {
  $sql = "SELECT * FROM juegos";

  try {
      $db = new DB();
      $db = $db->getConnection();
      $resultado = $db->query($sql);

  if ($resultado->rowCount() > 0) {
      $juegos = $resultado->fetchAll(PDO::FETCH_OBJ);
      $res->getBody()->write(json_encode($juegos));
  } else {
      $res->getBody()->write(json_encode("No existen juegos en la base de datos"));
  }
  $resultado = null;
  $db = null;

  return $res->withHeader('Content-Type', 'application/json')->withStatus(200);
  } catch (PDOException $e) {
      echo '{"error" : {"text": '.$e->getMessage().' }';
  };
});

// Obtener todos los juegos
// $app->get('/juegos/', function (Request $req, Response $res, array $args) {
//   $db = new DB();

//   $res = 

// });










