<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'dueDate' => $this->due_date?->toDateString(),
            'priority' => $this->priority,
            'category' => $this->category,
            'completed' => $this->completed,
            'completedAt' => $this->completed_at?->toIso8601String(),
            'createdAt' => $this->created_at?->toIso8601String(),
            'isOverdue' => ! $this->completed
                && $this->due_date
                && $this->due_date->lt(today()),
        ];
    }
}
