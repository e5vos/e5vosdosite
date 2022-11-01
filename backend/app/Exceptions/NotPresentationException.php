<?php

namespace App\Exceptions;

use Exception;

class NotPresentationException extends Exception
{
    /**
     * On exception exit with 418 status code
     */
    public function render()
    {
       abort(418);
    }
}
