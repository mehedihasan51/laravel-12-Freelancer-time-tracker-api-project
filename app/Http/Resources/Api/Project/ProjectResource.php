<?php

namespace App\Http\Resources\Api\Project;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\Client\ClientResource;

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
            // 'client_id'     => $this->client_id,
            // 'project_id'      => $this->id,
            'title'           => $this->title,
            'description'     => $this->description,
            'status'          => $this->status,
            'deadline'        => $this->deadline,
            'client'          => new ClientResource($this->whenLoaded('client')),
        ];
    }
}
