<?php
namespace App\Controllers; // -> esta linea determina la carpeta donde se encuentra el archivo

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

use App\Models\Genero;
use App\Views\GeneroView;

class GeneroController extends Controller{
  // --- METODO PARA MOSTRAR TODOS LOS GENEROS ---
  public function listarGeneros(Request $req, Response $res, $args) {
    return $this->obtenerTodos(new Genero(), 'generos', $res);
  }
  // --- METODO PARA OBTENER UN UNICO GENEROS ---
  public function obtenerGenero(Request $req, Response $res, $args) {
    return $this->obtenerUnico(new Genero(), 'generos', $args['id'], $res);
  }
  // --- METODO PARA CREAR UN GENEROS ---
  public function crearGenero(Request $req, Response $res, $args) { 
    return $this->crear(new Genero(), 'generos', $req, null, $res);
  }
  // --- METODO PARA ELIMINAR UN GENEROS ---
  public function eliminarGenero(Request $req, Response $res, $args) {
    return $this->elimnar(new Genero(), 'generos', $args['id'], $res);
  }
  // --- METODO PARA ACTUALIZAR UN GENEROS ---
  public function actualizarGenero(Request $req, Response $res, $args) {
    return $this->actualizar(new Genero(), 'generos', $args['id'], $req, null, $res);
  }
}





// Por si me dice q no akjsjas
 // public function listarGeneros(Request $req, Response $res, $args) {
  //   try {
  //     $generos = Genero::obtenerTodos();
  //       // Genero::obtenerTodos() -> devuelve un array de objetos Genero
  //     if (empty($generos)) {
  //       throw new TablaSinDatosException('generos');
  //     }

  //     $res->getBody()->write(json_encode([
  //       'generos' => $generos
  //     ]));
  //     return $res
  //       ->withHeader('Content-Type', 'application/json')
  //       ->withStatus(200);

  //   } catch (TablaSinDatosException $e) {
  //     $res->getBody()->write(json_encode([
  //       'error' => $e.getMessage()
  //     ]));
  //     return $res
  //       ->withHeader('Content-Type', 'application/json')
  //       ->withStatus(400);
  //   } catch (\Exception $e) {
  //     $res->getBody()->write(json_encode([
  //       'error' => $e.getMessage()
  //     ]));
  //     return $res
  //       ->withHeader('Content-Type', 'application/json')
  //       ->withStatus(500);
  //   }
  // }
  // --- Metodo para crear un genero ---
  // public function crearGenero(Request $req, Response $res, $args) {
  //   $datos = $req->getParsedBody();

  //   try {
  //     if (empty($datos['nombre'])) {
  //       throw new DatoGeneroVacioException();
  //     }

  //     $genero = new Genero();
  //     $genero->setNombre($datos['nombre']);

  //     $genero->crear();
  //     $res->getBody()->write(json_encode([
  //       'mensaje' => 'Genero creado con exito'
  //     ]));
  //     return $res
  //       ->withHeader('Content-Type', 'application/json')
  //       ->withStatus(200);
      
  //   } catch (DatoGeneroVacioException $e) {
  //     $res->getBody()->write(json_encode([
  //       'error' => $e->getMessage()
  //     ]));

  //     return $res
  //       ->withHeader('Content-Type', 'application/json')
  //       ->withStatus(400);
  //   } catch (\Exception $e) {
  //     $res->getBody()->write(json_encode([
  //       'error' => $e->getMessage()
  //     ]));
  //     return $res
  //       ->withHeader('Content-Type', 'application/json')
  //       ->withStatus(500);
  //   }
  // }
  // // --- Metodo para eliminar un juego --
  // public function eliminarGenero(Request $req, Response $res, $args) {
  //   $id = $args['id'];

  //   try {
  //     $genero = new Genero();

  //     if (!$genero->existeGenero($id)) {
  //       throw new NoExisteEnTablaException('genero');
  //     }

  //     $nombreGeneroEliminado = $genero->eliminar($id);
  //     $res->getBody()->write(json_encode([
  //       'mensaje' => 'El genero '. $nombreGeneroEliminado .' fue eliminado con exito'
  //     ]));
  //     return $res
  //       ->withHeader('Content-Type', 'application/json')
  //       ->withStatus(200);

  //   } catch (NoExisteEnTablaException $e) {
  //     $res->getBody()->write(json_encode([
  //       'error' => e.getMessage()
  //     ]));
  //     return $res
  //       ->withHeader('Content-Type', 'application/json')
  //       ->withStatus(400);
  //   } catch (\Exception $e) {
  //     $res->getBody()->write(json_encode([
  //       'error' => $e.getMessage() 
  //     ]));
  //     return $res
  //       ->withHeader('Content-Type', 'appliaction/json')
  //       ->withStatus(500);
  //   } 
  // }