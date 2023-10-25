<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
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
            "code" => $this->code,
            "name" => $this->name,

            "members" => UserResource::collection($this->whenLoaded('members')),
            "events" => EventResource::collection($this->whenLoaded('events')),

            "pivot" => $this->whenPivotLoaded($this->pivot?->getTable(), fn () => $this->pivot),
        ];
    }
}
