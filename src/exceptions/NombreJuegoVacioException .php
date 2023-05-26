<?php

namespace App\Exceptions;

use Exception;

class NombreJuegoVacioException extends Exception
{
    protected $message = 'El nombre del juego no debe estar vacío.';
}
