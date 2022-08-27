<?php

namespace App\Http\Resources;

use Faker\Guesser\Name;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request){
        return [
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'organiser_name' => $this->organiser_name,
            'organisers' => new UserResourceCollection($this->organisers()->get()),
            'location' => new LocationResource($this->location),
            'rating' => $this->rating,
        ];
    }
}
