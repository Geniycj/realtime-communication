<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->when(! empty($this->name), $this->name),
            'email' => $this->when(! empty($this->email), $this->email),
            'profile' => new ProfileResource($this->whenLoaded('profile')),
            'created_at' => $this->when(!empty($this->created_at), function () {
                return $this->created_at->diffForHumans();
            }),
        ];
    }
}
