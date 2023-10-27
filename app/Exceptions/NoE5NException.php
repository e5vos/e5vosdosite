<?php

namespace App\Exceptions;

use Exception;

class NoE5NException extends Exception
{
    public function render()
    {
        return response()->json([
            'message' => 'Az E5vös Napok még nem kezdődtek el',
        ], 403);
    }
}
