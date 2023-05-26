<?php

namespace App\Exceptions;

use Exception;

class DatosJuegoVaciosException extends Exception
{
    protected $message = 'Debe completar al menos un campo para actualizar el juego.';
}
