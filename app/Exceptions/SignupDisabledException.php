<?php

namespace App\Exceptions;

use Exception;

class SignupDisabledException extends Exception
{
    public function render()
    {
        return response()->json([
            'message' => 'Jelenleg nincs eseményjelentkezési időszak.',
        ], 403);
    }
}
