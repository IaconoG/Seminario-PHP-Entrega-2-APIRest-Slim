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
  protected function crear($model, $tabla, $datos, $files, $res) { // FIXME: Si se envia por json deberia elimnar el files
    try {
      // $datos = $datos->getParsedBody(); // getParsedBody() -> devuelve los datos del formulario como un array asociativo | // FIXME: En caso de que no se envie json 
      $datos = json_decode($datos->getBody()->getContents(), true);
       // getBody() -> devuelve un flujo de datos (stream) que representa el contenido del cuerpo de la solicitud
        // getContents() -> devuelve todo el contenido del flujo de datos (stream) en una cadena
        // json_decode() -> convierte un string en un array asociativo

      // Validacions de los datos 
      if ($datos == null && $files == null) {
        throw new ErrorEnvioFormularioException();
      }

      $errores = $this->validarDatos($datos, $files);
      if (!empty($errores)) {
        throw new CamposVaciosCrearException($errores);
      }

      // Asignamos los datos al objeto
      /*
        - Asignamos los datos del formulario a las propiedades del objeto
        - Contruyendo el nombre del metodo setter de cada propiedad
        - Si el objeto tiene un metodo con ese nombre, se llama al metodo con el valor del elemento correspondiente en el array $datos como argumento
      */
      $metodos = '';
      foreach($datos as $atributo => $valor) {
        $metodos = 'set' . ucfirst($atributo);
        if (method_exists($model, $metodos)) {
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
      $model->crearDato();
      $res->getBody()->write(json_encode([
        'mensaje' => 'Dato almacenado con exito'
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
  protected function actualizar($model, $tabla, $id, $datos, $files, $res) { // FIXME: Si se envia por json deberia elimnar el files
    try {
      $datos = json_decode($datos->getBody()->getContents(), true); // Convierte el json en un array asociativo
      unset($datos['_method']); // Ya no necesitamos este dato (creo) :D 
      /*
        Si no se envia nada en el formulario
        Si el formulario no envia nada, pero envia el _method que es solo para que el post se comporte como un patch 
        count($datos) -> devuelve la cantidad de elementos de un array
      */
      if (($datos == null && $files == null)) {
        throw new ErrorEnvioFormularioException();
      }
      if (!$model->existeDato($id)) {
        throw new NoExisteEnTablaException($tabla);
      }
      // Validacions de los datos
      $allCamposVacios = true;
      foreach($datos as $dato) {
        if (!empty($dato)) {
          $allCamposVacios = false;
          break;
        }
      }
      if ($allCamposVacios) {
        if (!$files == null) {
          if (!empty($files['imagen']->getClientFilename())) {
            $allCamposVacios = false;
          }
        }
      }
      if ($allCamposVacios) {
        throw new CamposVaciosActualizarException($tabla);
      }
      // Asignamos los datos al objeto 
      /*
        - Asignamos los datos del formulario a las propiedades del objeto siempre y cuand el campo no este vacio
        - Contruyendo el nombre del metodo setter de cada propiedad
        - Si el objeto posee un metodo con ese nombre, se llama al metodo con el valor del elemento correspondiente en el array $datos como argumento
      */
      $metodos = '';
      foreach($datos as $atributo => $valor){
        if (!empty($valor)) {
          $metodos = 'set' . ucfirst($atributo);
             // ucfirst() -> convierte el primer caracter de la cadena en mayuscula
             // set{$atributo}() -> "anida el valor de la variable $atributo dentro del nombre del metodo set"
          if (method_exists($model, $metodos)) {
            $model->$metodos($valor);
            // ej. estariamo llamando al metodo setNombre()
          }
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
      $datoActualizado = $model->actualizarDato($id);
      
      $res->getBody()->write(json_encode([
        'mensaje' => 'El dato ' . $datoActualizado->nombre .' fue actualizado con exito'
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
  protected function validarDatos($datos, $files) {
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