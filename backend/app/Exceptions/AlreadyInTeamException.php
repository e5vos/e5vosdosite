<?php

namespace App\Exceptions;

use Exception;

class AlreadyInTeamException extends Exception
{
    public function render()
    {
        return response()->json([
            'message' => 'Ez a felhasználó már benne van ebben a csapatban.',
        ], 409);
    }
}
