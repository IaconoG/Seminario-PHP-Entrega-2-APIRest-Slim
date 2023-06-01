<?php
namespace App\Controllers; 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Exceptions\ErrorEnvioFormularioException;
use App\Exceptions\CamposVaciosActualizarException;
use App\Exceptions\NoExisteEnTablaException;

use App\Models\Juego;
use App\Views\JuegoView;

class JuegoController extends Controller{
  // --- METODO PARA MOSTRAR TODOS LOS JUEGOS ---
  public function listarJuegos(Request $req, Response $res, $args) {
    return $this->obtenerTodos(new Juego(), 'juegos', $res);
  }
  // --- METODO PARA OBTENER JUEGOS SEGUN PARAMETROS ---
  public function buscarJuegos(Request $req, Response $res, $args) {
    try {
      $parametros = $req->getQueryParams();

      if ($parametros == null) {
        throw new ErrorEnvioFormularioException();
      }
      if ($parametros['nombre'] == null && $parametros['id_genero'] == null && $parametros['id_plataforma'] == null && $parametros['orden'] == null) { // Esto se puede mejorar :/
        throw new CamposVaciosActualizarException('juegos');
      }

      $juegos = Juego::buscarJuegos($parametros);
      if ($juego == null) {
        throw new NoExisteEnTablaException('juegos');
      }

      $res->getBody()->write(json_encode([
        'mensaje' => 'La busqueda se realizo con exito',
        'juegos' => $juegos
      ]));
      return $res
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);

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
    } catch (CamposVaciosActualizarException $e) {
      $res ->getBody()->write(json_encode([
        'error' => $e->getMessage()
      ]));
      return $res
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(400);
    } catch (NoExisteEnTablaException $e){
      $res->getBody()->write(json_encode([
        'error' => $e->getMessage()
      ]));
      return $res
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(404);
    } catch (\Exception $e){
      $res->getBody()->write(json_encode([
        'error' => $e->getMessage()
      ]));
      return $res
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(500);
    }
  }
  // --- METODO PARA CREAR UN JUEGO ---
  public function crearJuego(Request $req, Response $res, $args) { 
    return $this->crear(new Juego(), 'juegos', $req, $req->getUploadedFiles(), $res); // FIXME: Si se envia por json deberia elimnar el req->getUploadedFiles()
  }
  // --- METODO PARA ELIMINAR UN JUEGO ---
  public function eliminarJuego(Request $req, Response $res, $args) {
    return $this->elimnar(new Juego(), 'juegos', $args['id'], $res);
  }
  // --- METODO PARA ACTUALIZAR UN JUEGO ---
  public function actualizarJuego(Request $req, Response $res, $args) {
    return $this->actualizar(new Juego(), 'juegos', $args['id'], $req, $req->getUploadedFiles(), $res); // FIXME: Si se envia por json deberia elimnar el req->getUploadedFiles()
  }
}

// class JuegoController {
//   // --- Metodo para listar todos los juegos ---
//   public function listarJuegos(Request $req, Response $res, array $args) {
//     try {
//       $juegos = Juego::obtenerTodos('juegos');
//       if (empty($juegos)) {
//         $res->getBody()->write(json_encode([
//           'error' => 'No existen juegos en la base de datos'
//         ]));
//         return $res
//           ->withHeader('Content-Type', 'application/json')
//           ->withStatus(404);
//       }

//       $res->getBody()->write(json_encode($juegos)); // Aca deverimamos usar la vista
//       return $res
//         ->withHeader('Content-Type', 'application/json')
//         ->withStatus(200);
//     } catch (\Exception $e) {
//       $res->getBody()->write(json_encode([
//         'error'=> $e->getMessage()
//       ]));
//       return $res 
//         ->withHeader('Content-Type', 'application/json')
//         ->withStatus(500);
//     } 
//   }
  
//   // --- Metodo para crear un juego ---
//   public function crearJuego(Request $req, Response $res) {
//     $data = $req->getParsedBody();
//       // getParseBody() -> devuelve un array asociativo con los datos del body
//     $files = $req->getUploadedFiles();
//       // getUploadedFiles() -> devuelve un array asociativo con los archivos subidos
//     $errores = [];
//     $juego = new Juego();

