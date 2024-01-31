<?php

namespace App\Exceptions;

use Exception;

class InvalidCodeException extends Exception
{
    public function render()
    {
        return response()->json([
            'message' => 'Érvénytelen EJG diákkód.',
        ], 400);
    }
}
