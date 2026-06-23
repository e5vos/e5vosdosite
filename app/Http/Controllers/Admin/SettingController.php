<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\SettingResource;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SettingController extends Controller
{
    /**
     * Display a listing of the settings.
     *
     * @return Response
     */
    public function index()
    {
        return SettingResource::collection(Setting::all())->jsonSerialize();
    }

    /**
     * Store a newly created setting in storage.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $setting = Setting::create(['key' => $request->key, 'value' => $request->value]);

        return response((new SettingResource($setting))->jsonSerialize(), 201);
    }

    /**
     * Toggle the specified setting.
     *
     * @param  string  $key
     * @return Response
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
     * @return Response
     *
     * @deprecated
     */
    public function toggle($key)
    {
        $setting = Setting::where('key', $key)->firstOrFail();
        $setting->update(['value' => ! $setting->value]);

        return response((new SettingResource($setting))->jsonSerialize(), 200);
    }

    /**
     * Remove the specified setting from storage.
     *
     * @param  string  $key
     * @return Response
     */
    public function destroy($key)
    {
        $setting = Setting::where('key', $key)->firstOrFail();
        $setting->delete();

        return response()->noContent();
    }
}
