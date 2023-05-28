<?php 
namespace App\Controllers; // -> esta linea determina la carpeta donde se encuentra el archivo

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

use App\Exceptions\TablaSinDatosException;
use App\Exceptions\NoExisteEnTablaException;
use App\Exceptions\CamposVaciosActualizarException;
use App\Exceptions\CamposVaciosCrearException;
use App\Exceptions\ErrorEnvioFormularioException;

class Controller {
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
  protected function crear($model, $tabla, $datos, $files, $res) { 
    try {
      // Validacions de los datos 
      if ($datos == null && $files == null) {
        throw new ErrorEnvioFormularioException();
      }

      $errores = $this->validarDatos($datos, $files, $res);
      if (!empty($errores)) {
        throw new CamposVaciosCrearException($errores);
      }

      // Asignamos los datos al objeto
      $metodos = '';
      foreach($datos as $atributo => $valor) {
        $metodos = 'set' . ucfirst($atributo);
        if (property_exists($model, $metodos)) {
          $model->$metodos($valor);
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
          // getStream() -> devuelve un flujo de datos (stream) que representa el contenido del archivo cargado
          // getMetadata('uri') -> devuelve la ubicacion del archivo temporal
          // getClientMediaType() -> devuelve el tipo de media del archivo
      }
      
      // $model->crearDato(); // FIXME: Eliminar comentarios
      $res->getBody()->write(json_encode([
        'mensaje' => 'Juego creado con exito'
      ]));
      return $res
        ->withHeader('Content-Type', 'application/json') 
        ->withStatus(200);
    } catch (ErrorEnvioFormularioException $e) {
      $res->getBody()->write(json_encode([
        'error' => $e->getMessage()
      ]));
      return $res
        ->withHeader('Content-Type', 'application/json') 
        ->withStatus(400);
    } catch (CamposVaciosCrearException $e) {
      $res->getBody()->write(json_encode([
        'error' => $e->getMessage()
      ]));
      return $res
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(400); // Bad Request -> faltan datos
    } catch (\Exception $e) {
      $res->getBody()->write(json_encode([
        'error' => $e->getMessage()
      ]));
      return $res
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(500); 
    }
  }
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
      if (!$model->existeDato($id)) {
        throw new NoExisteEnTablaException;
      }
      if ($datos == null && $files == null) {
        throw new ErrorEnvioFormularioException();
      }
      if (empty($datos) && empty($files)) {
        throw new CamposVaciosActualizarException($tabla);
      }

      // Asignamos los datos al objeto 
      $metodos = '';
      foreach($datos as $atributo => $valor){
        $metodos = 'set' . ucfirst($atributo);
          // ucfirst() -> convierte el primer caracter de la cadena en mayuscula
        if (!empty($valor) && property_exists($model, $atributo)) {
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
    } catch (ErrorEnvioFormularioException $e){
      $res->getBody()->write(json_encode([
        'error' => $e->getMessage()
      ]));
      return $res
        ->withHeader('Content-Type', 'application/json') 
        ->withStatus(400);
    } catch (CamposVaciosActualizarException $e) {
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
  // --- Metodos Extas ---
  protected function validarDatos($datos, $files, $res) {
    $errores = [];
    // --- Validacion de datos ---
    $metodo = '';
    foreach ($datos as $key => $value) {
      if (empty($value)) {
        $metodo = 'set'. ucfirst($key);
        $errores[$key] = 'El campo '.$key.' es obligatorio';
      }
    }
    // Correccion de mensjae de idGenero y idPlataforma porque no me gusta que se vea idGenro o idPlataforma :D
    if (isset($errores['idGenero'])) {
      $errores['idGenero'] = 'El campo genero es obligatorio';
    }
    if (isset($errores['idPlataforma'])) {
      $errores['idPlataforma'] = 'El campo plataforma es obligatorio';
    }

    // --- Validacion de archivos ---
    if (isset($files['imagen'])) { // Si se envio el dato imagen
      $nroError = $files['imagen']->getError();
      if ($nroError !== UPLOAD_ERR_OK) { // Si se subio una imaben
        $errores['imagen'] = 'El campo imagen es obligatorio | NRO. Error: '. $nroError;
        if ($nroError !== UPLOAD_ERR_NO_FILE) { // Otro error
          $errores['imagen'] = 'Hubo un error al subir la imagen | NRO. Error: '. $nroError;  
        }
      }   
    }
    return $errores;
  }
}