<?php

namespace App\Exceptions;

use Exception;

class SignupRequiredException extends Exception
{
    /**
     * On exception return a 403 response
     */
    public function render()
    {
        return response()->json([
            'message' => 'Signup required',
        ], 403);
    }
}
