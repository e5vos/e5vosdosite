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
            "name" => $this->name,
            "ejg_class" => $this->ejg_class,
            "email" => $this->email,
            "e5code" => $this->e5code,
            "img_url" => $this->img_url,
            "permissions" => $this->permissions,
        ];
    }
}
