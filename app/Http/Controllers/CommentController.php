<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use App\Models\Project;
use App\Mail\CommentAdded;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CommentController extends Controller
{
    /**
     * Store a newly created comment.
     */
    public function store(Request $request, Project $project, Task $task)
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

        $comment = $task->comments()->create($validated);
        $comment->load('user');

        // Send email notifications to relevant users (except comment author)
        $usersToNotify = collect();
        
        // Add task assignee
        if ($task->assigned_to && $task->assigned_to !== Auth::id()) {
            $usersToNotify->push($task->assignedUser);
        }
        
        // Add project owner
        if ($task->project->user_id !== Auth::id()) {
            $usersToNotify->push($task->project->user);
        }
        
        // Add other project members who have commented on this task
        $otherCommenters = $task->comments()
            ->where('user_id', '!=', Auth::id())
            ->pluck('user_id')
            ->unique();
        
        foreach ($otherCommenters as $userId) {
            $user = \App\Models\User::find($userId);
            if ($user && !$usersToNotify->contains('id', $userId)) {
                $usersToNotify->push($user);
            }
        }
        
        // Send emails
        foreach ($usersToNotify as $user) {
            Mail::to($user)->send(new CommentAdded($comment, $task));
        }

        // Return JSON for AJAX requests
        if ($request->wantsJson() || $request->ajax()) {
            $commentHtml = view('partials.comment', compact('comment', 'project', 'task'))->render();
            
            return response()->json([
                'success' => true,
                'message' => 'Comment added successfully!',
                'commentHtml' => $commentHtml,
                'comment' => $comment
            ]);
        }

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
    public function destroy(Project $project, Task $task, Comment $comment)
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

        // Return JSON for AJAX requests
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Comment deleted successfully!'
            ]);
        }

        return back()->with('success', 'Comment deleted successfully!');
    }
}
