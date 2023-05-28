<?php
namespace App\Controllers; 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

use App\Models\Juego;
use App\Views\JuegoView;

class PlataformaController extends Controller{
  // --- METODO PARA MOSTRAR TODOS LAS PLATAFORMAS ---
  public function listarJuegos(Request $req, Response $res, $args) {
    return $this->obtenerTodos(new Plataforma(), 'plataformas', $res);
  }
  // --- METODO PARA OBTENER UNA UNICA PLATAFORMA ---
  public function obtenerJuego(Request $req, Response $res, $args) {
    return $this->obtenerUnico(new Plataforma(), 'plataformas', $args['id'], $res);
  }
  // --- METODO PARA CREAR UNA PLATAFORMA ---
  public function crearJuego(Request $req, Response $res, $args) { 
    return $this->crear(new Plataforma(), 'plataformas', $req->getParsedBody(), $req->getUploadedFiles(), $res);
  }
  // --- METODO PARA ELIMINAR UNA PLATAFORMA ---
  public function eliminarJuego(Request $req, Response $res, $args) {
    return $this->elimnar(new Plataforma(), 'plataformas', $args['id'], $res);
  }
  // --- METODO PARA ACTUALIZAR UNA PLATAFORMA ---
  public function actualizarJuego(Request $req, Response $res, $args) {
    return $this->actualizar(new Plataforma(), 'plataformas', $args['id'], $req->getParsedBody(), $req->getUploadedFiles(), $res);
  }
}