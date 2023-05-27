<?php
namespace App\Models;

use App\Models\DB;

abstract class Model{
  abstract protected function crear();
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
    $params = [];

    if ($this->getNombre() != null) {
      $sql .= "nombre = :nombre, ";
      $params[':nombre'] = $this->getNombre();
    }
    if ($tabla == 'juegos') {
      if ($this->getImagen() != null) {
          $sql .= "imagen = :imagen, ";
          $params[':imagen'] = $this->getImagen();
      }
      if ($this->getTipoImagen() != null) {
          $sql .= "tipo_imagen = :tipo_imagen, ";
          $params[':tipo_imagen'] = $this->getTipoImagen();
      }
      if ($this->getDescripcion() != null) {
          $sql .= "descripcion = :descripcion, ";
          $params[':descripcion'] = $this->getDescripcion();
      }
      if ($this->getUrl() != null) {
          $sql .= "url = :url, ";
          $params[':url'] = $this->getUrl();
      }
    }

    $sql = rtrim($sql, ', '); // Eliminar la última coma y espacio en blanco
      // rtrim -> 
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
  //---
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