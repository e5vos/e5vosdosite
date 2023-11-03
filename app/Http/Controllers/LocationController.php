<?php

namespace App\Http\Controllers;

use App\Http\Resources\LocationResource;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LocationController extends Controller
{
    public function index()
    {
        return Cache::rememberForever('locations.all', fn () => LocationResource::collection(Location::all())->jsonSerialize());
    }
}
