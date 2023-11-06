<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\NotAllowedException;
use App\Helpers\PermissionType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Clear the cache
     */
    public function cacheClear(Request $request)
    {
        if (env('APP_ENV') === 'production' && !$request->user()->hasPermission(PermissionType::Admin->value)) {
            throw new NotAllowedException();
        }
        DB::table('cache')->truncate();
        return response()->json(['message' => 'Cache cleared']);
    }
}
