<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskController extends Controller
{
    public function __construct(
        private readonly TaskService $tasks,
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $tasks = $this->tasks->list($request->user()->id, [
            'status' => $request->query('status'),
            'category' => $request->query('category'),
            'priority' => $request->query('priority'),
            'search' => $request->query('search'),
            'sort' => $request->query('sort'),
        ]);

        return TaskResource::collection($tasks);
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = $this->tasks->create($request->user()->id, $request->validated());

        return (new TaskResource($task))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request, int $task): TaskResource|JsonResponse
    {
        $found = $this->tasks->find($task, $request->user()->id);

        if (! $found) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        return new TaskResource($found);
    }

    public function update(UpdateTaskRequest $request, int $task): TaskResource|JsonResponse
    {
        $updated = $this->tasks->update(
            $task,
            $request->user()->id,
            $request->validated(),
        );

        if (! $updated) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        return new TaskResource($updated);
    }

    public function toggle(Request $request, int $task): TaskResource|JsonResponse
    {
        $updated = $this->tasks->toggle($task, $request->user()->id);

        if (! $updated) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        return new TaskResource($updated);
    }

    public function destroy(Request $request, int $task): JsonResponse
    {
        if (! $this->tasks->delete($task, $request->user()->id)) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        return response()->json(['message' => 'Task deleted']);
    }
}
