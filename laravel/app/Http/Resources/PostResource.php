<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'body'       => $this->body,
            'file'       => new FileResource($this->file),
            'latitude'   => $this->latitude,
            'longitude'  => $this->longitude,
            'visibility' => new VisibilityResource($this->visibility),
            'author'     => new UserResource($this->author),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // Counters
            'likes_count'=> $this->likes_count,
            'comments_count'=> $this->comments_count,
            // Logged user info
            'liked'     => auth()->user() ? $this->likedByAuthUser() : false,
            'commented' => auth()->user() ? $this->commentedByAuthUser() : false
        ];
    }
}
