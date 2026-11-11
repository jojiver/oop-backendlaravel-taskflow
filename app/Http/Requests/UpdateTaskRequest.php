<?php

namespace App\Http\Requests;

use App\Enums\TaskCategory;
use App\Enums\TaskPriority;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:160'],
            'description' => ['sometimes', 'nullable', 'string', 'max:2000'],
            'due_date' => ['sometimes', 'nullable', 'date'],
            'priority' => ['sometimes', 'required', Rule::in(TaskPriority::values())],
            'category' => ['sometimes', 'required', Rule::in(TaskCategory::values())],
            'completed' => ['sometimes', 'boolean'],
        ];
    }
}
