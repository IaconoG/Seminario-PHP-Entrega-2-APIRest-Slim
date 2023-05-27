<?php

namespace App\Exceptions;

use Exception;

class TablaSinDatosException extends Exception {
    protected $message;
    
    public function __construct($tabla) {
      $this->$message = 'La tabla ' . $tabla . ' no posee ningun dato. :(';
    }

}
