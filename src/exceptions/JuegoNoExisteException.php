<?php

namespace App\Exceptions;

use Exception;

class JuegoNoExisteException extends Exception
{
    protected $message = 'El juego no existe.';
}
