<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskApiTest extends TestCase
  {
        use RefreshDatabase;

    public function test_authenticated_user_can_create_a_task(): void
    {
              $user = User::factory()->create();

            $response = $this->actingAs($user, 'sanctum')->postJson('/api/tasks', [
                                                                                'title' => 'Write the client proposal',
                                                                                'status' => 'pending',
                                                                            ]);

            $response->assertCreated()
                          ->assertJsonPath('data.title', 'Write the client proposal');

            $this->assertDatabaseHas('tasks', [
                                                 'user_id' => $user->id,
                                                 'title' => 'Write the client proposal',
                                             ]);
    }

    public function test_creating_a_task_requires_a_title(): void
    {
              $user = User::factory()->create();

            $response = $this->actingAs($user, 'sanctum')->postJson('/api/tasks', [
                                                                                'status' => 'pending',
                                                                            ]);

            $response->assertStatus(422)->assertJsonValidationErrors('title');
    }

    public function test_user_only_sees_their_own_tasks(): void
    {
              $user = User::factory()->create();
              $otherUser = User::factory()->create();

            Task::factory()->for($user)->count(2)->create();
              Task::factory()->for($otherUser)->count(3)->create();

            $response = $this->actingAs($user, 'sanctum')->getJson('/api/tasks');

            $response->assertOk()->assertJsonCount(2, 'data');
    }

    public function test_user_cannot_view_another_users_task(): void
    {
              $user = User::factory()->create();
              $otherUsersTask = Task::factory()->for(User::factory())->create();

                        $response = $this->actingAs($user, 'sanctum')->getJson("/api/tasks/{$otherUsersTask->id}");

                        // 404, not 403 — we don't want to confirm the task ID even exists.
                        $response->assertNotFound();
    }

    public function test_user_can_update_their_own_task(): void
    {
              $user = User::factory()->create();
              $task = Task::factory()->for($user)->create(['status' => 'pending']);

            $response = $this->actingAs($user, 'sanctum')->putJson("/api/tasks/{$task->id}", [
                                                                               'status' => 'done',
                                                                           ]);

            $response->assertOk()->assertJsonPath('data.status', 'done');
    }

    public function test_unauthenticated_requests_are_rejected(): void
    {
              $this->getJson('/api/tasks')->assertUnauthorized();
    }
  }
