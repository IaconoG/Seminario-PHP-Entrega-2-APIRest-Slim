<?php
namespace App\Models;

use App\Models\DB;
use PDO;

class Model{

  public function __constructor() {
    // constructor
  }

  protected function crear($tabla) {
    $db = new DB();
    $conn = $db->getConnection();

    // $sql = "INSERT INTO $tabla (nombre, imagen, tipo_imagen, descripcion, url, id_genero, id_plataforma) VALUES (:nombre, :imagen, :tipo_imagen, :descripcion, :url, :id_genero, :id_plataforma)";
    $sql = "INSERT INTO $tabla (";
    $parametros = [];

    $sql .= "nombre, ";
    $parametros[':nombre'] = $this->getNombre();

    if ($tabla == 'juegos') {
      $sql .= "imagen, ";
      $parametros[':imagen'] = $this->getImagen();

      $sql .= "tipo_imagen, ";
      $parametros[':tipo_imagen'] = $this->getTipoImagen();

      $sql .= "descripcion, ";
      $parametros[':descripcion'] = $this->getDescripcion();

      $sql .= "url, ";
      $parametros[':url'] = $this->getUrl();

      $sql .= "id_genero, ";
      $parametros[':id_genero'] = $this->getIdGenero();

      $sql .= "id_plataforma, ";
      $parametros[':id_plataforma'] = $this->getIdPlataforma();
    }
    $sql = rtrim($sql, ', '); 
    $sql .= ") VALUES (";

    foreach ($parametros as $param => $value) {
      $sql .= "$param, ";
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
      if ($this->getIdGenero() != null) {
        $sql .= "id_genero = :id_genero, ";
        $parametros[':id_genero'] = $this->getIdGenero();
      }
      if ($this->getIdPlataforma() != null) {
        $sql .= "id_plataforma = :id_plataforma, ";
        $parametros[':id_plataforma'] = $this->getIdPlataforma();
      }
    }
    
    $sql = rtrim($sql, ', '); 
    // rtrim -> Eliminar la última coma y espacio en blanco

    $sql .= " WHERE $tabla.id = :id ";
    $parametros[':id'] = $id;
    
    $stmt = $conn->prepare($sql);

    foreach ($parametros as $param => $value) {
      $stmt->bindValue($param, $value);
    }

    $stmt->execute();

    $numFilasActualizadas = $stmt->rowCount();
      // rowCount() -> Devuelve el número de filas afectadas por la última sentencia SQL (UPDATE)

    $db = null;
    $stmt = null;
    return ($numFilasActualizadas > 0) ? true : false;
  }
  protected function elimnar($id, $tabla) {
    $db = new DB();
    $conn = $db->getConnection();

    $sql = "DELETE FROM $tabla WHERE id = :id";
    $stmt = $conn->prepare($sql);

    $stmt->bindValue(':id', $id);

    $stmt->execute();

    $numFilasEliminadas = $stmt->rowCount();
      // rowCount() -> Devuelve el número de filas afectadas por la última sentencia SQL (DELETE)
    
    $db = null;
    $stmt = null;

    return ($numFilasEliminadas > 0) ? true : false;
  }
  protected function buscar($datos, $tabla) {
    $db = new DB();
    $conn = $db->getConnection();

    $sql = "SELECT * FROM $tabla";

    if ($datos != null) {
      $sql .= " WHERE ";
      
      $orden = $datos['orden']; // ASC or DESC
      unset($datos['orden']);

      foreach ($datos as $key => $value) {
        if ($value != null) {
          $sql .= "$key = :$key AND ";
        }
      }
      $sql = rtrim($sql, ' AND '); 

      if ($orden != null) {
        $sql .= " ORDER BY nombre $orden";
      }
    }

    $stmt = $conn->prepare($sql);
    
    if ($datos != null) {
      foreach ($datos as $key => $value) {
        if ($value != null) {
          $stmt->bindValue(":$key", $value);
        }
      }
    }

    $stmt->execute();

    $datos = $stmt->fetchALL(\PDO::FETCH_OBJ);
      // fetch() -> devuelve una fila de un conjunto de resultados
      // \PDO::FETCH_OBJ -> le dice a PDO que cree una instancia de la clase stdClass y que asigne los valores de las columnas en la fila a las propiedades con el mismo nombre

    $db = null; 
    $stmt = null; 
    return $datos;
  }

  // OTROS METODOS
  public function getMaxCharImgBD() {
    $db = new DB();
    $conn = $db->getConnection();

    $sql = "SELECT CHARACTER_MAXIMUM_LENGTH FROM information_schema.columns WHERE table_name = 'juegos' AND column_name = 'imagen'";
    $stmt = $conn->query($sql);   
    
    $columnaImagen = $stmt->fetch(PDO::FETCH_ASSOC);
    $maxChar = $columnaImagen['CHARACTER_MAXIMUM_LENGTH'];

    $db = null; 
    $stmt = null; 
    return $maxChar;
  }  
}