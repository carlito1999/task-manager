<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the user's projects.
     */
    public function index(): View
    {
        $user = Auth::user();
        
        $projects = Project::forUser($user)
            ->with(['owner', 'members', 'tasks'])
            ->latest()
            ->paginate(10);

        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new project.
     */
    public function create(): View
    {
        return view('projects.create');
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date|after:today',
            'status' => 'required|in:active,completed,archived',
        ]);

        $project = Auth::user()->ownedProjects()->create($validated);

        // Add creator as owner in pivot table
        $project->members()->attach(Auth::id(), ['role' => 'owner']);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project created successfully!');
    }

    /**
     * Display the specified project.
     */
    public function show(Project $project): View
    {
        // Check if user can view this project
        if (!$project->userCanView(Auth::user())) {
            abort(403, 'You do not have permission to view this project.');
        }

        $project->load([
            'owner',
            'members',
            'tasks.assignedUser',
            'tasks.comments.user'
        ]);

        $tasksByStatus = $project->tasksByStatus();
        
        return view('projects.show', compact('project', 'tasksByStatus'));
    }

    /**
     * Show the form for editing the specified project.
     */
    public function edit(Project $project): View
    {
        // Check if user can manage this project
        if (!$project->userCanManage(Auth::user())) {
            abort(403, 'You do not have permission to edit this project.');
        }

        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified project in storage.
     */
    public function update(Request $request, Project $project): RedirectResponse
    {
        // Check if user can manage this project
        if (!$project->userCanManage(Auth::user())) {
            abort(403, 'You do not have permission to update this project.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
            'status' => 'required|in:active,completed,archived',
        ]);

        $project->update($validated);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project updated successfully!');
    }

    /**
     * Remove the specified project from storage.
     */
    public function destroy(Project $project): RedirectResponse
    {
        // Only owner can delete project
        if ($project->user_id !== Auth::id()) {
            abort(403, 'Only the project owner can delete this project.');
        }

        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully!');
    }

    /**
     * Add a member to the project.
     */
    public function addMember(Request $request, Project $project): RedirectResponse
    {
        // Check if user can manage this project
        if (!$project->userCanManage(Auth::user())) {
            abort(403, 'You do not have permission to add members to this project.');
        }

        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
            'role' => 'required|in:member,viewer',
        ]);

        $user = User::where('email', $validated['email'])->first();

        // Check if user is already a member
        if ($project->members()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'User is already a member of this project.');
        }

        $project->members()->attach($user->id, ['role' => $validated['role']]);

        return back()->with('success', "Added {$user->name} as {$validated['role']} to the project.");
    }

    /**
     * Remove a member from the project.
     */
    public function removeMember(Project $project, User $user): RedirectResponse
    {
        // Check if user can manage this project
        if (!$project->userCanManage(Auth::user())) {
            abort(403, 'You do not have permission to remove members from this project.');
        }

        // Cannot remove owner
        if ($project->user_id === $user->id) {
            return back()->with('error', 'Cannot remove project owner.');
        }

        $project->members()->detach($user->id);

        return back()->with('success', "Removed {$user->name} from the project.");
    }

    /**
     * Get project statistics for dashboard.
     */
    public function stats(): array
    {
        $user = Auth::user();
        
        $projects = Project::forUser($user);
        
        return [
            'total' => $projects->count(),
            'active' => $projects->where('status', 'active')->count(),
            'completed' => $projects->where('status', 'completed')->count(),
            'archived' => $projects->where('status', 'archived')->count(),
        ];
    }
}