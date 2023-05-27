<?php 
namespace App\Controllers; // -> esta linea determina la carpeta donde se encuentra el archivo

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

use App\Exceptions\TablaSinDatosException;
use App\Exceptions\NoExisteEnTablaException;
use App\Exceptions\CamposVaciosException;

abstract class Controller {
  protected function obtenerUnico($model, $tabla, $id, $res) {
    try {
      if (!$model->existeDato($id)) {
        throw new NoExisteEnTablaException($tabla);
      }

      $dato = $model->obtenerUnico($id);

      $res->getBody()->write(json_encode([
        'dato' => $dato
      ]));
      return $res
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);

    } catch (NoExisteEnTablaException $e) {
      $res->getBody()->write(json_encode([
        'error' => $e->getMessage()
      ]));
      return $res
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(400);
    } catch (\Exception $e) {
      $res->getBody()->write(json_encode([
        'error' => $e->getMessage()
      ]));
      return $res
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(500);
    }
  }
  abstract protected function crear(Request $req, Response $res, $args);

  protected function elimnar($model, $tabla, $id, $res) {
    try {
      if (!$model->existeDato($id)) {
        throw new NoExisteEnTablaException($tabla);
      }

      $nombreDatoEliminado = $model->eliminarDato($id);
      $res->getBody()->write(json_encode([
        'mensaje' => 'El dato '. $nombreDatoEliminado .' fue eliminado con exito'
      ]));
      return $res
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);

    } catch (NoExisteEnTablaException $e) {
      $res->getBody()->write(json_encode([
        'error' => $e->getMessage()
      ]));
      return $res
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(400);
    } catch (\Exception $e) {
      $res->getBody()->write(json_encode([
        'error' => $e->getMessage()
      ]));
      return $res
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(500);
    }
  }
  protected function actualizar($model, $tabla, $id, $datos, $files, $res) {
    try {
      if (empty($datos) && empty($files)) {
        throw new CamposVaciosException($tabla);
      }

      if (!$model->existeDato($id)) {
        throw new NoExisteEnTablaException;
      }

      // Asignamos los datos al objeto 
      $metodos = '';
      foreach($datos as $atributo => $valor){
        $metodos = 'set' . ucfirst($atributo);
          // ucfirst() -> convierte el primer caracter de la cadena en mayuscula
        if (isset($datos[$atributo]) && !empty($datos[$atributo]) && property_exists($model, $atributo)) {
          $model->$metodos($valor);
            // set{$atributo}() -> "anida el valor de la variable $atributo dentro del nombre del metodo set"
            // ej. estariamo llamando al metodo setNombre()
        }
      }
      if (!empty($files)) {
        $model->setTipoImagen($files['imagen']->getClientMediaType());
        $model->setImagen(substr(base64_encode(
          file_get_contents(
            $files['imagen']
              ->getStream()
              ->getMetadata('uri')
            )
          ),0,5)); // FIXME: el substr esta porque no tengo ganas de envioar todo el choclo ese :D (eliminar) 
      }

      // Actualizamos el dato
      $model->actualizarDato($id);
      $nombreDatoActualizado = $model->getNombre();
      

      $res->getBody()->write(json_encode([
        'mensaje' => 'El dato '. $nombreDatoActualizado .' fue actualizado con exito'
      ]));
      return $res
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);

    } catch (CamposVaciosException $e) {
      $res->getBody()->write(json_encode([
        'error' => $e->getMessage()
      ]));
      return $res
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(404);
    } catch (NoExisteEnTablaException $e) {
      $res->getBody()->write(json_encode([
        'error' => $e->getMessage()
      ]));
      return $res
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(400);
    } catch (\Exceptoin $e) {
      $res->getBody()->write(json_encode([
        'error' => $e->getMessage()
      ]));
      return $res
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(500);
    }
  }
  
  // ---
  protected function obtenerTodos($model, $tabla, $res) {
    try {
      $datos = $model->obtenerTodos();
      if (empty($datos)) {
        throw new TablaSinDatosException($tabla);
      }
    
      $res->getBody()->write(json_encode([
        'datos' => $datos
      ]));
      return $res
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);

    } catch (TablaSinDatosException $e) {
      $res->getBody()->write(json_encode([
        'error' => $e->getMessage()
      ]));
      return $res
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(400);
    } catch (\Exception $e) {
      $res->getBody()->write(json_encode([
        'error' => $e->getMessage()
      ]));
      return $res
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(500);
    }

    return $model->obtenerTodos();
  }
}