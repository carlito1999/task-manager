<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with user statistics and recent activity.
     */
    public function index(): View
    {
        $user = Auth::user();

        // Get user's projects
        $userProjects = Project::where('user_id', $user->id)
            ->orWhereHas('members', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['tasks', 'members'])
            ->latest()
            ->limit(5)
            ->get();

        // Get user's tasks statistics with basic counts
        $totalTasks = Task::where('assigned_to', $user->id)->count();
        $completedTasks = Task::where('assigned_to', $user->id)->where('status', 'done')->count();
        $inProgressTasks = Task::where('assigned_to', $user->id)->where('status', 'in_progress')->count();
        $todoTasks = Task::where('assigned_to', $user->id)->where('status', 'todo')->count();
        $overdueTasks = Task::where('assigned_to', $user->id)
            ->where('due_date', '<', now())
            ->where('status', '!=', 'done')
            ->count();

        $taskStats = [
            'total' => $totalTasks,
            'completed' => $completedTasks,
            'in_progress' => $inProgressTasks,
            'todo' => $todoTasks,
            'overdue' => $overdueTasks,
        ];

        // Get user's projects statistics  
        $totalProjects = Project::where('user_id', $user->id)->count();
        $activeProjects = Project::where('user_id', $user->id)->where('status', 'active')->count();
        $completedProjects = Project::where('user_id', $user->id)->where('status', 'completed')->count();
        $ownedProjects = Project::where('user_id', $user->id)->count();

        $projectStats = [
            'total' => $totalProjects,
            'active' => $activeProjects,
            'completed' => $completedProjects,
            'owned' => $ownedProjects,
        ];

        // Get upcoming tasks (due in next 7 days)
        $upcomingTasks = Task::where('assigned_to', $user->id)
            ->where('due_date', '>=', now())
            ->where('due_date', '<=', now()->addDays(7))
            ->with(['project'])
            ->orderBy('due_date', 'asc')
            ->limit(5)
            ->get();

        // Get overdue tasks
        $overdueTasks = Task::where('assigned_to', $user->id)
            ->where('due_date', '<', now())
            ->where('status', '!=', 'done')
            ->with(['project'])
            ->orderBy('due_date', 'asc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'userProjects',
            'taskStats', 
            'projectStats',
            'upcomingTasks',
            'overdueTasks'
        ));
    }
}
