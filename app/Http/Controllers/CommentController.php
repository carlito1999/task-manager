<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store a newly created comment.
     */
    public function store(Request $request, Project $project, Task $task): RedirectResponse
    {
        // Check if user can view this project
        if (!$project->userCanView(Auth::user())) {
            abort(403, 'You do not have permission to comment on this project.');
        }

        // Ensure task belongs to project
        if ($task->project_id !== $project->id) {
            abort(404);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $validated['user_id'] = Auth::id();

        $task->comments()->create($validated);

        return back()->with('success', 'Comment added successfully!');
    }

    /**
     * Update the specified comment.
     */
    public function update(Request $request, Project $project, Task $task, Comment $comment): RedirectResponse
    {
        // Check if user owns this comment
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'You can only edit your own comments.');
        }

        // Ensure comment belongs to task
        if ($comment->task_id !== $task->id) {
            abort(404);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->update($validated);

        return back()->with('success', 'Comment updated successfully!');
    }

    /**
     * Remove the specified comment.
     */
    public function destroy(Project $project, Task $task, Comment $comment): RedirectResponse
    {
        // Check if user owns this comment or can manage the project
        if ($comment->user_id !== Auth::id() && !$project->userCanManage(Auth::user())) {
            abort(403, 'You do not have permission to delete this comment.');
        }

        // Ensure comment belongs to task
        if ($comment->task_id !== $task->id) {
            abort(404);
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted successfully!');
    }
}
