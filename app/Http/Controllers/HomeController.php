<?php

namespace App\Http\Controllers;



use App\Models\{
    Permission,
    Setting
};

use Illuminate\Http\Request;

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

        return view('admin.admin', [
            'permissions' => Permission::all('user_id', 'permission'),
        ]);
    }

    /**
     * returns current settings
     *
     * @return void
     */
    public function settings(){
        return Setting::all();
    }

    /**
     * toggles the setting with the given id
     *
     * @param  mixed $request
     * @return void
     */
    public function setSetting(Request $request){
        Setting::findOrFail($request->input('setting'))->set($request->input('newSetting'));
    }

    /**
     * returns the view of the settings
     *
     * @return view
     */
    public function settingsView(){
        return view('admin.settings');
    }


}
