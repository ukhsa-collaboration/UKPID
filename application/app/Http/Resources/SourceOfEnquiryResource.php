<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SourceOfEnquiryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            /** @var int $id */
            'id' => $this->id,
            'name' => $this->name,
            /** @var bool $hidden Prevent new enquiries from using this source of enquiry if marked as hidden */
            'hidden' => $this->hidden,
        ];
    }
}
