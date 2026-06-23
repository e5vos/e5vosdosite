<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventResource;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Cache::rememberForever('locations.all', fn () => LocationResource::collection(Location::all())->jsonSerialize());
    }

    /**
     * Display the specified resource.
     *
     * @param  Location  $location
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
     * @return Response
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
     * @return Response
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
     * @return Response
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
     * @return Response
     */
    public function events(int $locationId)
    {
        $location = Location::findOrFail($locationId);

        return Cache::rememberForever("locations.{$location->id}.events", fn () => EventResource::collection($location->events()->get())->jsonSerialize());
    }

    /**
     * Display a listing of the events ongoing in the specific time.
     *
     * @return Response
     */
    public function currentEvents(int $locationId)
    {
        $location = Location::findOrFail($locationId);
        $time = request()->time ? Carbon::parse(request()->time) : now();

        // Only cache "now"-ish queries; a custom time far from now skips the cache.
        if (abs($time->diffInMinutes(now())) > 5) {
            return EventResource::collection($location->currentEvents($time)->get())->jsonSerialize();
        }

        return Cache::remember("locations.{$location->id}.current_events", 360, fn () => EventResource::collection($location->currentEvents($time)->get())->jsonSerialize());
    }
}
