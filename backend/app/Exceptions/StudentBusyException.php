<?php

namespace App\Exceptions;

use Exception;

class StudentBusyException extends Exception
{
    /**
     * On exception exit with 422 status code
     */
    public function render()
    {
        return response()->json([
            'message' => 'Student is busy',
        ], 409);
    }
}
