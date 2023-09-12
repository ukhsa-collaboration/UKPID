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
            'name' => $this->name,
            'email' => $this->email,
            /** @var array{id: int, key: string} $location */
            'location' => $this->location,
            /** @var string $created_at */
            'created_at' => $this->created_at,
            /** @var string $updated_at */
            'updated_at' => $this->updated_at,
        ];
    }
}
