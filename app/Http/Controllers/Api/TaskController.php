<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
  {
        public function index(Request $request)
    {
              $tasks = Task::query()
                            ->ownedBy($request->user())
                            ->status($request->query('status'))
                            ->latest()
                            ->paginate(15);

            return TaskResource::collection($tasks);
    }

    public function store(StoreTaskRequest $request)
    {
              $task = $request->user()->tasks()->create($request->validated());

            return TaskResource::make($task)
                          ->response()
                          ->setStatusCode(201);
    }

    public function show(Request $request, Task $task)
    {
              $this->authorizeOwnership($request, $task);

            return TaskResource::make($task);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
              $this->authorizeOwnership($request, $task);

            $task->update($request->validated());

            return TaskResource::make($task);
    }

    public function destroy(Request $request, Task $task)
    {
              $this->authorizeOwnership($request, $task);

            $task->delete();

            return response()->noContent();
    }

    /**
     * A task belonging to another user should look like it doesn't exist —
         * 404, not 403 — so we never confirm another user's task IDs.
       */
    protected function authorizeOwnership(Request $request, Task $task): void
    {
              abort_unless($task->user_id === $request->user()->id, 404);
    }
  }
