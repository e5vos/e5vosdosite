<?php

namespace App\Exceptions;

use Exception;

class NotAllowedException extends Exception
{
    public function __construct($message = null)
    {
        parent::__construct();
        $this->message = mb_convert_encoding($message, 'UTF-8', 'auto');
    }

    /**
     * On exception exit with 403 status code
     */
    public function render()
    {
        return response()->json([
            'message' => $this->message ?? 'Ejnyebejnye, nem te vagy a főnök.',
        ], 403);
    }
}
