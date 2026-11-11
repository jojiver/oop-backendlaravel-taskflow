<?php

namespace App\Repositories\Contracts;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

interface TaskRepositoryInterface
{
    public function allForUser(int $userId, array $filters = []): Collection;

    public function findForUser(int $taskId, int $userId): ?Task;

    public function create(int $userId, array $data): Task;

    public function update(Task $task, array $data): Task;

    public function delete(Task $task): void;

    public function nextUp(int $userId, int $limit = 3): Collection;
}
