<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'code' => $this->code,
            'name' => $this->name,

            'members' => UserResource::collection($this->whenLoaded('members')),
            'events' => EventResource::collection($this->whenLoaded('events')),

            'pivot' => $this->whenPivotLoaded($this->pivot?->getTable(), fn () => $this->pivot),
        ];
    }
}
