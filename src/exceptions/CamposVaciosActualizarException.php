<?php

namespace App\Exceptions;

use Exception;

class CamposVaciosActualizarException extends Exception {
    protected $message;

    public function __construct($tabla) {
        switch ($tabla) {
            case 'juegos':
                $msg = "Debe completar al menos un campo.";
                break;
            default:
                $msg = "Debe completar el campo.";
                break;
        }
        $this->message = $msg;
    }
}
