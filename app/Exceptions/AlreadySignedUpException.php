<?php

namespace App\Exceptions;

use Exception;

class AlreadySignedUpException extends Exception
{
    /**
     * On exception exit with 422 status code
     */
    public function render()
    {
        return response()->json([
            'message' => 'Ez a felhasználó vagy csapat már jelentkezett erre az eseményre.',
        ], 409);
    }
}
