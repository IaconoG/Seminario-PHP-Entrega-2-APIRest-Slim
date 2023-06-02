<?php

namespace App\Exceptions;

use Exception;

class NoExisteEnTablaException extends Exception {
    protected $message;

    public function __construct($msg) {
        $this->message = $msg;
    }
}
