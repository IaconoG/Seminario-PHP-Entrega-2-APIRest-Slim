<?php 
namespace App\Controllers; // -> esta linea determina la carpeta donde se encuentra el archivo

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Exceptions\NoExisteEnTablaException;
use App\Exceptions\CamposVaciosException;
use App\Exceptions\CamposCrearException;
use App\Exceptions\ErrorEnvioParametrosException;

class Controller {
  protected function crear($model, $tabla, $datos, $res) {
    try {
      $datos = json_decode($datos->getBody()->getContents(), true);
       // getBody() -> devuelve un flujo de datos (stream) que representa el contenido del cuerpo de la solicitud
        // getContents() -> devuelve todo el contenido del flujo de datos (stream) en una cadena
        // json_decode() -> convierte un string en un array asociativo

      // Validacions de los datos 
      if ($datos == null) {
        throw new ErrorEnvioParametrosException('No se proporcionaron parametros en la solicitud');
      }

      $errores = $this->validarCampos($datos, $model, $tabla);
      if (!empty($errores)) {
        throw new CamposCrearException($errores);
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
      $model->crearDato();
      $res->getBody()->write(json_encode([
        'mensaje' => 'Dato creado con exito'
      ]));
      return $res
        ->withHeader('Content-Type', 'application/json') 
        ->withStatus(200);
    } catch (ErrorEnvioParametrosException $e) {
      $res->getBody()->write(json_encode([
        'error' => $e->getMessage()
      ]));
      return $res
        ->withHeader('Content-Type', 'application/json') 
        ->withStatus(400);
    } catch (CamposCrearException $e) {
      $res->getBody()->write(json_encode([
        'error' => $e->getMessage()
      ]));
      return $res
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(400); 
    } catch (\PDOException $e) {
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
      $correctaEliminacion = $model->eliminarDato($id);

      if (!$correctaEliminacion) {
        throw new NoExisteEnTablaException('No se pudo eliminar el dato debido a que no existe en la tabla');
      }

      $res->getBody()->write(json_encode([
        'mensaje' => 'El dato fue eliminado con exito'
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
        ->withStatus(404);
    } catch (\PDOException $e) { //  Estas excepciones están relacionadas con errores específicos de la base de datos cuando se utiliza PDO
      if ($e->getCode() === '23000') {
        // getCode() -> devuelve el código de error de la excepción
        // 23000 -> error de integridad referencial
        $res->getBody()->write(json_encode([
          'error' => 'No se puede eliminar el dato debido a que esta siendo utilizado en otra tabla'
        ]));
        return $res
          ->withHeader('Content-Type', 'application/json')
          ->withStatus(409);
      }
      
      $res->getBody()->write(json_encode([
        'error PDO' => $e->getMessage() . ' ' . $e->getCode()
      ]));
      return $res
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(500);
    }
  }
  protected function actualizar($model, $tabla, $id, $datos, $res) {
    try {
      $datos = json_decode($datos->getBody()->getContents(), true);
      if (($datos == null)) {
        throw new ErrorEnvioParametrosException('No se proporcionaron parametros en la solicitud');
      }
      
      // Validacions de los campos vacios
      if ($this->validarCamposVacios($datos)) {
        throw new CamposVaciosException($tabla);
      }
      // Validacion solo para juegos
      if ($tabla == 'juegos') {
        // Eliminamos los campos vacios
        foreach ($datos as $atributo => $valor) { 
          if ($valor == "") {
            unset($datos[$atributo]);
          }
        }
        $errores = $this->validarCampos($datos, $model, $tabla); // Validamos los campos
        // Eliminamos los campos que no estan en el formulario
        foreach ($errores as $atributo => $valor) {
          if (!isset($datos[$atributo])) {
            unset($errores[$atributo]);
          }
        }
        if (!empty($errores)) {
          throw new CamposCrearException($errores);
        }
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
  
      // Actualizamos el dato
      $fueActualizado = $model->actualizarDato($id); 
      if (!$fueActualizado) { 
        throw new NoExisteEnTablaException('No se pudo actualizar el dato debido a que no existe en la tabla'); 
      }
      
      $res->getBody()->write(json_encode([
        'mensaje' => 'El dato fue actualizado con exito',
      ]));
      return $res
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);
    } catch (ErrorEnvioParametrosException $e){
      $res->getBody()->write(json_encode([
        'error' => $e->getMessage()
      ]));
      return $res
        ->withHeader('Content-Type', 'application/json') 
        ->withStatus(400);
    } catch (CamposVaciosException $e) {
      $res->getBody()->write(json_encode([
        'error' => $e->getMessage()
      ]));
      return $res
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(400);
    } catch (CamposCrearException $e) {
      $res->getBody()->write(json_encode([
        'error' => $e->getMessage()
      ]));
      return $res
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(400);
    } catch (NoExisteEnTablaException $e) {
        $res->getBody()->write(json_encode([
          'errror' => $e->getMessage()
        ]));
        return $res
          ->withHeader('Content-Type', 'application/json')
          ->withStatus(404);
    } catch (\PDOException $e) {
      $res->getBody()->write(json_encode([
        'error PDO' => $e->getMessage()
      ]));
      return $res
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(500);
    }
  }
  // ---
  protected function buscar($model, $tabla, $params, $res) {
    try {
      if ($tabla == "juegos") {
        if ($this->validarCamposVacios($params)) {
          $params = null;
        }
      }
      $datos = $model->buscarDatos($params);

      $res->getBody()->write(json_encode([
        'mensaje' => ($datos != null) ? 'Datos obtenidos con exito' : 'No se encontraron datos',
        'datos' => $datos,
      ]));
      return $res
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);
    } catch (\PDOException $e) {
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
  private function validarCampos($datos, $model, $tabla) {
    $errores = [];
    // --- Validacion de campos ---
    if (empty($datos['nombre'])) {
      $errores['nombre'] = 'El campo nombre es obligatorio';
    }
    
    if ($tabla == 'juegos') {
      // --- Validacion especifica de datos ---
        // Validacion de tama;o de imagen
      if (empty($datos['imagen'])) {
        $errores['imagen'] = 'El campo imagen es obligatorio';
      } else {
        $maxSizeImg = $this->maxCharImgBD($model);
        if (strlen($datos['imagen']) > $maxSizeImg) {
          $errores['imagen'] = 'La imagen debe tener un tamano menor a ' . $maxSizeImg . ' caracteres';
        }
          // Validacion de tipo imagen 
        if (empty($datos['tipoImagen'])) {
          $errores['tipoImagen'] = 'El campo tipo de imagen es obligatorio';
        } else {
          $tiposImage = array('image/jpeg', 'image/png', 'image/gif', 'image/bmp', 'image/webp', 'image/svg+xml');
          if (!in_array($datos['tipoImagen'], $tiposImage)) {
            $errores['tipoImagen'] = 'El tipo de imagen debe ser de algun tipo permitido (image/jpeg, image/png, image/gif, image/bmp, image/webp, image/svg+xml)';
          }
        }
      }

        // Validacion de descripcion
      if (empty($datos['descripcion'])) {
        $errores['descripcion'] = 'El campo descripcion es obligatorio';
      } else {
        $maxSizeDesc = 255;
        if (strlen($datos['descripcion']) > $maxSizeDesc) {
          $errores['descripcion'] = 'La descripcion no puede tener mas de ' . $maxSizeDesc . ' caracteres';
        }
      }
        // Validacion de url
      if (empty($datos['url'])) {
        $errores['url'] = 'El campo url es obligatorio';
      } else {
        $maxSizeDesc = 80;
        if (strlen($datos['url']) > $maxSizeDesc) {
          $errores['url'] = 'La url no puede tener mas de ' . $maxSizeDesc . ' caracteres';
        }
      }
        // Validacion de genero
      if (empty($datos['idGenero'])) {
        $errores['idGenero'] = 'El campo genero es obligatorio';
      } 
        // Validacion de plataforma
      if (empty($datos['idPlataforma'])) {
        $errores['idPlataforma'] = 'El campo plataforma es obligatorio';
      } 
    }
    return $errores;
  }
  private function validarCamposVacios($datos){
    $allCamposVacios = true;
    foreach($datos as $dato) {
      if (!empty($dato)) {
        $allCamposVacios = false;
        break;
      }
    }
    return $allCamposVacios;
  }
  private function maxCharImgBD($model) {
    return $model->getMaxCharImgBD();
  }
  // Otros metodos
  protected function cargar($model, $tabla, $res) {
    try {
      $rutaJSON = __DIR__ . "/../../src/data/$tabla.json";
      $datosJSON = json_decode(file_get_contents($rutaJSON), true)[$tabla];
      $datos = [];
      foreach ($datosJSON as $dato) {
        $datos[] = $dato['nombre'];
      }
      
      $yaCargado = $model->cargarDatos($datos, $tabla);
      $msg = ($yaCargado) ? 'Los datos ya estaban cargados en la tabla '. $tabla : 'Los datos se cargaron correctamente en la tabla '.$tabla;
      $res->getBody()->write(json_encode([
        'mensaje' => $msg,
        'yaCargado' => $yaCargado
      ]));
      return $res
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);
    } catch (\PDOException $e) {
      $res->getBody()->write(json_encode([
        'error' => $e->getMessage()
      ]));
      return $res
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(500);
    }
  }
  protected function vaciar($model, $tabla, $res) {
    try {
      $estabaVacio = $model->vaciarTabla($tabla);
      $res->getBody()->write(json_encode([
        'mensaje' => ($estabaVacio) ? "La tabla $tabla ya estaba vacia" : "La tabla $tabla se vacio correctamente"
      ]));
      return $res
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);
    } catch (\PDOException $e) {
      $res->getBody()->write(json_encode([
        'error' => $e->getMessage()
      ]));
      return $res
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(500);
    }
  }
}