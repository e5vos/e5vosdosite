<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

class StudentBusyException extends Exception
{
    public function render(Request $request){
        return abort(400);
    }
}
