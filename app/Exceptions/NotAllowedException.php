<?php

namespace App\Exceptions;

use Exception;

class NotAllowedException extends Exception
{
    /**
     * On exception exit with 403 status code
     */
    public function render()
    {
        return response()->json([
            'message' => 'Ejnyebejnye, nem te vagy a főnök.',
        ], 403);
    }
}
