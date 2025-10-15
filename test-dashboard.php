<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Load Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Create test data for dashboard
$taskStats = [
    'total' => 0,
    'completed' => 0,
    'in_progress' => 0,
    'todo' => 0,
    'overdue' => 0,
];

$projectStats = [
    'total' => 0,
    'active' => 0,
    'completed' => 0,
    'owned' => 0,
];

$userProjects = collect([]);
$upcomingTasks = collect([]);
$overdueTasks = collect([]);

// Create the view
$view = view('dashboard', compact(
    'userProjects',
    'taskStats', 
    'projectStats',
    'upcomingTasks',
    'overdueTasks'
));

echo $view->render();