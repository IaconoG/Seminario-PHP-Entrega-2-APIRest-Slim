<?php
namespace App\Models;

use App\Models\DB;

class Genero extends Model{
  private $id;
  private $nombre;

  public function getId() {
    return $this->id;
  }
  public function setId($id) {
    $this->id = $id;
  }
  
  public function getNombre() {
    return $this->nombre;
  }
  public function setNombre($nombre) {
    $this->nombre = $nombre;
  }

  // --- LISTAR ---
  public function buscarDatos() {
    return $this->buscar(null, 'generos');
  }
  // --- CREAR ---
  public function crearDato() { 
    return $this->crear('generos');
  }
  // --- ELIMINAR ---
  public function eliminarDato($id) {
    return $this->elimnar($id, 'generos');
  }
  // --- ACTUALIZAR --- 
  public function actualizarDato($id) {
    return $this->actualizar($id, 'generos');
  }
  // --- OTROS METODOS ---
  public function existeDato($id) { 
    return $this->existe($id, 'generos');
  }
  public function cargarDatos($datos, $nombres) {
    return $this->cargar($datos, $nombres, 'generos');
  }
  public function vaciarTabla() {
    return $this->vaciar('generos');
  }
  
}