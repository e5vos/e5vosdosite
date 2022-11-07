<?php

namespace App\Exceptions;

use Exception;

class ResourceDidNoExistException extends Exception
{
    public function render()
    {
        return response()->json([
            'message' => 'A törölni kíívánt erőforrás nem létezik.',
        ], 409);
    }
}
