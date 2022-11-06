<?php

namespace App\Exceptions;

use Exception;

class AlreadyInTeamException extends Exception
{
    public function render()
    {
        return response()->json([
            'message' => 'Already in team',
        ], 409);
    }
}
