<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CodeResource extends JsonResource
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
            'additional_data' => $this->additional_data,
            'code_table_id' => $this->code_table_id,
            'code_table' => new CodeTableResource($this->whenLoaded('codeTable')),
        ];
    }
}
