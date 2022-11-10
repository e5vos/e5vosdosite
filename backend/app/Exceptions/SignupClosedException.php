<?php

namespace App\Exceptions;

use Exception;

class SignupClosedException extends Exception
{
    /**
     * On exception return a 403 response
     */
    public function render()
    {
        return response()->json([
            'message' => 'Erre az eseményre a jelentkezés le van zárva.',
        ], 403);
    }
}
