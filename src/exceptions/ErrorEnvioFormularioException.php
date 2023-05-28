<?php

namespace App\Exceptions;

use Exception;

class ErrorEnvioFormularioException extends Exception {
  protected $message;

  public function __construct($errores) {
    $this->message = 'El formulario no esta enviado parametros (keys)';
  
  }
}
