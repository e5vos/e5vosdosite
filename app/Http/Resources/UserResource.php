<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'ejg_class' => $this->ejg_class,
            'email' => $this->email,
            'e5code' => $this->e5code,
            'img_url' => $this->img_url,

            'teams' => TeamResource::collection($this->whenLoaded('teams')),
            'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),
            'organisedEvents' => EventResource::collection($this->whenLoaded('organisedEvents')),
            'presentations' => EventResource::collection($this->whenLoaded('presentations')),
            'isBusy' => $this->whenLoaded('isBusy', fn () => $this->isBusy()),

            'pivot' => $this->whenPivotLoaded($this->pivot?->getTable(), fn () => $this->pivot),
        ];
    }
}
