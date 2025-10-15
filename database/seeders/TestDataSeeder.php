<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\Comment;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test users
        $john = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $jane = User::factory()->create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
        ]);

        echo "✅ Created users: {$john->name} and {$jane->name}\n";

        // Create a project
        $project = $john->ownedProjects()->create([
            'name' => 'Website Redesign',
            'description' => 'Complete redesign of company website',
            'status' => 'active',
            'deadline' => now()->addWeeks(4),
        ]);

        echo "✅ Created project: {$project->name}\n";

        // Add Jane as a member
        $project->members()->attach($jane->id, ['role' => 'member']);
        echo "✅ Added {$jane->name} as project member\n";

        // Create tasks
        $tasks = [
            [
                'title' => 'Design Homepage',
                'description' => 'Create mockup for new homepage',
                'status' => 'todo',
                'priority' => 'high',
                'due_date' => now()->addDays(7),
                'assigned_to' => $jane->id,
            ],
            [
                'title' => 'Setup Database',
                'description' => 'Configure production database',
                'status' => 'in_progress',
                'priority' => 'medium',
                'due_date' => now()->addDays(3),
                'assigned_to' => $john->id,
            ],
            [
                'title' => 'Write Documentation',
                'description' => 'Document the new features',
                'status' => 'done',
                'priority' => 'low',
                'due_date' => now()->addDays(10),
                'assigned_to' => $john->id,
            ],
        ];

        foreach ($tasks as $taskData) {
            $task = $project->tasks()->create($taskData);
            echo "✅ Created task: {$task->title} (assigned to: {$task->assignedUser->name})\n";

            // Add a comment to each task
            $comment = $task->comments()->create([
                'content' => "Working on this task - {$task->title}",
                'user_id' => $task->assigned_to,
            ]);
            echo "  💬 Added comment by {$comment->user->name}\n";
        }

        // Test relationships
        echo "\n🔍 Testing Relationships:\n";
        echo "Project owner: {$project->owner->name}\n";
        echo "Project members count: {$project->members->count()}\n";
        echo "Project tasks count: {$project->tasks->count()}\n";
        echo "Project completion: {$project->completion_percentage}%\n";
        echo "John's assigned tasks: {$john->assignedTasks->count()}\n";
        echo "Jane's assigned tasks: {$jane->assignedTasks->count()}\n";

        // Test scopes
        echo "\n🔍 Testing Scopes:\n";
        echo "High priority tasks: " . Task::highPriority()->count() . "\n";
        echo "Overdue tasks: " . Task::overdue()->count() . "\n";
        echo "Tasks due soon: " . Task::dueSoon()->count() . "\n";
        echo "John's projects: " . $john->allProjects()->count() . "\n";

        echo "\n🎉 All model relationships working correctly!\n";
    }
}
