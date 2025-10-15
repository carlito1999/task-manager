<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\Comment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test users
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $johnDoe = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $janeSmith = User::factory()->create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
        ]);

        $mikeWilson = User::factory()->create([
            'name' => 'Mike Wilson',
            'email' => 'mike@example.com',
        ]);

        // Create sample projects
        $projects = [
            [
                'name' => 'Website Redesign',
                'description' => 'Complete redesign of the company website with modern UI/UX principles.',
                'status' => 'active',
                'user_id' => $testUser->id,
            ],
            [
                'name' => 'Mobile App Development',
                'description' => 'Develop a cross-platform mobile application for iOS and Android.',
                'status' => 'active',
                'user_id' => $testUser->id,
            ],
            [
                'name' => 'Database Migration',
                'description' => 'Migrate legacy database to new cloud infrastructure.',
                'status' => 'completed',
                'user_id' => $johnDoe->id,
            ],
            [
                'name' => 'Marketing Campaign',
                'description' => 'Q4 marketing campaign for product launch.',
                'status' => 'active',
                'user_id' => $janeSmith->id,
            ]
        ];

        $createdProjects = [];
        foreach ($projects as $projectData) {
            $project = Project::create($projectData);
            $createdProjects[] = $project;

            // Add team members to some projects
            if ($project->name === 'Website Redesign') {
                $project->members()->attach([$johnDoe->id, $janeSmith->id]);
            } elseif ($project->name === 'Mobile App Development') {
                $project->members()->attach([$mikeWilson->id, $janeSmith->id]);
            } elseif ($project->name === 'Marketing Campaign') {
                $project->members()->attach([$testUser->id, $mikeWilson->id]);
            }
        }

        // Create sample tasks
        $tasks = [
            // Website Redesign Tasks
            [
                'title' => 'Create wireframes',
                'description' => 'Design wireframes for all main pages including homepage, about, services, and contact.',
                'status' => 'done',
                'priority' => 'high',
                'assigned_to' => $janeSmith->id,
                'project_id' => $createdProjects[0]->id,
                'due_date' => now()->subDays(5),
            ],
            [
                'title' => 'Implement responsive design',
                'description' => 'Code the responsive layout using CSS Grid and Flexbox.',
                'status' => 'in_progress',
                'priority' => 'high',
                'assigned_to' => $testUser->id,
                'project_id' => $createdProjects[0]->id,
                'due_date' => now()->addDays(3),
            ],
            [
                'title' => 'Content migration',
                'description' => 'Migrate existing content from old website to new design.',
                'status' => 'todo',
                'priority' => 'medium',
                'assigned_to' => $johnDoe->id,
                'project_id' => $createdProjects[0]->id,
                'due_date' => now()->addDays(7),
            ],

            // Mobile App Development Tasks
            [
                'title' => 'Setup development environment',
                'description' => 'Configure React Native development environment and initialize project.',
                'status' => 'done',
                'priority' => 'high',
                'assigned_to' => $mikeWilson->id,
                'project_id' => $createdProjects[1]->id,
                'due_date' => now()->subDays(10),
            ],
            [
                'title' => 'Design app architecture',
                'description' => 'Plan the overall architecture including state management and navigation.',
                'status' => 'in_progress',
                'priority' => 'high',
                'assigned_to' => $testUser->id,
                'project_id' => $createdProjects[1]->id,
                'due_date' => now()->addDays(5),
            ],
            [
                'title' => 'Implement user authentication',
                'description' => 'Build login, registration, and password reset functionality.',
                'status' => 'todo',
                'priority' => 'high',
                'assigned_to' => $janeSmith->id,
                'project_id' => $createdProjects[1]->id,
                'due_date' => now()->addDays(10),
            ],
            [
                'title' => 'Create onboarding flow',
                'description' => 'Design and implement user onboarding screens.',
                'status' => 'todo',
                'priority' => 'medium',
                'assigned_to' => $mikeWilson->id,
                'project_id' => $createdProjects[1]->id,
                'due_date' => now()->addDays(14),
            ],

            // Database Migration Tasks
            [
                'title' => 'Data backup',
                'description' => 'Create complete backup of existing database.',
                'status' => 'done',
                'priority' => 'high',
                'assigned_to' => $johnDoe->id,
                'project_id' => $createdProjects[2]->id,
                'due_date' => now()->subDays(15),
            ],
            [
                'title' => 'Schema conversion',
                'description' => 'Convert database schema for new cloud infrastructure.',
                'status' => 'done',
                'priority' => 'high',
                'assigned_to' => $johnDoe->id,
                'project_id' => $createdProjects[2]->id,
                'due_date' => now()->subDays(10),
            ],

            // Marketing Campaign Tasks
            [
                'title' => 'Market research',
                'description' => 'Conduct comprehensive market research for target audience.',
                'status' => 'done',
                'priority' => 'high',
                'assigned_to' => $janeSmith->id,
                'project_id' => $createdProjects[3]->id,
                'due_date' => now()->subDays(8),
            ],
            [
                'title' => 'Create campaign materials',
                'description' => 'Design banners, social media posts, and email templates.',
                'status' => 'in_progress',
                'priority' => 'medium',
                'assigned_to' => $testUser->id,
                'project_id' => $createdProjects[3]->id,
                'due_date' => now()->addDays(2),
            ],
            [
                'title' => 'Setup analytics tracking',
                'description' => 'Configure Google Analytics and conversion tracking.',
                'status' => 'todo',
                'priority' => 'low',
                'assigned_to' => $mikeWilson->id,
                'project_id' => $createdProjects[3]->id,
                'due_date' => now()->addDays(6),
            ],

            // Some overdue tasks for demonstration
            [
                'title' => 'Fix urgent bug',
                'description' => 'Critical bug causing login issues for some users.',
                'status' => 'todo',
                'priority' => 'high',
                'assigned_to' => $testUser->id,
                'project_id' => $createdProjects[0]->id,
                'due_date' => now()->subDays(2), // Overdue
            ],
            [
                'title' => 'Update documentation',
                'description' => 'Update project documentation with latest changes.',
                'status' => 'in_progress',
                'priority' => 'low',
                'assigned_to' => $testUser->id,
                'project_id' => $createdProjects[1]->id,
                'due_date' => now()->subDays(1), // Overdue
            ],
        ];

        $createdTasks = [];
        foreach ($tasks as $taskData) {
            $task = Task::create($taskData);
            $createdTasks[] = $task;
        }

        // Create sample comments
        $comments = [
            [
                'content' => 'Great work on the wireframes! The layout looks very user-friendly.',
                'user_id' => $testUser->id,
                'task_id' => $createdTasks[0]->id,
                'created_at' => now()->subDays(3),
            ],
            [
                'content' => 'I\'ve started working on the responsive design. Should have the first draft ready by tomorrow.',
                'user_id' => $testUser->id,
                'task_id' => $createdTasks[1]->id,
                'created_at' => now()->subDays(1),
            ],
            [
                'content' => 'The React Native setup is complete. Ready to move on to the next phase.',
                'user_id' => $mikeWilson->id,
                'task_id' => $createdTasks[3]->id,
                'created_at' => now()->subDays(8),
            ],
            [
                'content' => 'I think we should consider adding social login options as well.',
                'user_id' => $janeSmith->id,
                'task_id' => $createdTasks[5]->id,
                'created_at' => now()->subHours(6),
            ],
            [
                'content' => 'The market research data shows strong demand for our product in the 25-35 age group.',
                'user_id' => $janeSmith->id,
                'task_id' => $createdTasks[9]->id,
                'created_at' => now()->subDays(2),
            ],
            [
                'content' => 'Working on the campaign materials. Will have the social media templates ready by end of day.',
                'user_id' => $testUser->id,
                'task_id' => $createdTasks[10]->id,
                'created_at' => now()->subHours(3),
            ],
        ];

        foreach ($comments as $commentData) {
            Comment::create($commentData);
        }

        $this->command->info('Sample data has been created successfully!');
        $this->command->info('Users created: ' . User::count());
        $this->command->info('Projects created: ' . Project::count());
        $this->command->info('Tasks created: ' . Task::count());
        $this->command->info('Comments created: ' . Comment::count());
    }
}
