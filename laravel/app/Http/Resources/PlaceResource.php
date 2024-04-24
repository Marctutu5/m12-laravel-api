<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlaceResource extends JsonResource
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
            'id'         => $this->id,
            'name'       => $this->name,
            'description'=> $this->description,
            'file'       => new FileResource($this->file),
            'latitude'   => $this->latitude,
            'longitude'  => $this->longitude,
            'visibility' => new VisibilityResource($this->visibility),
            'author'     => new UserResource($this->author),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // Counters
            'favorites_count'=> $this->favorites_count,
            'reviews_count'=> $this->reviews_count,
            // Logged user info
            'favorited' => auth()->user() ? $this->favoritedByAuthUser() : false,
            'reviewed'  => auth()->user() ? $this->reviewedByAuthUser() : false
        ];
    }
}
