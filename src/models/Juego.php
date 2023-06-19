<?php 
namespace App\Models;

use App\Models\DB;

class Juego extends Model{
  private $id;
  private $nombre;
  private $imagen;
  private $tipo_imagen;
  private $descripcion;
  private $url;
  private $id_genero;
  private $id_plataforma;

  public function __constructor() {
    parent::__constructor(); //llama al constructor de la clase padre
  }

  // getters y setters
  public function getId(){
    return $this->id;
  }
  public function setId($id){
    $this->id = $id;
  }
  public function getNombre(){
    return $this->nombre;
  }
  public function setNombre($nombre) {
    $this->nombre = $nombre;
  }
  public function getImagen() {
    return $this->imagen;
  }
  public function setImagen($imagen) {
    $this->imagen = $imagen;
  }
  public function getTipoImagen() {
    return $this->tipo_imagen;
  }
  public function setTipoImagen($tipo_imagen) {
    $this->tipo_imagen = $tipo_imagen;
  }
  public function getDescripcion() {
    return $this->descripcion;
  }
  public function setDescripcion($descripcion) {
    $this->descripcion = $descripcion;
  }
  public function getUrl() {
    return $this->url;
  }
  public function setUrl($url) {
    $this->url = $url;
  }
  public function getIdGenero() {
    return $this->id_genero;
  }
  public function setIdGenero($id_genero) {
    $this->id_genero = $id_genero;
  }
  public function getIdPlataforma() {
    return $this->id_plataforma;
  }
  public function setIdPlataforma($id_plataforma) {
    $this->id_plataforma = $id_plataforma;
  }

  // --- LISTAR \ BUSCAR ---
  public function buscarDatos($datos) {
    return $this->buscar($datos, 'juegos');
  }
  // --- CREAR ---
  public function crearDato() { 
    return $this->crear('juegos');
  }
  // --- ELIMINAR ---
  public function eliminarDato($id) {
    return $this->elimnar($id, 'juegos');
  }
  // --- ACTUALIZAR --- 
  public function actualizarDato($id) {
    return $this->actualizar($id, 'juegos');
  }
  // --- OTROS METODOS ---
  public function existeDato($id) { 
    return $this->existe($id, 'juegos');
  }
  public function cargarDatos($datos) {
    return $this->cargar($datos, 'juegos');
  }
  public function vaciarTabla() {
    return $this->vaciar('juegos');
  }
}