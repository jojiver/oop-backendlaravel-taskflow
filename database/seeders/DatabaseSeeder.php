<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->updateOrCreate(
            ['email' => 'alex@taskflow.test'],
            [
                'name' => 'Alex Morgan',
                'password' => Hash::make('Password123'),
            ],
        );

        $tasks = [
            [
                'title' => 'Submit history essay',
                'description' => 'Final draft for Ms. Rivera\'s class — include citations and a works-cited page.',
                'due_date' => today()->subDay()->toDateString(),
                'priority' => 'high',
                'category' => 'school',
                'completed' => false,
            ],
            [
                'title' => 'Finish calculus problem set',
                'description' => 'Problems 1–20, chapters 4 and 5.',
                'due_date' => today()->toDateString(),
                'priority' => 'high',
                'category' => 'school',
                'completed' => false,
            ],
            [
                'title' => 'Prepare for biology quiz',
                'description' => 'Review cell division and genetics notes.',
                'due_date' => today()->addDays(2)->toDateString(),
                'priority' => 'medium',
                'category' => 'school',
                'completed' => false,
            ],
            [
                'title' => 'Update client project brief',
                'description' => 'Add the revised timeline and budget summary.',
                'due_date' => today()->addDays(3)->toDateString(),
                'priority' => 'medium',
                'category' => 'work',
                'completed' => false,
            ],
            [
                'title' => 'Renew library books',
                'description' => 'Two books are due this weekend.',
                'due_date' => today()->addDays(5)->toDateString(),
                'priority' => 'low',
                'category' => 'others',
                'completed' => false,
            ],
            [
                'title' => 'Team stand-up notes',
                'description' => 'Share action items with the team.',
                'due_date' => today()->toDateString(),
                'priority' => 'low',
                'category' => 'work',
                'completed' => true,
                'completed_at' => now(),
            ],
            [
                'title' => 'Book dentist appointment',
                'description' => 'Call the clinic before Friday afternoon.',
                'due_date' => today()->addDays(7)->toDateString(),
                'priority' => 'low',
                'category' => 'personal',
                'completed' => false,
            ],
        ];

        foreach ($tasks as $task) {
            Task::query()->updateOrCreate(
                [
                    'user_id' => $user->id,
                    'title' => $task['title'],
                ],
                [...$task, 'user_id' => $user->id],
            );
        }
    }
}
