<?php
namespace App\Models;

use App\Models\DB;

class Plataforma extends Model{
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
    return $this->buscar(null, 'plataformas');
  }
  // --- CREAR ---
  public function crearDato() { 
    return $this->crear('plataformas');
  }
  // --- ELIMINAR ---
  public function eliminarDato($id) {
    return $this->elimnar($id, 'plataformas');
  }
  // --- ACTUALIZAR --- 
  public function actualizarDato($id) {
    return $this->actualizar($id, 'plataformas');
  }
  // --- OTROS METODOS ---
  public function existeDato($id) { 
    return $this->existe($id, 'plataformas');
  }
  public function cargarDatos($datos, $nombres) {
    return $this->cargar($datos, $nombres, 'plataformas');
  }
  public function vaciarTabla() {
    return $this->vaciar('plataformas');
  }
  
}
