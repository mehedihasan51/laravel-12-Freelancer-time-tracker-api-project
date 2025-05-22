<?php

namespace App\Http\Resources\Api\TimeLog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\Project\ProjectResource;

class TimeLogStartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'project_id'     => $this->project_id,
            'start_time'     => $this->start_time,
            'description'    => $this->description,
            'project'          => new ProjectResource($this->whenLoaded('project')),
        ];
    }
}
