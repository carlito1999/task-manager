@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $task->title }}</h1>
                <div class="flex items-center space-x-4 mt-2 text-sm text-gray-600">
                    <a href="{{ route('projects.show', $project) }}" class="text-blue-600 hover:text-blue-800">
                        ← Back to {{ $project->name }}
                    </a>
                    <span class="text-gray-400">|</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium status-badge
                        @if($task->status === 'todo') status-todo
                        @elseif($task->status === 'in_progress') status-in-progress
                        @else status-done @endif">
                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium priority-badge
                        @if($task->priority === 'low') priority-low
                        @elseif($task->priority === 'medium') priority-medium
                        @else priority-high @endif">
                        {{ ucfirst($task->priority) }} Priority
                    </span>
                </div>
            </div>
            
            @if($project->userCanManage(auth()->user()) || $task->assigned_to === auth()->id())
                <div class="flex space-x-2">
                    <a href="{{ route('projects.tasks.edit', [$project, $task]) }}" 
                       class="btn-sm">
                        Edit Task
                    </a>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Task Details -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Description</h2>
                    <div class="prose text-gray-700">
                        @if($task->description)
                            {!! nl2br(e($task->description)) !!}
                        @else
                            <p class="text-gray-500 italic">No description provided.</p>
                        @endif
                    </div>
                </div>

                <!-- Subtasks Section -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    @include('partials.subtasks', ['task' => $task, 'project' => $project])
                </div>

                <!-- Comments Section -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">
                        Comments 
                        <span class="text-sm font-normal text-gray-500">({{ $task->comments->count() }})</span>
                    </h2>

                    <!-- Add Comment Form -->
                    @auth
                        <form id="comment-form" action="{{ route('comments.store', [$project, $task]) }}" method="POST" class="mb-6">
                            @csrf
                            <div class="flex space-x-3">
                                <img class="w-8 h-8 rounded-full" 
                                     src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=3B82F6&color=fff" 
                                     alt="{{ auth()->user()->name }}">
                                <div class="flex-1">
                                    <textarea name="content" 
                                              placeholder="Add a comment..." 
                                              rows="3"
                                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                                              required></textarea>
                                    <div class="mt-2">
                                        <button type="submit" 
                                                class="btn-sm">
                                            Post Comment
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @endauth

                    <!-- Comments List -->
                    <div id="comments-list" class="space-y-4">
                        @forelse($task->comments as $comment)
                            <div class="flex space-x-3 comment-item" data-comment-id="{{ $comment->id }}">
                                <img class="w-8 h-8 rounded-full" 
                                     src="https://ui-avatars.com/api/?name={{ urlencode($comment->user->name) }}&background=3B82F6&color=fff" 
                                     alt="{{ $comment->user->name }}">
                                <div class="flex-1">
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <div class="flex justify-between items-start mb-1">
                                            <h4 class="font-medium text-gray-900">{{ $comment->user->name }}</h4>
                                            <div class="flex items-center space-x-2">
                                                <time class="text-xs text-gray-500" datetime="{{ $comment->created_at->toISOString() }}">
                                                    {{ $comment->created_at->diffForHumans() }}
                                                </time>
                                                @if($comment->user_id === auth()->id())
                                                    <button onclick="deleteComment({{ $comment->id }})" 
                                                            class="text-xs text-red-600 hover:text-red-800">
                                                        Delete
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                        <p class="text-gray-700 text-sm">{!! nl2br(e($comment->content)) !!}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.974 8.974 0 01-4.315-1.1L3 20l1.1-5.685A8.974 8.974 0 013 12a8 8 0 018-8 8 8 0 018 8z"></path>
                                </svg>
                                <p>No comments yet. Be the first to comment!</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Attachments Section -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">
                        Attachments 
                        <span class="text-sm font-normal text-gray-500">({{ $task->attachments->count() }})</span>
                    </h2>

                    <!-- Upload Form -->
                    @auth
                        <form id="attachment-form" action="{{ route('attachments.store', [$project, $task]) }}" method="POST" enctype="multipart/form-data" class="mb-6">
                            @csrf
                            <div class="flex items-center space-x-3">
                                <div class="flex-1">
                                    <input type="file" 
                                           id="file-input"
                                           name="file" 
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                           accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif,.zip,.rar"
                                           required>
                                    <p class="mt-1 text-xs text-gray-500">Max file size: 10MB. Supported formats: PDF, DOC, XLS, PPT, Images, ZIP</p>
                                </div>
                                <button type="submit" 
                                        class="bg-blue-600 text-white px-4 py-2 text-sm rounded-md hover:bg-blue-700 transition-colors whitespace-nowrap">
                                    Upload File
                                </button>
                            </div>
                        </form>
                    @endauth

                    <!-- Attachments List -->
                    <div id="attachments-list" class="space-y-3">
                        @forelse($task->attachments as $attachment)
                            @include('partials.attachment', ['attachment' => $attachment, 'project' => $project, 'task' => $task])
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                </svg>
                                <p>No attachments yet. Upload your first file!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Task Info -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Task Details</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Assigned to</dt>
                            <dd class="mt-1">
                                @if($task->assignedUser)
                                    <div class="flex items-center space-x-2">
                                        <img class="w-6 h-6 rounded-full" 
                                             src="https://ui-avatars.com/api/?name={{ urlencode($task->assignedUser->name) }}&background=3B82F6&color=fff" 
                                             alt="{{ $task->assignedUser->name }}">
                                        <span class="text-sm text-gray-900">{{ $task->assignedUser->name }}</span>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-500">Unassigned</span>
                                @endif
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Due Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($task->due_date)
                                    <span class="@if($task->is_overdue) text-red-600 font-medium @endif">
                                        {{ $task->due_date->format('F j, Y') }}
                                        @if($task->is_overdue)
                                            (Overdue)
                                        @endif
                                    </span>
                                @else
                                    <span class="text-gray-500">No due date</span>
                                @endif
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $task->created_at->format('F j, Y') }}</dd>
                        </div>
                        
                        @if($task->updated_at != $task->created_at)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $task->updated_at->diffForHumans() }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                <!-- Quick Actions -->
                @if($project->userCanManage(auth()->user()) || $task->assigned_to === auth()->id())
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                        <div class="space-y-2">
                            @if($task->status !== 'in_progress')
                                <form method="POST" action="{{ route('tasks.update-status', [$project, $task]) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="in_progress">
                                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 text-sm rounded-md hover:bg-blue-700 transition-colors">
                                        Start Progress
                                    </button>
                                </form>
                            @endif
                            
                            @if($task->status !== 'done')
                                <form method="POST" action="{{ route('tasks.update-status', [$project, $task]) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="done">
                                    <button type="submit" class="w-full btn-sm-success">
                                        Mark as Complete
                                    </button>
                                </form>
                            @endif
                            
                            @if($task->status === 'done')
                                <form method="POST" action="{{ route('tasks.update-status', [$project, $task]) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="todo">
                                    <button type="submit" class="w-full bg-gray-600 text-white px-4 py-2 text-sm rounded-md hover:bg-gray-700 transition-colors">
                                        Reopen Task
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Scripts loaded'); // Debug log
    
    // AJAX form submission for comments
    const commentForm = document.getElementById('comment-form');
    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const formData = new FormData(form);
            const submitButton = form.querySelector('button[type="submit"]');
            const textarea = form.querySelector('textarea[name="content"]');
            
            // Disable form
            submitButton.disabled = true;
            submitButton.textContent = 'Posting...';
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Add new comment to the list
                    const commentsList = document.getElementById('comments-list');
                    const emptyState = commentsList.querySelector('.text-center');
                    
                    // Remove empty state if it exists
                    if (emptyState) {
                        emptyState.remove();
                    }
                    
                    // Add new comment HTML
                    commentsList.insertAdjacentHTML('beforeend', data.commentHtml);
                    
                    // Clear form
                    textarea.value = '';
                    
                    // Update comment count
                    const commentSection = commentsList.closest('.bg-white');
                    const countElement = commentSection.querySelector('h2 span');
                    if (countElement) {
                        const currentCount = parseInt(countElement.textContent.match(/\d+/)[0]);
                        countElement.textContent = `(${currentCount + 1})`;
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error posting comment. Please try again.');
            })
            .finally(() => {
                // Re-enable form
                submitButton.disabled = false;
                submitButton.textContent = 'Post Comment';
            });
        });
    }

    // AJAX form submission for attachments
    const attachmentForm = document.getElementById('attachment-form');
    if (attachmentForm) {
        console.log('Attachment form found'); // Debug log
        
        attachmentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Form submission prevented'); // Debug log
            
            const form = this;
            const formData = new FormData(form);
            const submitButton = form.querySelector('button[type="submit"]');
            const fileInput = form.querySelector('input[type="file"]');
            
            if (!fileInput.files.length) {
                alert('Please select a file to upload.');
                return;
            }
            
            console.log('Starting upload...'); // Debug log
            
            // Disable form
            submitButton.disabled = true;
            submitButton.textContent = 'Uploading...';
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                console.log('Response received:', response); // Debug log
                return response.json();
            })
            .then(data => {
                console.log('Data received:', data); // Debug log
                if (data.success) {
                    // Add new attachment to the list
                    const attachmentsList = document.getElementById('attachments-list');
                    const emptyState = attachmentsList.querySelector('.text-center');
                    
                    // Remove empty state if it exists
                    if (emptyState) {
                        emptyState.remove();
                    }
                    
                    // Add new attachment HTML
                    attachmentsList.insertAdjacentHTML('beforeend', data.attachmentHtml);
                    
                    // Clear form
                    fileInput.value = '';
                    
                    // Update attachment count
                    const attachmentSection = attachmentsList.closest('.bg-white');
                    const countElement = attachmentSection.querySelector('h2 span');
                    if (countElement) {
                        const currentCount = parseInt(countElement.textContent.match(/\d+/)[0]);
                        countElement.textContent = `(${currentCount + 1})`;
                    }
                } else {
                    alert(data.message || 'Error uploading file. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error uploading file. Please try again.');
            })
            .finally(() => {
                // Re-enable form
                submitButton.disabled = false;
                submitButton.textContent = 'Upload File';
            });
        });
    } else {
        console.log('Attachment form not found'); // Debug log
    }
});

