<?php
namespace App\Controllers; 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

use App\Models\Juego;
use App\Views\JuegoView;

class PlataformaController extends Controller{
  // --- METODO PARA MOSTRAR TODOS LAS PLATAFORMAS ---
  public function listarPlataformas(Request $req, Response $res, $args) {
    return $this->obtenerTodos(new Plataforma(), 'plataformas', $res);
  }
  // --- METODO PARA OBTENER UNA UNICA PLATAFORMA ---
  public function obtenerPlataforma(Request $req, Response $res, $args) {
    return $this->obtenerUnico(new Plataforma(), 'plataformas', $args['id'], $res);
  }
  // --- METODO PARA CREAR UNA PLATAFORMA ---
  public function crearPlataforma(Request $req, Response $res, $args) { 
    return $this->crear(new Plataforma(), 'plataformas', $req, null, $res);
  }
  // --- METODO PARA ELIMINAR UNA PLATAFORMA ---
  public function eliminarPlataforma(Request $req, Response $res, $args) {
    return $this->elimnar(new Plataforma(), 'plataformas', $args['id'], $res);
  }
  // --- METODO PARA ACTUALIZAR UNA PLATAFORMA ---
  public function actualizarPlataforma(Request $req, Response $res, $args) {
    return $this->actualizar(new Plataforma(), 'plataformas', $args['id'], $req, null, $res);
  }
}