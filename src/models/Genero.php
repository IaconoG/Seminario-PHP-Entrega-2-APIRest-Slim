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
  public function obtenerTodos() {
    return $this->obtenerTodosDatos('generos');
  }
  // --- OBTENER UNICO ---
  public function obtenerUnico($id) {
    return $this->obtener($id, 'generos');
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
  
}

// // --- CREAR ---
// public function crear() { 
//   // Obtenemos la conexion a la base de datos
//   $db = new DB();
//   $conn = $db->getConnection();

//   // Creamos la consulta, para insertar un nuevo genero
//   $sql = "INSERT INTO generos (nombre) VALUES (:nombre)";
//   $stmt = $conn->prepare($sql);
//     // stmt -> abreviatura utilizada para referirse a una declaración preparada (prepared statement) en PDO

//   // Asignamos los valores de los parametros de la consulta
//   $stmt->bindValue(':nombre', $this->nombre);
//     // bindValue(':nombre', $this->nombre) -> vincula el valor de la variable $this->nombre al parametro :nombre
//     // El uso de marcadores de posicion y la funcion bindValue proporciona una capa adicoinal de seguridad
//     // Esto sirve para evitar la CONCATENACION directa de valores en la consutla SQL


//   // Ejectutamos la consulta
//   $stmt->execute();
  
//   // Obtenemos el id del genero insertado ultimamente insetado y lo asignamos al atributo id del objeto
//   $this->id = $conn->lastInsertId();
//     // lastInsertId() -> devuelve el ID de la última fila o secuencia insertada

//   $db = null;
//   $stmt = null;
// }
// // --- ELIMINAR ---
// public function eliminar($id) {
//   $db = new DB();
//   $conn = $db->getConnection();

//   $sql = "SELECT nombre FROM generos WHERE id = :id";
//   $stmt = $conn->prepare($sql);

//   $stmt->bindValue(':id', $id);

//   $stmt->execute();

//   $nombre = $stmt->fetchColumn();

//   $sql = "DELETE FROM generos WHERE id = :id";
//   $stmt = $conn->prepare($sql);

//   $stmt->bindValue(':id', $id);

//   $stmt->execute();

//   $db = null;
//   $stmt = null;

//   return $nombre;
// }
// // TODO: return Obtener el nombre del elemento eliminado 
// // --- ACTUALIZAR ---

// // --- OBTENER ---



// // --- OTROS METODOS ---
// // Obtenemos todos los generos de la base de datos
// public function obtenerTodos() {
//   return $this->obtenerTodosDatos('generos');
// }

// public function existeGenero($id) { 
//   $db = new DB();
//   $conn = $db->getConnection();

//   $sql = "SELECT 1 FROM generos WHERE id = :id LIMIT 1";
//   $stmt = $conn->prepare($sql);
//   $stmt->bindValue(':id', $id);
//   $stmt->execute();

//   $existe = $stmt->fetchColumn();
//     // 1 si el registro existe
//     // flase si no se encuentra ningun registro

//   $db = null;
//   $stmt = null;
  
//   return $existe !== false;
// }