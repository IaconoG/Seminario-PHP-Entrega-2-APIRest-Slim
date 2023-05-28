<?php
namespace App\Models;

use App\Models\DB;

class Model{
  protected function crear($tabla) {
    $db = new DB();
    $conn = $db->getConnection();

    // $sql = "INSERT INTO $tabla (nombre, imagen, tipo_imagen, descripcion, url, id_genero, id_plataforma) VALUES (:nombre, :imagen, :tipo_imagen, :descripcion, :url, :id_genero, :id_plataforma)";
    $sql = "INSERT INTO $tabla (";
    $parametros = [];
    if ($this->getNombre() != null) {
      $sql .= "nombre, ";
      $parametros[':nombre'] = $this->getNombre();
    }
    if ($tabla == 'juegos') {
      if ($this->getImagen() != null) {
          $sql .= "imagen, ";
          $parametros[':imagen'] = $this->getImagen();
      }
      if ($this->getTipoImagen() != null) {
          $sql .= "tipo_imagen, ";
          $parametros[':tipo_imagen'] = $this->getTipoImagen();
      }
      if ($this->getDescripcion() != null) {
          $sql .= "descripcion, ";
          $parametros[':descripcion'] = $this->getDescripcion();
      }
      if ($this->getUrl() != null) {
          $sql .= "url, ";
          $parametros[':url'] = $this->getUrl();
      }
    }
    $sql = rtrim($sql, ', '); 
    $sql .= ") VALUES (";

    foreach ($parametros as $param => $value) {
      $sql .= ":$param, ";
    }
    
    $sql = rtrim($sql, ', ');
    $sql .= ")";

    $stmt = $conn->prepare($sql);
    foreach ($parametros as $param => $value) {
      $stmt->bindValue($param, $value);
    }

    $stmt->execute();

    $db = null;
    $stmt = null;
  }
  protected function obtener($id, $tabla) {
    $db = new DB();
    $conn = $db->getConnection();

    $sql = "SELECT * FROM $tabla WHERE id = :id";
    $stmt = $conn->prepare($sql);

    $stmt->bindValue(':id', $id);
    $stmt->execute();

    $juego = $stmt->fetch(\PDO::FETCH_OBJ);
      // fetch() -> devuelve una fila de un conjunto de resultados
      // \PDO::FETCH_OBJ -> le dice a PDO que cree una instancia de la clase stdClass y que asigne los valores de las columnas en la fila a las propiedades con el mismo nombre

    $db = null;
    $stmt = null;
    return $juego;  
  }
  protected function actualizar($id, $tabla) {
    $db = new DB();
    $conn = $db->getConnection();

    $sql = "UPDATE $tabla SET ";
    $parametros = [];

    if ($this->getNombre() != null) {
      $sql .= "nombre = :nombre, ";
      $parametros[':nombre'] = $this->getNombre();
    }
    if ($tabla == 'juegos') {
      if ($this->getImagen() != null) {
          $sql .= "imagen = :imagen, ";
          $parametros[':imagen'] = $this->getImagen();
      }
      if ($this->getTipoImagen() != null) {
          $sql .= "tipo_imagen = :tipo_imagen, ";
          $parametros[':tipo_imagen'] = $this->getTipoImagen();
      }
      if ($this->getDescripcion() != null) {
          $sql .= "descripcion = :descripcion, ";
          $parametros[':descripcion'] = $this->getDescripcion();
      }
      if ($this->getUrl() != null) {
          $sql .= "url = :url, ";
          $parametros[':url'] = $this->getUrl();
      }
    }

    $sql = rtrim($sql, ', '); 
      // rtrim -> Eliminar la última coma y espacio en blanco
    $sql .= " WHERE id = :id";
    $parametros[':id'] = $id;

    $stmt = $conn->prepare($sql);

    foreach ($parametros as $param => $value) {
      $stmt->bindValue($param, $value);
    }

    // $stmt->bindValue(':id_genero', $this->id_genero); // No permite modificar
    // $stmt->bindValue(':id_plataforma', $this->id_plataforma); // No permite modificar

    $stmt->execute();

    $db = null;
    $stmt = null;
  }
  protected function elimnar($id, $tabla) {
    $db = new DB();
    $conn = $db->getConnection();

    $sql = "SELECT nombre FROM $tabla WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    $nombre = $stmt->fetchColumn();

    $sql = "DELETE FROM $tabla WHERE id = :id";
    $stmt = $conn->prepare($sql);

    $stmt->bindValue(':id', $id);

    $stmt->execute();

    $db = null;
    $stmt = null;

    return $nombre;
  }
  // ---
  public function obtenerTodosDatos($tabla) {
    $db = new DB();
    $conn = $db->getConnection();

    $sql = "SELECT * FROM $tabla";
    $stmt = $conn->query($sql);   
    $datos = $stmt->fetchAll(\PDO::FETCH_OBJ);

    $db = null; 
    $stmt = null; 
    return $datos;
  }

  // OTROS METODOS
  public function existe($id, $tabla) { 
    $db = new DB();
    $conn = $db->getConnection();

    $sql = "SELECT 1 FROM $tabla WHERE id = :id LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    $existe = $stmt->fetchColumn();
      // 1 si el registro existe
      // flase si no se encuentra ningun registro

    $db = null;
    $stmt = null;
    
    return $existe !== false;
  }
}