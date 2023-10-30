<?php

namespace App\Exceptions;

use Exception;

class InvalidCodeException extends Exception
{
    public function render()
    {
        return response()->json([
            'message' => 'Hibás e5vös kód.',
        ], 400);
    }
}
