<?php

namespace App\Exceptions;

use Exception;

class AttendanceRegisterDisabledException extends Exception
{
    public function render()
    {
        return response()->json([
            'message' => 'A jelenléti ívek módosítása le van tiltva.',
        ], 403);
    }
}
