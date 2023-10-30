<?php

namespace App\Exceptions;

use Exception;

class EventFullException extends Exception
{
    /**
     * On exception exit with 409 status code
     */
    public function render()
    {
        return response()->json([
            'message' => 'Az eseményen már nincs hely.',
        ], 409);
    }
}
