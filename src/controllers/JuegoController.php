<?php
namespace App\Controllers; 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\Juego;

class JuegoController extends Controller{
  // --- METODO PARA OBTENER JUEGOS SEGUN PARAMETROS ---
  public function buscarJuegos(Request $req, Response $res, $args) {
    return $this->buscar(new Juego(), 'juegos', $req->getQueryParams(), $res);
  }
  // --- METODO PARA CREAR UN JUEGO ---
  public function crearJuego(Request $req, Response $res, $args) { 
    return $this->crear(new Juego(), 'juegos', $req, $res);
  }
  // --- METODO PARA ELIMINAR UN JUEGO ---
  public function eliminarJuego(Request $req, Response $res, $args) {
    if ($args['id'] == 'todos')
      return $this->vaciar(new Juego(), 'juegos', $res);
    return $this->elimnar(new Juego(), 'juegos', $args['id'], $res);
  }
  // --- METODO PARA ACTUALIZAR UN JUEGO ---
  public function actualizarJuego(Request $req, Response $res, $args) {
    return $this->actualizar(new Juego(), 'juegos', $args['id'], $req, $res);
  }
  // Otros metodos
  public function cargarDatos(Request $req, Response $res, $args) {
    return $this->cargar(new Juego(), 'juegos', $res);
  }
}
