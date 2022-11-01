<?php

namespace App\Exceptions;

use Exception;

class EventFullException extends Exception
{
    /**
     * On exception exit with 422 status code
     */
    public function render()
    {
        return response()->json([
            'message' => 'Event is full',
        ], 422);
    }
}
