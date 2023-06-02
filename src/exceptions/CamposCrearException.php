<?php

namespace App\Exceptions;

use Exception;

class CamposCrearException extends Exception {
  protected $message = [];

  public function __construct($errores) {
    $this->message = $errores;   
  }
}
