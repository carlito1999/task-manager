<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Test route for dashboard without auth
Route::get('/test-dashboard', function () {
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

    return view('dashboard', compact(
        'userProjects',
        'taskStats', 
        'projectStats',
        'upcomingTasks',
        'overdueTasks'
    ));
});

// Dashboard routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/data', [DashboardController::class, 'getData'])->name('dashboard.data');
});

// Project routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('projects', ProjectController::class);
    
    // Additional project routes
    Route::post('/projects/{project}/members', [ProjectController::class, 'addMember'])->name('projects.add-member');
    Route::delete('/projects/{project}/members/{user}', [ProjectController::class, 'removeMember'])->name('projects.remove-member');
});

// Task routes (nested under projects)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('projects.tasks', TaskController::class);
    
    // Additional task routes
    Route::patch('/projects/{project}/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.update-status');
    Route::patch('/projects/{project}/tasks/{task}/assign', [TaskController::class, 'assign'])->name('tasks.assign');
    
    // My tasks page
    Route::get('/my-tasks', [TaskController::class, 'myTasks'])->name('tasks.my-tasks');
});

// Comment routes (nested under projects and tasks)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/projects/{project}/tasks/{task}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::patch('/projects/{project}/tasks/{task}/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/projects/{project}/tasks/{task}/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