//     // === Validacions de los datos === 
//       // Validamos el nombre
//     if (empty($data['nombre'])) {
//       $errores['nombre'] = 'El nombre es obligatorio';
//     } else {
//       $juego->setNombre($data['nombre']);
//     }
//       // Validamos la imagen
//     if (empty($files['imagen'])) {
//       $errores['imagen'] = 'La imagen es obligatoria';
//     } else {
//       $imagen = $files['imagen'];
//       if ($imagen->getError() === UPLOAD_ERR_OK) {
//         $tipoImagen = $imagen->getClientMediaType(); 
//           // getClientMediaType() -> devuelve el tipo de media del archivo
//         $tempFile = $imagen->getStream()->getMetadata('uri');
//           // getStream() -> devuelve un flujo de datos (stream) que representa el contenido del archivo cargado
//           // getMetadata('uri') -> devuelve la ubicacion del archivo temporal
//         $contenImg = file_get_contents($tempFile);
//         $nombreImg = base64_encode($contenImg);

//         $juego->setImagen(substr($nombreImg,0,5));
//         $juego->setTipoImagen($tipoImagen);
//       } else {
//         $errores['imagen'] = 'Hubo un error al subir la imagen '. $imagen->getError();
//       }
//     }
//       // Validamos la descripcion
//     if (empty($data['descripcion'])) {
//       $errores['descripcion'] = 'La descripcion es obligatoria';
//     } else {
//       $juego->setDescripcion($data['descripcion']);
//     }
//       // Validamos la url
//     if (empty($data['url'])) {
//       $errores['url'] = 'La url es obligatoria';
//     } else {
//       $juego->setUrl($data['url']);
//     }
//       // Validamos el genero
//     if (empty($data['id_genero'])) {
//       $errores['id_genero'] = 'El genero es obligatorio';
//     } else {
//       $juego->setIdGenero($data['id_genero']);
//     }
//       // Validamos la plataforma
//     if (empty($data['id_plataforma'])) {
//       $errores['id_plataforma'] = 'La plataforma es obligatorio';
//     } else {
//       $juego->setIdPlataforma($data['id_plataforma']);
//     }

//     if (!empty($errores)) {
//       $res->getBody()->write(json_encode([
//         'error' => $errores
//       ]));
//       return $res
//         ->withHeader('Content-Type', 'application/json')
//         ->withStatus(400); // Bad Request -> faltan datos
//     } 

//     $res->getBody()->write(json_encode([
//       'errores' => $errores,
//       'nombre' => $juego->getNombre(),
//       'imagen' => substr($juego->getImagen(), 0, 5),
//       'tipo_imagen' => $juego->getTipoImagen(),
//       'descripcion' => $juego->getDescripcion(),
//       'url' => $juego->getUrl(),
//       'id_genero' => $juego->getIdGenero(),
//       'id_plataforma' => $juego->getIdPlataforma()
//     ]));

//     // $juego->crear();
//     // $res->getBody()->write(json_encode([
//     //   'mensaje' => 'Juego creado con exito'
//     // ]));
//     return $res
//       ->withHeader('Content-Type', 'application/json') 
//       ->withStatus(200);
//   }

//   // --- Metodo para eliminar un juego ---
//   public function eliminarJuego(Request $req, Response $res, array $args) {
    
//     $nombre = $args['nombre'];
    
//     try {
//       // Validamos el parametro nombre
//       if (empty($nombre)) {
//         throw new NombreVacioException();
//       }
//       $juego = new Juego();

//       // Verificar si el juego existe en la base de datos
//       if (!$juego->existeJuego($nombre)) {
//         throw new NoExisteEnTablaException();
//       }

//       $juego->eliminar($nombre);
//       $res->getBody()->write(json_encode([
//         'message' => 'Juego eliminado con exito'
//       ]));
//       return $res
//         ->withHeader('Content-Type', 'application/json')
//         ->withStatus(200);

