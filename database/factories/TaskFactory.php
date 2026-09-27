<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
  {
        public function definition(): array
    {
              return [
                            'user_id' => User::factory(),
                            'title' => fake()->sentence(4),
                            'description' => fake()->optional()->paragraph(),
                            'status' => fake()->randomElement(['pending', 'in_progress', 'done']),
                            'due_date' => fake()->optional()->dateTimeBetween('now', '+1 month'),
                        ];
    }
  }
