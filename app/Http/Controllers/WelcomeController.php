<?php

namespace App\Http\Controllers;


class WelcomeController extends Controller
{
    public function index(){
        return view('welcome');
    }

    public function qr($code){
        return view('qrcode',["code"=>$code]);
    }
}
