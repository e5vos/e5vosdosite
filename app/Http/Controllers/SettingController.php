<?php

namespace App\Http\Controllers;

use App\Http\Resources\SettingResource;
use App\Models\Setting;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\{
    Gate,
};


class SettingController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        Gate::authorize("viewAny",Setting::class);
        $settings = SettingResource::collection(Setting::all());
        return view('admin.settings',["settings"=>$settings]);
    }

    public function show($settingID){
        return redirect()->route('setting.index');
    }

    public function create(Request $request){
        Gate::authorize('create',Setting::class);
        $setting = new Setting();
        $setting->key = $request->input('key');
        $setting->value = $request->input('value');
        $setting->save();
    }
    public function update(Request $request, $settingId){
        $setting = Setting::findOrFail($settingId);
        Gate::authorize("update",$setting);
        $setting->set($request->input('newSetting'));
    }
    public function destroy(Request $request,$settingId ){
        $setting = Setting::findOrFail($settingId);
        Gate::authorize('delete',$setting);
        $setting->delete();
    }

}
