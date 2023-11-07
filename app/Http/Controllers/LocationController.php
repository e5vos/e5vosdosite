<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventResource;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use Hamcrest\Type\IsString;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Cache::rememberForever('locations.all', fn () => LocationResource::collection(Location::all())->jsonSerialize());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Location  $location
     * @return LocationResource
     */
    public function show(int $locationId)
    {
        $location = Location::with('events')->findOrFail($locationId);
        return Cache::rememberForever("locations.{$location->id}", fn () => new LocationResource($location));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $location = Location::updateOrCreate($request->all());
        Cache::forget('locations.all');
        return Cache::rememberForever("locations.{$location->id}", fn () => new LocationResource($location));
    }

    /**
     * Update the specified location in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $locationId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $locationId)
    {
        $location = Location::findOrFail($locationId);
        foreach ($request->all() as $key => $value) {
            $location->$key = $value;
        }
        $location->save();
        Cache::forget('locations.all');
        Cache::forget("locations.{$location->id}");
        Cache::forget("locations.{$location->id}.events");
        return Cache::rememberForever("locations.{$location->id}", fn () => new LocationResource($location));
    }

    /**
     * Remove the specified location from storage.
     *
     * @param  int  $locationId
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $locationId)
    {
        $location = Location::findOrFail($locationId);
        $location->delete();
        Cache::forget('locations.all');
        Cache::forget("locations.{$location->id}");
        Cache::forget("locations.{$location->id}.events");
        return response()->noContent();
    }

    /**
     * Display a listing of the events for the specified location.
     *
     * @param  int  $locationId
     * @return \Illuminate\Http\Response
     */
    public function events(int $locationId)
    {
        $location = Location::findOrFail($locationId);
        return Cache::rememberForever("locations.{$location->id}.events", fn () => EventResource::collecion($location->events())->jsonSerialize());
    }

    /**
     * Display a listing of the events ongoing in the specific time.
     *
     * @param  int  $locationId
     * @return \Illuminate\Http\Response
     */
    public function currentEvents(int $locationId)
    {
        $location = Location::findOrFail($locationId);
        $time = request()->time ?? now();
        if (is_string($time)) {
            $time = strtotime($time);
        }
        if (date_diff(now(), $time)->i > 5) {
            return EventResource::collecion($location->currentEvents($time))->jsonSerialize();
        }
        return Cache::remember("locations.{$location->id}.current_events", 360, fn () => EventResource::collecion($location->currentEvents($time))->jsonSerialize());
    }
}
