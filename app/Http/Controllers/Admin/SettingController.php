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
        return (new SettingResource($setting))->jsonSerialize();
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
        $setting->update(['value' => $value]);
        return response((new SettingResource($setting))->jsonSerialize(), 200);
    }

    /**
     * Toggle a setting.
     *
     * @param  string  $key
     * @return \Illuminate\Http\Response
     * @deprecated
     */
    public function toggle($key)
    {
        $setting = Setting::where('key', $key)->firstOrFail();
        $setting->update(['value' => !$setting->value]);
        return response((new SettingResource($setting))->jsonSerialize(), 200);
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
        return response()->noContent();
    }
}
