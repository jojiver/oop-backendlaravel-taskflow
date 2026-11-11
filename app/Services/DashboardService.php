<?php

namespace App\Services;

use App\Repositories\Contracts\TaskRepositoryInterface;

class DashboardService
{
    public function __construct(
        private readonly TaskRepositoryInterface $tasks,
    ) {}

    public function summary(int $userId): array
    {
        $all = $this->tasks->allForUser($userId);

        $total = $all->count();
        $completed = $all->where('completed', true)->count();
        $overdue = $all->filter(
            fn ($task) => ! $task->completed
                && $task->due_date
                && $task->due_date->lt(today())
        )->count();
        $pending = $total - $completed;

        $categories = [
            'school' => $all->where('category', 'school')->count(),
            'personal' => $all->where('category', 'personal')->count(),
            'work' => $all->where('category', 'work')->count(),
            'others' => $all->where('category', 'others')->count(),
        ];

        $priorityMix = [
            'high' => $all->where('priority', 'high')->count(),
            'medium' => $all->where('priority', 'medium')->count(),
            'low' => $all->where('priority', 'low')->count(),
        ];

        return [
            'summary' => [
                'total' => $total,
                'completed' => $completed,
                'pending' => $pending,
                'overdue' => $overdue,
                'completionRate' => $total === 0
                    ? 0
                    : (int) round(($completed / $total) * 100),
                'categories' => $categories,
            ],
            'priorityMix' => $priorityMix,
            'nextUp' => $this->tasks->nextUp($userId, 3),
        ];
    }
}
