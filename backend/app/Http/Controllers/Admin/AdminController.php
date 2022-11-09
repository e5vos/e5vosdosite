<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\NotAllowedException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     *
     */
    public function cacheClear(Request $request)
    {
        if (env('APP_ENV') === 'production' || !$request->user()->hasPermission('OPT')) {
            throw new NotAllowedException();
        }
        DB::table('cache')->truncate();
        return response()->json(['message' => 'Cache cleared']);
    }
}
