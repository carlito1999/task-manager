<!-- Subtasks Section -->
<div class="mt-8">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-900">
            Subtasks 
            @if($task->subtasks->count() > 0)
                <span class="text-sm font-normal text-gray-500">({{ $task->subtask_progress['completed'] }}/{{ $task->subtask_progress['total'] }} completed)</span>
            @endif
        </h3>
        <button type="button" 
                onclick="toggleSubtaskForm()" 
                class="btn-success text-sm">
            Add Subtask
        </button>
    </div>

    <!-- Progress Bar -->
    @if($task->subtasks->count() > 0)
        <div class="mb-6">
            <div class="flex justify-between items-center mb-1">
                <span class="text-sm font-medium text-gray-700">Progress</span>
                <span class="text-sm font-medium text-gray-700">{{ $task->subtask_progress['percentage'] }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" 
                     style="width: {{ $task->subtask_progress['percentage'] }}%"
                     id="progress-bar"></div>
            </div>
        </div>
    @endif

    <!-- Add Subtask Form (Initially Hidden) -->
    <div id="subtask-form" class="hidden mb-6 p-4 bg-gray-50 rounded-lg border">
        <form method="POST" action="{{ route('subtasks.store', [$project, $task]) }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label for="subtask_title" class="block text-sm font-medium text-gray-700">Title *</label>
                    <input type="text" 
                           name="title" 
                           id="subtask_title" 
                           required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="subtask_description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" 
                              id="subtask_description" 
                              rows="2"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                </div>

                <!-- Priority -->
                <div>
                    <label for="subtask_priority" class="block text-sm font-medium text-gray-700">Priority</label>
                    <select name="priority" 
                            id="subtask_priority"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>

                <!-- Assigned To -->
                <div>
                    <label for="subtask_assigned_to" class="block text-sm font-medium text-gray-700">Assign To</label>
                    <select name="assigned_to" 
                            id="subtask_assigned_to"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Unassigned</option>
                        @foreach($project->members as $member)
                            <option value="{{ $member->id }}">{{ $member->name }}</option>
                        @endforeach
                        @if(!$project->members->contains($task->assignedUser))
                            <option value="{{ $task->assignedUser->id }}">{{ $task->assignedUser->name }}</option>
                        @endif
                    </select>
                </div>

                <!-- Due Date -->
                <div>
                    <label for="subtask_due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                    <input type="date" 
                           name="due_date" 
                           id="subtask_due_date"
                           min="{{ date('Y-m-d') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
            </div>

            <div class="mt-4 flex space-x-2">
                <button type="submit" 
                        class="btn-primary text-sm">
                    Create Subtask
                </button>
                <button type="button" 
                        onclick="toggleSubtaskForm()" 
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm">
                    Cancel
                </button>
            </div>
        </form>
    </div>

    <!-- Subtasks List -->
    <div id="subtasks-list" class="space-y-3">
        @forelse($task->subtasks as $subtask)
            <div class="bg-white border rounded-lg p-4 shadow-sm" data-subtask-id="{{ $subtask->id }}">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-3 flex-1">
                        <!-- Status Checkbox -->
                        <div class="flex items-center pt-1">
                            <input type="checkbox" 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded subtask-checkbox"
                                   data-subtask-id="{{ $subtask->id }}"
                                   {{ $subtask->status === 'done' ? 'checked' : '' }}>
                        </div>

                        <!-- Subtask Content -->
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-1">
                                <h4 class="font-medium text-gray-900 {{ $subtask->status === 'done' ? 'line-through text-gray-500' : '' }}">
                                    {{ $subtask->title }}
                                </h4>
                                
                                <!-- Priority Badge -->
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $subtask->priority_badge_color }}">
                                    {{ ucfirst($subtask->priority) }}
                                </span>

                                <!-- Status Badge -->
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $subtask->status_badge_color }}">
                                    {{ ucfirst(str_replace('_', ' ', $subtask->status)) }}
                                </span>
                            </div>

                            @if($subtask->description)
                                <p class="text-sm text-gray-600 mb-2">{{ $subtask->description }}</p>
                            @endif

                            <div class="flex items-center space-x-4 text-xs text-gray-500">
                                @if($subtask->assignedUser)
                                    <span>Assigned to: {{ $subtask->assignedUser->name }}</span>
                                @endif
                                
                                @if($subtask->due_date)
                                    <span class="{{ $subtask->isOverdue() ? 'text-red-600 font-medium' : '' }}">
                                        Due: {{ $subtask->due_date->format('M j, Y') }}
                                        @if($subtask->isOverdue())
                                            (Overdue)
                                        @endif
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center space-x-2 ml-4">
                        <button type="button" 
                                onclick="editSubtask({{ $subtask->id }})"
                                class="text-blue-600 hover:text-blue-800 text-sm">
                            Edit
                        </button>
                        <form method="POST" 
                              action="{{ route('subtasks.destroy', [$project, $task, $subtask]) }}" 
                              class="inline"
                              onsubmit="return confirm('Are you sure you want to delete this subtask?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                <p class="mt-2">No subtasks yet. Add one to break down this task!</p>
            </div>
        @endforelse
    </div>
</div>

<script>
function toggleSubtaskForm() {
    const form = document.getElementById('subtask-form');
    form.classList.toggle('hidden');
    
    if (!form.classList.contains('hidden')) {
        document.getElementById('subtask_title').focus();
    }
}

// Handle subtask status changes
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.subtask-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const subtaskId = this.dataset.subtaskId;
            const isChecked = this.checked;
            const status = isChecked ? 'done' : 'todo';
            
            // Update status via AJAX
            fetch(`{{ route('subtasks.update-status', [$project, $task, '__SUBTASK_ID__']) }}`.replace('__SUBTASK_ID__', subtaskId), {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update progress bar
                    const progressBar = document.getElementById('progress-bar');
                    if (progressBar) {
                        progressBar.style.width = data.progress.percentage + '%';
                    }
                    
                    // Update progress text
                    const progressText = document.querySelector('h3 span');
                    if (progressText) {
                        progressText.textContent = `(${data.progress.completed}/${data.progress.total} completed)`;
                    }
                    
                    // Update task status in parent if needed
                    console.log('Task status updated to:', data.task_status);
                    
                    // Refresh page to show updated task status
                    if (data.task_status === 'done') {
                        setTimeout(() => window.location.reload(), 500);
                    }
                } else {
                    // Revert checkbox if failed
                    this.checked = !isChecked;
                    alert('Failed to update subtask status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Revert checkbox if failed
                this.checked = !isChecked;
                alert('Failed to update subtask status');
            });
        });
    });
});

function editSubtask(subtaskId) {
    // For now, just show an alert. You can implement a modal later
    alert('Edit functionality coming soon! For now, you can delete and recreate the subtask.');
}
</script>