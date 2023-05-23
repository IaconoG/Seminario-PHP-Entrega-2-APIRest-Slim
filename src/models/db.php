<?php 
namespace APP\Models;

use PDO;
use PDOException;

class DB {
  // private -> solo puede ser accedido o modificado desde dentro de la misma clase.
  // protected -> puede ser accedido desde dentro de la misma clase y también desde dentro de clases hijas que hereden de ella. No puede ser accedido ni modificado fuera de la clase.
  // pbulic -> puede ser accedido y modificado desde cualquier parte del código.
  private $host;
  private $dbname;
  private $username;
  private $password;

  protected $conn;

  public function __construct() {
    $this->host = 'localhost';
    $this->dbname = 'test';  // entrega2
    $this->username = 'root';
    $this->password = '';

    $this->connect();
  }

  protected function connect() {
    $srv = "mysql:host={$this->host};dbname={$this->dbname}";

    try {
      $this->conn = new PDO($srv, $this->username, $this->password);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // setAtribute() -> establece un atributo en el objeto PDO
        // PDO::ATTR_ERRMODE -> reporte de errores
        // PDO::ERRMODE_EXCEPTION -> lanza una excepcion
      $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        // PDO::ATTR_DEFAULT_FETCH_MODE -> establece el modo de obtencion de los datos
        // PDO::FETCH_ASSOC -> devuelve un array indexado por los nombres de las columnas del conjunto de resultados
    } catch (PDOException $e) {
      die('Error de conexion: ' . $e->getMessage());
    }
  }

  public function getConnection() {
    return $this->conn;
  }

}
