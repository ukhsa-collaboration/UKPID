<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            /** @var int $id The id of the audit log entry. */
            'id' => $this->id,
            /** @var UserResource $owner The user who did the action. */
            'owner' => new UserResource($this->user),
            /** @var string $event The event which triggered the log. One of: `created`, `updated`, `deleted`, `restored`. */
            'event' => $this->event,
            /** @var int $target_id The ID of the record the audit log entry relates to. */
            'target_id' => $this->auditable_id,
            /** @var object $old_values The original value(s) of the field(s) changed. */
            'old_values' => $this->old_values,
            /** @var object $new_values The updated value(s) of the field(s) changed. */
            'new_values' => $this->new_values,
            /** @var string $created_at The date and time the event was logged. */
            'date' => $this->created_at,
        ];
    }
}
