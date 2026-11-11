<?php

namespace App\Repositories\Eloquent;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentTaskRepository implements TaskRepositoryInterface
{
    public function allForUser(int $userId, array $filters = []): Collection
    {
        $query = Task::query()->where('user_id', $userId);

        if (! empty($filters['status'])) {
            $status = $filters['status'];
            if ($status === 'completed') {
                $query->where('completed', true);
            } elseif ($status === 'pending') {
                $query->where('completed', false);
            } elseif ($status === 'overdue') {
                $query->where('completed', false)->whereDate('due_date', '<', today());
            } elseif ($status === 'today') {
                $query->whereDate('due_date', today());
            } elseif ($status === 'upcoming') {
                $query->where('completed', false)->whereDate('due_date', '>', today());
            }
        }

        if (! empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if (! empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (! empty($filters['search'])) {
            $query->where('title', 'like', '%'.$filters['search'].'%');
        }

        $sort = $filters['sort'] ?? 'due_date';
        if ($sort === 'priority') {
            $query->orderByRaw("case priority when 'high' then 1 when 'medium' then 2 else 3 end");
        } else {
            $query->orderByRaw('due_date is null')->orderBy('due_date');
        }

        return $query->orderByDesc('created_at')->get();
    }

    public function findForUser(int $taskId, int $userId): ?Task
    {
        return Task::query()
            ->where('user_id', $userId)
            ->find($taskId);
    }

    public function create(int $userId, array $data): Task
    {
        return Task::query()->create([
            'user_id' => $userId,
            ...$data,
        ]);
    }

    public function update(Task $task, array $data): Task
    {
        $task->fill($data)->save();

        return $task->refresh();
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }

    public function nextUp(int $userId, int $limit = 3): Collection
    {
        return Task::query()
            ->where('user_id', $userId)
            ->where('completed', false)
            ->orderByRaw('due_date is null')
            ->orderBy('due_date')
            ->limit($limit)
            ->get();
    }
}
