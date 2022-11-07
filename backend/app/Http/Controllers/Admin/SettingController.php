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
        return SettingResource::collection(Setting::all())->jsonSerialize();
    }

    /**
     * Store a newly created setting in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $setting = Setting::create(['key' => $request->key, 'value' => $request->value]);
        $setting = new SettingResource($setting);
        return $setting->jsonSerialize();
    }

    /**
     * Toggle the specified setting.
     *
     * @param  string  $key
     * @return \Illuminate\Http\Response
     */
    public function set($key, $value)
    {
        $setting = Setting::where('key', $key)->firstOrFail();
        $setting->value = $value;
        $setting->save();
        $setting = new SettingResource($setting);
        return $setting->jsonSerialize();
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
        return response()->json(['message' => 'Setting deleted'], 204);
    }
}
