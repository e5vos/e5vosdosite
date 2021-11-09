<?php

namespace App\Exceptions;

use Exception;

class EventFullException extends Exception
{
    public function render(){
        abort(400);
    }
}
