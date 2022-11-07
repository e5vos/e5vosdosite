<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Ramsey\Uuid\Type\Integer;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'slot' => new SlotResource($this->whenLoaded('slot')),
            'slot_id' => $this->slot_id,
            'location' => new LocationResource($this->whenLoaded('location')),
            'location_id' => $this->location_id,
            'signup_type' => $this->signup_type,
            'is_competition' => $this->is_competition,
            'signup_deadline' => $this->signup_deadline,
            'organiser' => $this->organiser,
            'capacity' => $this->capacity,
            'img_url' => $this->img_url,
            'starts_at' => $this->starts_at,
            'ends_at' => $this->ends_at,
            //'rating' => $this->rating,
            'occupancy' => $this->occupancy,


            'attendances' => AttendanceResource::collection($this->whenLoaded('attendances')),
            'organisers' => UserResource::collection($this->whenLoaded('organisers')),
            'users' => UserResource::collection($this->whenLoaded('users')),
            'teams' => TeamResource::collection($this->whenLoaded('teams')),
            'attendees' => UserResource::collection($this->whenLoaded('attendees')),

            "pivot" => $this->whenPivotLoaded($this->pivot->getTable(), fn () => $this->pivot),
        ];
    }
}
