<?php

namespace App\Exceptions;

use Exception;

class NoExisteEnTablaException extends Exception {
    protected $message;

    public function __construct($tabla) {
        $this->message = 'El dato no se encuentra en la tabla ' . $tabla;
    }
}
