<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\Subtask;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class SubtaskController extends Controller
{
    /**
     * Store a newly created subtask.
     */
    public function store(Request $request, Project $project, Task $task): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date|after_or_equal:today',
        ]);

        $subtask = $task->subtasks()->create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'assigned_to' => $request->assigned_to,
            'due_date' => $request->due_date,
            'sort_order' => $task->subtasks()->max('sort_order') + 1,
        ]);

        return redirect()->route('projects.tasks.show', [$project, $task])
            ->with('success', 'Subtask created successfully!');
    }

    /**
     * Update the specified subtask.
     */
    public function update(Request $request, Project $project, Task $task, Subtask $subtask): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,in_progress,done',
            'priority' => 'required|in:low,medium,high',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
        ]);

        $subtask->update($request->only([
            'title', 'description', 'status', 'priority', 'assigned_to', 'due_date'
        ]));

        // Update parent task status based on subtask completion
        $task->updateStatusFromSubtasks();

        return redirect()->route('projects.tasks.show', [$project, $task])
            ->with('success', 'Subtask updated successfully!');
    }

    /**
     * Update subtask status (AJAX endpoint).
     */
    public function updateStatus(Request $request, Project $project, Task $task, Subtask $subtask): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:todo,in_progress,done',
        ]);

        $subtask->update(['status' => $request->status]);
        
        // Update parent task status
        $task->updateStatusFromSubtasks();
        
        // Get updated progress
        $progress = $task->fresh()->subtask_progress;

        return response()->json([
            'success' => true,
            'message' => 'Subtask status updated successfully!',
            'progress' => $progress,
            'task_status' => $task->fresh()->status,
        ]);
    }

    /**
     * Remove the specified subtask.
     */
    public function destroy(Project $project, Task $task, Subtask $subtask): RedirectResponse
    {
        $subtask->delete();

        // Update parent task status after deletion
        $task->updateStatusFromSubtasks();

        return redirect()->route('projects.tasks.show', [$project, $task])
            ->with('success', 'Subtask deleted successfully!');
    }

    /**
     * Update subtasks order (AJAX endpoint).
     */
    public function updateOrder(Request $request, Project $project, Task $task): JsonResponse
    {
        $request->validate([
            'subtasks' => 'required|array',
            'subtasks.*' => 'exists:subtasks,id',
        ]);

        foreach ($request->subtasks as $index => $subtaskId) {
            Subtask::where('id', $subtaskId)
                ->where('task_id', $task->id)
                ->update(['sort_order' => $index + 1]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Subtask order updated successfully!',
        ]);
    }
}
