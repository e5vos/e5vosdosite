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
            'message' => 'Team or User already signed up for this event',
        ], 409);
    }
}
