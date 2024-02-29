<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function __construct(
        $resource,
        protected bool $includePermissions = false
    ) {
        parent::__construct($resource);
    }

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
            'location' => $this->location->name,
            /** @var array{string} $role */
            'role' => $this->getRoleNames(),
            /** @var array{string} $permissions Only included on /user/me requests */
            'permissions' => $this->when($this->includePermissions, fn () => $this->getAllPermissions()->pluck('name')),
            /** @var string $created_at */
            'created_at' => $this->created_at,
            /** @var string $updated_at */
            'updated_at' => $this->updated_at,
        ];
    }
}
