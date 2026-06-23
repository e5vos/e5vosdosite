<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
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
            'user_id' => $this->user_id,
            'user' => new UserResource($this->whenLoaded('user')),
            'event_id' => $this->event_id,
            'event' => new EventResource($this->whenLoaded('event')),
            'code' => $this->code,

            'pivot' => $this->whenPivotLoaded($this->pivot?->getTable(), fn () => $this->pivot),
        ];
    }
}
