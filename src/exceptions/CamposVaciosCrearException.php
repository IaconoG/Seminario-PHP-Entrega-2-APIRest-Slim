<?php

namespace App\Exceptions;

use Exception;

class CamposVaciosCrearException extends Exception {
  protected $message = [];

  public function __construct($errores) {
    $this->message = $errores;   
  }
}
