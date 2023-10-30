<?php

namespace App\Exceptions;

use Exception;

class SignupNotRequiredException extends Exception
{
    /**
     * On exception return a 422 response
     */
    public function render()
    {
        return response()->json([
            'message' => 'Erre az eseményre nem lehet jeletkezni, mert a szervező nem kívánt jelentkezést.',
        ], 422);
    }
}
