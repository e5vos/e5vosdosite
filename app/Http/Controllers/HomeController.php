<?php

namespace App\Http\Controllers;
use App\Help;
use Illuminate\Http\Request;
use App\Permission;
use App\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        return view('home');
    }
    public function admin()
    {

        //Help::getSos();
        return view('admin.admin', [
            'permissions' => Permission::all('user_id', 'permission'),
        ]);
    }
}
