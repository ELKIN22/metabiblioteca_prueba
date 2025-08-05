<?php

namespace App\Exceptions;

use Exception;

class OrcidException extends Exception
{
    public function __construct(string $message = 'Error al consultar ORCID', int $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
