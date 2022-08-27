<?php

namespace App\Http\Controllers\E5N;

use App\Http\Controllers\{
    Controller
};

use App\Models\{
    EJGClass,
    Event
};
use BadMethodCallException;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\{
    Gate
};

use Endroid\QrCode;


class E5NController extends Controller
{

    public function presentations(){
        throw new BadMethodCallException('Not implemented yet');
    }
    public function attendancesheet($code){
        throw new BadMethodCallException('Not implemented yet');
    }

    public function map(){
        throw new BadMethodCallException('Not implemented yet');
    }

    public function home(){
        throw new BadMethodCallException('Not implemented yet');
    }

    public function admin(){
        throw new BadMethodCallException('Not implemented yet');
    }

    public function reset(){
        throw new BadMethodCallException('Not implemented yet');
    }



}

