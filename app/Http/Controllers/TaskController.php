<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks for a project.
     */
    public function index(Project $project): View
    {
        // Check if user can view this project
        if (!$project->userCanView(Auth::user())) {
            abort(403, 'You do not have permission to view this project.');
        }

        $tasks = $project->tasks()
            ->with(['assignedUser', 'comments'])
            ->latest()
            ->paginate(15);

        return view('tasks.index', compact('project', 'tasks'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create(Project $project): View
    {
        // Check if user can manage this project
        if (!$project->userCanManage(Auth::user())) {
            abort(403, 'You do not have permission to create tasks in this project.');
        }

        $projectMembers = $project->members()->get();

        return view('tasks.create', compact('project', 'projectMembers'));
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request, Project $project): RedirectResponse
    {
        // Check if user can manage this project
        if (!$project->userCanManage(Auth::user())) {
            abort(403, 'You do not have permission to create tasks in this project.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'required|date|after:today',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'sometimes|in:todo,in_progress,done',
        ]);

        $validated['status'] = $validated['status'] ?? 'todo';

        // Verify assigned user is a project member
        if ($validated['assigned_to'] && !$project->members()->where('user_id', $validated['assigned_to'])->exists()) {
            return back()->withErrors(['assigned_to' => 'User must be a project member.']);
        }

        $task = $project->tasks()->create($validated);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Task created successfully!');
    }

    /**
     * Display the specified task.
     */
    public function show(Project $project, Task $task): View
    {
        // Check if user can view this project
        if (!$project->userCanView(Auth::user())) {
            abort(403, 'You do not have permission to view this project.');
        }

        // Ensure task belongs to project
        if ($task->project_id !== $project->id) {
            abort(404);
        }

        $task->load(['assignedUser', 'comments.user', 'project.members']);

        return view('tasks.show', compact('project', 'task'));
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit(Project $project, Task $task): View
    {
        // Check if user can manage this project
        if (!$project->userCanManage(Auth::user())) {
            abort(403, 'You do not have permission to edit tasks in this project.');
        }

        // Ensure task belongs to project
        if ($task->project_id !== $project->id) {
            abort(404);
        }

        $projectMembers = $project->members()->get();

        return view('tasks.edit', compact('project', 'task', 'projectMembers'));
    }

    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, Project $project, Task $task): RedirectResponse
    {
        // Check if user can manage this project
        if (!$project->userCanManage(Auth::user())) {
            abort(403, 'You do not have permission to edit tasks in this project.');
        }

        // Ensure task belongs to project
        if ($task->project_id !== $project->id) {
            abort(404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'required|date',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:todo,in_progress,done',
        ]);

        // Verify assigned user is a project member
        if ($validated['assigned_to'] && !$project->members()->where('user_id', $validated['assigned_to'])->exists()) {
            return back()->withErrors(['assigned_to' => 'User must be a project member.']);
        }

        $task->update($validated);

        return redirect()->route('tasks.show', [$project, $task])
            ->with('success', 'Task updated successfully!');
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(Project $project, Task $task): RedirectResponse
    {
        // Check if user can manage this project
        if (!$project->userCanManage(Auth::user())) {
            abort(403, 'You do not have permission to delete tasks in this project.');
        }

        // Ensure task belongs to project
        if ($task->project_id !== $project->id) {
            abort(404);
        }

        $task->delete();

        return redirect()->route('projects.show', $project)
            ->with('success', 'Task deleted successfully!');
    }

    /**
     * Update task status (for AJAX/Kanban).
     */
    public function updateStatus(Request $request, Project $project, Task $task): RedirectResponse
    {
        // Check if user can manage this project or is assigned to the task
        if (!$project->userCanManage(Auth::user()) && $task->assigned_to !== Auth::id()) {
            abort(403, 'You do not have permission to update this task status.');
        }

        // Ensure task belongs to project
        if ($task->project_id !== $project->id) {
            abort(404);
        }

        $validated = $request->validate([
            'status' => 'required|in:todo,in_progress,done',
        ]);

        $task->update($validated);

        return back()->with('success', 'Task status updated successfully!');
    }

    /**
     * Get my tasks for dashboard.
     */
    public function myTasks(): View
    {
        $user = Auth::user();
        
        $tasks = $user->assignedTasks()
            ->with(['project', 'comments'])
            ->incomplete()
            ->latest()
            ->paginate(10);

        $upcomingTasks = $user->upcomingTasks()->limit(5)->get();
        $overdueTasks = $user->overdueTasks()->limit(5)->get();

        return view('tasks.my-tasks', compact('tasks', 'upcomingTasks', 'overdueTasks'));
    }
}
