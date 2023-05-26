<?php
namespace App\Models;

use App\Models\DB;

class Genero {
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

  // Agregar un genero
  public function save() { 
    // Obtenemos la conexion a la base de datos
    $db = new DB();
    $conn = $db->getConnection();

    // Creamos la consulta, para insertar un nuevo genero
    $sql = "INSERT INTO generos (nombre) VALUES (:nombre)";
    $query = $conn->prepare($sql);
      // query = stmt -> abreviatura utilizada para referirse a una declaración preparada (prepared statement) en PDO

    // Asignamos los valores de los parametros de la consulta
    $query->bindValue(':nombre', $this->nombre);
      // bindValue(':nombre', $this->nombre) -> vincula el valor de la variable $this->nombre al parametro :nombre
      // El uso de marcadores de posicion y la funcion bindValue proporciona una capa adicoinal de seguridad
      // Esto sirve para evitar la CONCATENACION directa de valores en la consutla SQL


    // Ejectutamos la consulta
    $query->execute();
    
    // Obtenemos el id del genero insertado ultimamente insetado y lo asignamos al atributo id del objeto
    $this->id = $conn->lastInsertId();
      // lastInsertId() -> devuelve el ID de la última fila o secuencia insertada
  }

  // Obtenemos todos los generos de la base de datos
  public static function all() {
      $db = new DB();
      $conn = $db->getConnection();
  
      $sql = "SELECT * FROM generos";
      $query = $conn->prepare($sql);
  
      $query->execute();
  
      $generos = $query->fetchAll(\PDO::FETCH_CLASS, Genero::class);
  
      return $generos;
  }
}


// try {
//   $db = new DB();
//   $db = $db->getConnection();
//   $stmt = $db->query($sql);
//   $friends = $stmt->fetchAll(PDO::FETCH_OBJ);

//   $db = null;
//   $res->getBody()->write(json_encode($friends));
//   return $res
//     ->withHeader('Content-Type', 'application/json')
//     ->withStatus(200);
// } catch (PDOException $e) {
//   $error = array(
//     "message" => $e->getMessage()
//   );

//   $res->getBody()->write(json_encode($error));
//   return $res
//     ->withHeader('Content-Type', 'application/json')
//     ->withStatus(500);
// }