<?php

namespace App\Http\Resources\Api\Project;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'client_id'     => $this->client_id,
            'title'           => $this->title,
            'description'     => $this->description,
            'status'          => $this->status,
            'deadline'        => $this->deadline,
        ];
    }
}
