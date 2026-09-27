<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
  {
        public function authorize(): bool
    {
              // Any authenticated user may create a task for themselves.
            return true;
    }

    public function rules(): array
    {
              return [
                            'title' => ['required', 'string', 'max:255'],
                            'description' => ['nullable', 'string', 'max:2000'],
                            'status' => ['sometimes', 'in:pending,in_progress,done'],
                            'due_date' => ['nullable', 'date', 'after_or_equal:today'],
                        ];
    }

    public function messages(): array
    {
              return [
                            'due_date.after_or_equal' => 'The due date can\'t be in the past.',
                        ];
    }
  }
