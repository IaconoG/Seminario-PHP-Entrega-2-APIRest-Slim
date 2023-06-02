<?php
namespace App\Controllers; 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\Plataforma;

class PlataformaController extends Controller{
  // --- METODO PARA MOSTRAR TODOS LAS PLATAFORMAS ---
  public function listarPlataformas(Request $req, Response $res, $args) {
    return $this->buscar(new Plataforma(), 'plataformas', null, $res);
  }
  // --- METODO PARA CREAR UNA PLATAFORMA ---
  public function crearPlataforma(Request $req, Response $res, $args) { 
    return $this->crear(new Plataforma(), 'plataformas', $req, $res);
  }
  // --- METODO PARA ELIMINAR UNA PLATAFORMA ---
  public function eliminarPlataforma(Request $req, Response $res, $args) {
    return $this->elimnar(new Plataforma(), 'plataformas', $args['id'], $res);
  }
  // --- METODO PARA ACTUALIZAR UNA PLATAFORMA ---
  public function actualizarPlataforma(Request $req, Response $res, $args) {
    return $this->actualizar(new Plataforma(), 'plataformas', $args['id'], $req, $res);
  }
}