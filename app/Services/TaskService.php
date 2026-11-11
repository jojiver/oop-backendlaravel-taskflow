<?php

namespace App\Services;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TaskService
{
    public function __construct(
        private readonly TaskRepositoryInterface $tasks,
    ) {}

    public function list(int $userId, array $filters = []): Collection
    {
        return $this->tasks->allForUser($userId, $filters);
    }

    public function find(int $taskId, int $userId): ?Task
    {
        return $this->tasks->findForUser($taskId, $userId);
    }

    public function create(int $userId, array $data): Task
    {
        return $this->tasks->create($userId, $data);
    }

    public function update(int $taskId, int $userId, array $data): ?Task
    {
        $task = $this->tasks->findForUser($taskId, $userId);

        if (! $task) {
            return null;
        }

        if (array_key_exists('completed', $data)) {
            $data['completed_at'] = $data['completed'] ? now() : null;
        }

        return $this->tasks->update($task, $data);
    }

    public function toggle(int $taskId, int $userId): ?Task
    {
        $task = $this->tasks->findForUser($taskId, $userId);

        if (! $task) {
            return null;
        }

        return $this->tasks->update($task, [
            'completed' => ! $task->completed,
            'completed_at' => $task->completed ? null : now(),
        ]);
    }

    public function delete(int $taskId, int $userId): bool
    {
        $task = $this->tasks->findForUser($taskId, $userId);

        if (! $task) {
            return false;
        }

        $this->tasks->delete($task);

        return true;
    }
}
