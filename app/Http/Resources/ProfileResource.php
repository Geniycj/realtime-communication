<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            'user_id' => $this->user_id,
            'phone' => $this->when(!empty($this?->phone), $this->phone),
            'name' => $this->when(!empty($this?->name), $this->name),
            'surname' => $this->when(!empty($this?->surname), $this->surname),
            'middle_name' => $this->when(!empty($this?->middle_name), $this->middle_name),
            'notification' => $this->when(!empty($this?->notification), $this->notification),
            'profile' => new ProfileResource($this->whenLoaded('profile')),
            'created_at' => $this->when(!empty($this->created_at), function () {
                return $this->created_at->diffForHumans();
            }),
        ];
    }
}
