<?php

namespace App\Exceptions;

use Exception;

class NombreVacioException extends Exception
{
    protected $message = 'El nombre no debe estar vacío.';
}
