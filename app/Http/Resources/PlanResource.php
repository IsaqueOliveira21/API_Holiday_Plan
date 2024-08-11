<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (integer) $this->id,
            'user_id' => (integer) $this->user_id,
            'title' => (string) $this->title,
            'description' => (string) $this->description,
            'date' => (string) $this->date,
            'location' => (string) $this->location,
            'participants' => ParticipantResource::collection($this->whenLoaded('participants')),
        ];
    }
}
