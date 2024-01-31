<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\NotAllowedException;
use App\Helpers\PermissionType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class AdminController extends Controller
{
    /**
     * Clear the cache
     */
    public function cacheClear(Request $request)
    {
        if (env('APP_ENV') === 'production' && ! $request->user()->hasPermission(PermissionType::Admin->value)) {
            throw new NotAllowedException();
        }
        DB::statement('TRUNCATE TABLE cache');

        return response()->json(['message' => 'Cache cleared']);
    }

    public function dumpState(Request $request)
    {
        if (env('APP_ENV') === 'production' && ! $request->user()->hasPermission(PermissionType::Admin->value) && (! getenv('DEBUG_API_KEY') || $request->key != env('DEBUG_API_KEY'))) {
            throw new NotAllowedException();
        }
        Http::post(env('DISCORD_WEBHOOK', ''), [
            'username' => 'DoSys',
            'content' => '```json'.(strlen($request->dump) > 1900 ? substr($request->dump, 0, 1900) : $request->dump).'```',
        ]);

        return response()->noContent();
    }
}
