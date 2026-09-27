<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
  {
        public function authorize(): bool
    {
              // Ownership is enforced in the controller (404 for non-owned tasks
            // so we don't leak whether a task ID exists at all).
            return true;
    }

    public function rules(): array
    {
              return [
                            'title' => ['sometimes', 'required', 'string', 'max:255'],
                            'description' => ['nullable', 'string', 'max:2000'],
                            'status' => ['sometimes', 'in:pending,in_progress,done'],
                            'due_date' => ['nullable', 'date'],
                        ];
    }
  }