//     } catch (NombreJuegoVacioException $e) {
//       $res->getBody()->write(json_encode([
//         'error' => $e->getMessage()
//       ]));
//       return $res
//         ->withHeader('Content-Type', 'application/json')
//         ->withStatus(400);
//     } catch (NoExisteEnTablaException $e) {
//       $res->getBody()->write(json_encode([
//         'error' => $e->getMessage()
//       ]));
//       return $res
//         ->withHeader('Content-Type', 'application/json')
//         ->withStatus(404);
//     } catch (\Exception $e) {
//       $res->getBody()->write(json_encode([
//         'error' => $e->getMessage()
//       ]));
//       return $res
//         ->withHeader('Content-Type', 'application/json')
//         ->withStatus(500);
//     }
//   }
//   // --- Metodo para actualizar un juego ---
//   public function actualizarJuego(Request $req, Response $res, array $args) {
//     $id = $args['id'];
//     try {
//       if (empty($id)) {
//         throw new NombreVacioException();
//       }
      
//       $juego = new Juego();
      
//       if (!$juego->existeJuego($id)) {
//         throw new NoExisteEnTablaException();
//       }
      
//       $datos = $req->getParsedBody();

//       if (empty($datos)) {
//         throw new DatosJuegoVaciosException();
//       }

//       // Validamos los datos
//       $metods = '';
//       foreach($datos as $atributo => $valor){
//         $metodo = 'set' . ucfirst($atributo);
//           // ucfirst() -> convierte el primer caracter de la cadena en mayuscula
//         if (isset($datos[$atributo]) && !empty($datos[$atributo]) && property_exists($juego, $atributo)) {
//           $juego->$metodo($valor);
//             // set{$atributo}() -> "anida el valor de la variable $atributo dentro del nombre del metodo set"
//             // ej. estariamo llamando al metodo setNombre()
//         }
//       }

//       $juego->actualizar($id);

//       $res->getBody()->write(json_encode([
//         'message' => 'Juego actualizado con exito'
//       ]));
//       return $res
//         ->withHeader('Content-Type', 'application/json')
//         ->withStatus(200);

//     } catch (NombreVacioException $e) {
//       $res->getBody()->write(json_encode([
//         'error' => $e->getMessage()
//       ]));
//       return $res
//         ->withHeader('Content-Type', 'application/json')
//         ->withStatus(400);
//     } catch (NoExisteEnTablaException $e) {
//       $res->getBody()->write(json_encode([
//         'error' => $e->getMessage()
//       ]));
//       return $res
//         ->withHeader('Content-Type', 'application/json')
//         ->withStatus(404);
//     } catch (DatosJuegoVaciosException $e) {
//       $res->getBody()->write(json_encode([
//         'error' => $e->getMessage()
//       ]));
//       return $res
//         ->withHeader('Content-Type', 'application/json')
//         ->withStatus(400);
//     } catch (\Exception $e) {
//       $res->getBody()->write(json_encode([
//         'error' =>  $e->getMessage()
//       ]));
//       return $res
//         ->withHeader('Content-Type', 'application/json')
//         ->withStatus(500);
//     }
//   }
//   // --- Metodo para obtener un juego ---
//   public function obtenerJuego(Request $req, Response $res, array $args) {
//     $nombre = $args['nombre'];
//     $juego = new Juego();

//     try {
//       if (empty($nombre)) {
//         throw new NombreVacioException();
//       }
//       if (!$juego->existeJuego($nombre)) {
//         throw new NoExisteEnTablaException('juego');
//       }

//       $juego = $juego->obtener($nombre);

//       $res->getBody()->write(json_encode([
//         'juego' => $juego
//       ]));
//       return $res
//         ->withHeader('Content-Type', 'application/json')
//         ->withStatus(200);

//     } catch (NombreVacioException $e) {
//       $res->getBody()->write(json_encode([
//         'error' => $e->getMessage()
//       ]));
//       return $res
//         ->withHeader('Content-Type', 'application/json')
//         ->withStatus(400);
//     } catch (NoExisteEnTablaException $e) {
//       $res->getBody()->write(json_encode([
//         'error' => $e->getMessage()
//       ]));
//       return $res
//         ->withHeader('Content-Type', 'application/json')
//         ->withStatus(404);
//     } catch (\Exception $e) {
//       $res->getBody()->write(json_encode([
//         'error' => $e->getMessage()
//       ]));
//       return $res
//         ->withHeader('Content-Type', 'application/json')
//         ->withStatus(500);
//     }
//   }
// }
