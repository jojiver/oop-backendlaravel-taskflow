<?php

namespace App\Http\Requests;

use App\Enums\TaskCategory;
use App\Enums\TaskPriority;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:2000'],
            'due_date' => ['nullable', 'date'],
            'priority' => ['required', Rule::in(TaskPriority::values())],
            'category' => ['required', Rule::in(TaskCategory::values())],
        ];
    }
}
