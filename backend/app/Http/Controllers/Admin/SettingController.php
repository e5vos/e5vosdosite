<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Http\Resources\SettingResource;

class SettingController extends Controller
{
    /**
     * Display a listing of the settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new SettingResource(Setting::all());
    }

    /**
     * Store a newly created setting in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $setting = Setting::create(['key' => $request->key, 'value' => false]);
        return new SettingResource($setting);
    }

    /**
     * Toggle the specified setting.
     *
     * @param  string  $key
     * @return \Illuminate\Http\Response
     */
    public function toggle($key)
    {
        $setting = Setting::where('key', $key)->firstOrFail();
        $setting->value = !$setting->value;
        $setting->save();
        return new SettingResource($setting);
    }

    /**
     * Remove the specified setting from storage.
     *
     * @param  string  $key
     * @return \Illuminate\Http\Response
     */
    public function destroy($key)
    {
        $setting = Setting::where('key', $key)->firstOrFail();
        $setting->delete();
        return new SettingResource($setting);
    }
}
