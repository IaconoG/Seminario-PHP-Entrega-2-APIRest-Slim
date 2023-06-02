<?php
namespace App\Controllers; // -> esta linea determina la carpeta donde se encuentra el archivo

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\Genero;

class GeneroController extends Controller{
  // --- METODO PARA MOSTRAR TODOS LOS GENEROS ---
  public function listarGeneros(Request $req, Response $res, $args) {
    return $this->buscar(new Genero(), 'generos', null, $res);
  }
  // --- METODO PARA CREAR UN GENEROS ---
  public function crearGenero(Request $req, Response $res, $args) { 
    return $this->crear(new Genero(), 'generos', $req, $res);
  }
  // --- METODO PARA ELIMINAR UN GENEROS ---
  public function eliminarGenero(Request $req, Response $res, $args) {
    return $this->elimnar(new Genero(), 'generos', $args['id'], $res);
  }
  // --- METODO PARA ACTUALIZAR UN GENEROS ---
  public function actualizarGenero(Request $req, Response $res, $args) {
    return $this->actualizar(new Genero(), 'generos', $args['id'],  $req, $res);
  }
}