// Delete comment function
function deleteComment(commentId) {
    if (!confirm('Are you sure you want to delete this comment?')) {
        return;
    }
    
    fetch(`{{ route('comments.destroy', [$project, $task, ':id']) }}`.replace(':id', commentId), {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove comment from DOM
            const commentElement = document.querySelector(`[data-comment-id="${commentId}"]`);
            if (commentElement) {
                commentElement.remove();
            }
            
            // Update comment count
            const commentsList = document.getElementById('comments-list');
            const commentSection = commentsList.closest('.bg-white');
            const countElement = commentSection.querySelector('h2 span');
            if (countElement) {
                const currentCount = parseInt(countElement.textContent.match(/\d+/)[0]);
                const newCount = Math.max(0, currentCount - 1);
                countElement.textContent = `(${newCount})`;
                
                // Show empty state if no comments left
                if (newCount === 0) {
                    commentsList.innerHTML = `
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.974 8.974 0 01-4.315-1.1L3 20l1.1-5.685A8.974 8.974 0 013 12a8 8 0 018-8 8 8 0 018 8z"></path>
                            </svg>
                            <p>No comments yet. Be the first to comment!</p>
                        </div>
                    `;
                }
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error deleting comment. Please try again.');
    });
}

// Delete attachment function
function deleteAttachment(attachmentId) {
    if (!confirm('Are you sure you want to delete this file?')) {
        return;
    }
    
    fetch(`{{ route('attachments.destroy', [$project, $task, ':id']) }}`.replace(':id', attachmentId), {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove attachment from DOM
            const attachmentElement = document.querySelector(`[data-attachment-id="${attachmentId}"]`);
            if (attachmentElement) {
                attachmentElement.remove();
            }
            
            // Update attachment count
            const attachmentsList = document.getElementById('attachments-list');
            const attachmentSection = attachmentsList.closest('.bg-white');
            const countElement = attachmentSection.querySelector('h2 span');
            if (countElement) {
                const currentCount = parseInt(countElement.textContent.match(/\d+/)[0]);
                const newCount = Math.max(0, currentCount - 1);
                countElement.textContent = `(${newCount})`;
                
                // Show empty state if no attachments left
                if (newCount === 0) {
                    attachmentsList.innerHTML = `
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                            </svg>
                            <p>No attachments yet. Upload your first file!</p>
                        </div>
                    `;
                }
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error deleting file. Please try again.');
    });
}
</script>
@endpush

@endsection
