<?php 
namespace App\Models;

use App\Models\DB;

class Juego{
  private $id;
  private $nombre;
  private $imagen;
  private $tipo_imagen;
  private $descripcion;
  private $url;
  private $id_genero;
  private $id_plataforma;

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

  // Metodos CRUD
  // --- CREAR ---
  public function crear() {
    $db = new DB();
    $conn = $db->getConnection();

    $sql = "INSERT INTO juegos (nombre, imagen, tipo_imagen, descripcion, url, id_genero, id_plataforma) VALUES (:nombre, :imagen, :tipo_imagen, :descripcion, :url, :id_genero, :id_plataforma)";
    $stmt = $conn->prepare($sql);
    
    $stmt->bindValue(':nombre', $this->nombre);
    $stmt->bindValue(':imagen', $this->imagen);
    $stmt->bindValue(':tipo_imagen', $this->tipo_imagen);
    $stmt->bindValue(':descripcion', $this->descripcion);
    $stmt->bindValue(':url', $this->url);
    $stmt->bindValue(':id_genero', $this->id_genero);
    $stmt->bindValue(':id_plataforma', $this->id_plataforma);
      // bindValue(':nombre', $this->nombre) -> vincula el valor de la variable $this->nombre al parametro :nombre
      // El uso de marcadores de posicion y la funcion bindValue proporciona una capa adicoinal de seguridad
      // Esto sirve para evitar la CONCATENACION directa de valores en la consutla SQL

    $stmt->execute();

    $this->id = $conn->lastInsertId();
      // lastInsertId() -> devuelve el ID de la ultima fila o secuencia insertada

    $db = null;  // Cierra la conexion
    $stmt = null; // Liberar recurso
  }
  // --- ELIMINAR ---
  public function eliminar($nombre) {
    $db = new DB();
    $conn = $db->getConnection();

    $sql = "DELETE FROM juSADASDegos WHERE nombre = :nombre";
    $stmt = $conn->prepare($sql);

    $stmt->bindValue(':nombre', $nombre); 

    $stmt->execute();

    $db = null;
    $stmt = null;      
  }
  // --- ACTUALIZAR ---
  public function actualizar($id) {
    $db = new DB();
    $conn = $db->getConnection();

    $sql = "UPDATE juegos SET ";
    $params = [];
    // nombre = :nombre, imagen = :imagen, tipo_imagen = :tipo_imagen, descripcion = :descripcion, url = :url WHERE id = :id";

    if ($this->nombre != null) {
      $sql .= "nombre = :nombre, ";
      $params[':nombre'] = $this->nombre;
    }
    if ($this->imagen != null) {
        $sql .= "imagen = :imagen, ";
        $params[':imagen'] = $this->imagen;
    }
    if ($this->tipo_imagen != null) {
        $sql .= "tipo_imagen = :tipo_imagen, ";
        $params[':tipo_imagen'] = $this->tipo_imagen;
    }
    if ($this->descripcion != null) {
        $sql .= "descripcion = :descripcion, ";
        $params[':descripcion'] = $this->descripcion;
    }
    if ($this->url != null) {
        $sql .= "url = :url, ";
        $params[':url'] = $this->url;
    }

    $sql = rtrim($sql, ', '); // Eliminar la última coma y espacio en blanco
    $sql .= " WHERE id = :id";
    $params[':id'] = $id;

    $stmt = $conn->prepare($sql);

    foreach ($params as $param => $value) {
      $stmt->bindValue($param, $value);
    }

    // $stmt->bindValue(':id_genero', $this->id_genero); // No permite modificar
    // $stmt->bindValue(':id_plataforma', $this->id_plataforma); // No permite modificar
    
    $stmt->execute();

    $db = null;
    $stmt = null;
  }
  // --- OBTENER ---
  public function obtener($nombre) {
    $db = new DB();
    $conn = $db->getConnection();

    $sql = "SELECT * FROM juegos WHERE nombre = :nombre";
    $stmt = $conn->prepare($sql);

    $stmt->bindValue(':nombre', $nombre);
    $stmt->execute();

    $juego = $stmt->fetch(\PDO::FETCH_OBJ);
      // fetch() -> devuelve una fila de un conjunto de resultados
      // \PDO::FETCH_OBJ -> le dice a PDO que cree una instancia de la clase stdClass y que asigne los valores de las columnas en la fila a las propiedades con el mismo nombre

    $db = null;
    $stmt = null;
    return $juego;
  }

  // Otros metodos
  public static function obtenerTodos($tabla) {
    $db = new DB();
    $conn = $db->getConnection();
    
    $sql = "SELECT * FROM juegos";
    $stmt = $conn->query($sql);

    $juegos = $stmt->fetchAll(\PDO::FETCH_OBJ);
      // fetchAll() -> devuelve un array que contiene todas las filas del conjunto de resultados
      // \PDO::FETCH_CLASS -> le dice a PDO que cree una instancia de la clase especificada y que asigne los valores de las columnas en la fila a las propiedades con el mismo nombre
      // Juego::class -> devuelve el nombre de la clase

    $db = null; 
    $stmt = null; 
    return $juegos;
  }
  public function existeJuego($id) {
    $db = new DB();
    $conn = $db->getConnection();

    $sql = "SELECT COUNT(*) FROM juegos WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    $count = $stmt->fetchColumn();
      // fetchColumn() -> devuelve una unica columna de la siguiente fila de un conjunto de resultados

    $db = null; 
    $stmt = null; 

    return $count > 0;
  }

}