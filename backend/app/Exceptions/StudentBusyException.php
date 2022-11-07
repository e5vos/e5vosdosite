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
            'message' => 'A diák ebben az időpontban elfoglalt.',
        ], 409);
    }
}
