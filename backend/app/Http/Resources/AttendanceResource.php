<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
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
            "id" => $this->id,
            "user_id" => $this->user_id,
            "user" => new UserResource($this->whenLoaded('user')),
            "team_id" => $this->team_id,
            "team" => new TeamResource($this->whenLoaded('team')),
            "event_id" => $this->event_id,
            "event" => new EventResource($this->whenLoaded('event')),
            "rank" => $this->rank,
            "is_present" => $this->is_present,
            "pivot" => $this->whenPivotLoaded($this->pivot?->getTable(), fn () => $this->pivot),
        ];
    }
}
