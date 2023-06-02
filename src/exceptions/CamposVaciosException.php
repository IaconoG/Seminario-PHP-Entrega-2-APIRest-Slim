<?php

namespace App\Exceptions;

use Exception;

class CamposVaciosException extends Exception {
    protected $message;

    public function __construct($tabla) {
        switch ($tabla) {
            case 'juegos':
                $msg = "Los campos no deben estar vacios debe completar al menos uno.";
                break;
            default:
                $msg = "El campo no puede estar vacio debe completarlo.";
                break;
        }
        $this->message = $msg;
    }
}
