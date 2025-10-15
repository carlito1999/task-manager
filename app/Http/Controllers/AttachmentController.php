<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AttachmentController extends Controller
{
    /**
     * Store a newly uploaded attachment.
     */
    public function store(Request $request, Project $project, Task $task): JsonResponse
    {
        // Check if user can view this project
        if (!$project->userCanView(Auth::user())) {
            abort(403, 'You do not have permission to upload files to this project.');
        }

        // Ensure task belongs to project
        if ($task->project_id !== $project->id) {
            abort(404);
        }

        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
        ]);

        $file = $request->file('file');
        
        // Generate unique filename
        $filename = time() . '_' . $file->getClientOriginalName();
        
        // Store file in public disk under attachments directory
        $path = $file->storeAs('attachments', $filename, 'public');

        // Create attachment record
        $attachment = $task->attachments()->create([
            'user_id' => Auth::id(),
            'filename' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);

        $attachment->load('user');

        // Return JSON response with attachment HTML
        $attachmentHtml = view('partials.attachment', compact('attachment', 'project', 'task'))->render();

        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully!',
            'attachmentHtml' => $attachmentHtml,
            'attachment' => $attachment
        ]);
    }

    /**
     * Download an attachment.
     */
    public function download(Attachment $attachment): BinaryFileResponse
    {
        // Check if user can view the project this attachment belongs to
        $project = $attachment->task->project;
        if (!$project->userCanView(Auth::user())) {
            abort(403, 'You do not have permission to download this file.');
        }

        $filePath = Storage::disk('public')->path($attachment->path);
        
        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }

        return response()->download($filePath, $attachment->filename);
    }

    /**
     * Delete an attachment.
     */
    public function destroy(Project $project, Task $task, Attachment $attachment): JsonResponse
    {
        // Check if user owns this attachment or can manage the project
        if ($attachment->user_id !== Auth::id() && !$project->userCanManage(Auth::user())) {
            abort(403, 'You do not have permission to delete this file.');
        }

        // Ensure attachment belongs to task
        if ($attachment->task_id !== $task->id) {
            abort(404);
        }

        $attachment->delete();

        return response()->json([
            'success' => true,
            'message' => 'File deleted successfully!'
        ]);
    }
}
