@extends('layouts.app')

@section('content')
    <!-- CSRF Token for AJAX requests -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Header -->
    <div class="bg-white shadow mb-6">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ $project->name }}
                    </h2>
                    <p class="text-gray-600 mt-1">{{ $project->description }}</p>
                </div>
                <div class="flex space-x-2">
                    @if($project->userCanManage(auth()->user()))
                    @csrf
                        <a href="{{ route('projects.tasks.create', $project) }}" 
                           class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition-colors">
                            Add Task
                        </a>
                    <a href="{{ route('projects.edit', $project) }}" 
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors">
                        Edit Project
                    </a>
                    @endif
                    <a href="{{ route('projects.index') }}" 
                       class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition-colors">
                        Back to Projects
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Project Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4">
                        <div class="text-sm font-medium text-gray-500">Progress</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $project->completion_percentage }}%</div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $project->completion_percentage }}%"></div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4">
                        <div class="text-sm font-medium text-gray-500">Total Tasks</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $project->tasks->count() }}</div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4">
                        <div class="text-sm font-medium text-gray-500">Team Members</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $project->members->count() }}</div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4">
                        <div class="text-sm font-medium text-gray-500">Status</div>
                        <span class="px-2 py-1 text-sm rounded-full 
                            @if($project->status === 'active') bg-green-100 text-green-800
                            @elseif($project->status === 'completed') bg-blue-100 text-blue-800  
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($project->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Enhanced Kanban Board -->
            <div class="kanban-columns grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Todo Column -->
                <div class="kanban-column-wrapper">
                    <div class="bg-white rounded-lg shadow-sm">
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="font-semibold text-gray-800 flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-gray-400 rounded-full mr-2"></div>
                                    Todo 
                                    <span class="task-count ml-2 bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full">
                                        {{ $tasksByStatus['todo']->count() }}
                                    </span>
                                </div>
                                @if($project->userCanManage(auth()->user()))
                                    <button onclick="showQuickAddForm('todo')" 
                                            class="text-gray-400 hover:text-gray-600 transition-colors">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                @endif
                            </h3>
                        </div>
                        <div class="kanban-column p-4 space-y-3 min-h-96" data-status="todo">
                            @foreach($tasksByStatus['todo'] as $task)
                                <div class="task-card bg-gray-50 border border-gray-200 rounded-lg p-3 hover:shadow-md transition-shadow priority-{{ $task->priority }}"
                                     data-task-id="{{ $task->id }}" data-project-id="{{ $project->id }}">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="font-medium text-gray-900 text-sm">{{ $task->title }}</h4>
                                        <span class="px-2 py-1 text-xs rounded-full {{ $task->priority_color }}">
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                    </div>
                                    
                                    @if($task->description)
                                        <p class="text-gray-600 text-xs mb-2 line-clamp-2">{{ $task->description }}</p>
                                    @endif
                                    
                                    <div class="flex justify-between items-center text-xs text-gray-500 mb-2">
                                        <div class="flex items-center">
                                            @if($task->assignedUser)
                                                <div class="assignee-avatar bg-blue-500 mr-1">
                                                    {{ strtoupper(substr($task->assignedUser->name, 0, 1)) }}
                                                </div>
                                                <span>{{ $task->assignedUser->name }}</span>
                                            @else
                                                <span>Unassigned</span>
                                            @endif
                                        </div>
                                        @if($task->due_date)
                                            <span class="@if($task->is_overdue) due-overdue @elseif($task->due_date->isToday()) due-today @endif">
                                                {{ $task->due_date->format('M j') }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="flex space-x-1">
                                        <a href="{{ route('projects.tasks.show', [$project, $task]) }}" 
                                           class="text-blue-500 hover:text-blue-700 text-xs">View</a>
                                        @if($project->userCanManage(auth()->user()) || $task->assigned_to === auth()->id())
                                            <button onclick="updateTaskStatus({{ $task->id }}, {{ $project->id }}, 'in_progress')"
                                                    class="text-green-500 hover:text-green-700 text-xs ml-2">
                                                Start
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- In Progress Column -->
                <div class="kanban-column-wrapper">
                    <div class="bg-white rounded-lg shadow-sm">
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="font-semibold text-gray-800 flex items-center">
                                <div class="w-3 h-3 bg-blue-400 rounded-full mr-2"></div>
                                In Progress 
                                <span class="task-count ml-2 bg-blue-100 text-blue-600 text-xs px-2 py-1 rounded-full">
                                    {{ $tasksByStatus['in_progress']->count() }}
                                </span>
                            </h3>
                        </div>
                        <div class="kanban-column p-4 space-y-3 min-h-96" data-status="in_progress">
                            @foreach($tasksByStatus['in_progress'] as $task)
                                <div class="task-card bg-blue-50 border border-blue-200 rounded-lg p-3 hover:shadow-md transition-shadow priority-{{ $task->priority }}"
                                     data-task-id="{{ $task->id }}" data-project-id="{{ $project->id }}">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="font-medium text-gray-900 text-sm">{{ $task->title }}</h4>
                                        <span class="px-2 py-1 text-xs rounded-full {{ $task->priority_color }}">
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                    </div>
                                    
                                    @if($task->description)
                                        <p class="text-gray-600 text-xs mb-2 line-clamp-2">{{ $task->description }}</p>
                                    @endif
                                    
                                    <div class="flex justify-between items-center text-xs text-gray-500 mb-2">
                                        <div class="flex items-center">
                                            @if($task->assignedUser)
                                                <div class="assignee-avatar bg-blue-500 mr-1">
                                                    {{ strtoupper(substr($task->assignedUser->name, 0, 1)) }}
                                                </div>
                                                <span>{{ $task->assignedUser->name }}</span>
                                            @else
                                                <span>Unassigned</span>
                                            @endif
                                        </div>
                                        @if($task->due_date)
                                            <span class="@if($task->is_overdue) due-overdue @elseif($task->due_date->isToday()) due-today @endif">
                                                {{ $task->due_date->format('M j') }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="flex space-x-1">
                                        <a href="{{ route('projects.tasks.show', [$project, $task]) }}" 
                                           class="text-blue-500 hover:text-blue-700 text-xs">View</a>
                                        @if($project->userCanManage(auth()->user()) || $task->assigned_to === auth()->id())
                                            <button onclick="updateTaskStatus({{ $task->id }}, {{ $project->id }}, 'done')"
                                                    class="text-green-500 hover:text-green-700 text-xs ml-2">
                                                Complete
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Done Column -->
                <div class="kanban-column-wrapper">
                    <div class="bg-white rounded-lg shadow-sm">
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="font-semibold text-gray-800 flex items-center">
                                <div class="w-3 h-3 bg-green-400 rounded-full mr-2"></div>
                                Done 
                                <span class="task-count ml-2 bg-green-100 text-green-600 text-xs px-2 py-1 rounded-full">
                                    {{ $tasksByStatus['done']->count() }}
                                </span>
                            </h3>
                        </div>
                        <div class="kanban-column p-4 space-y-3 min-h-96" data-status="done">
                            @foreach($tasksByStatus['done'] as $task)
                                <div class="task-card bg-green-50 border border-green-200 rounded-lg p-3 hover:shadow-md transition-shadow opacity-75 priority-{{ $task->priority }}"
                                     data-task-id="{{ $task->id }}" data-project-id="{{ $project->id }}">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="font-medium text-gray-900 text-sm line-through">{{ $task->title }}</h4>
                                        <span class="px-2 py-1 text-xs rounded-full {{ $task->priority_color }}">
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                    </div>
                                    
                                    @if($task->description)
                                        <p class="text-gray-600 text-xs mb-2 line-clamp-2">{{ $task->description }}</p>
                                    @endif
                                    
                                    <div class="flex justify-between items-center text-xs text-gray-500 mb-2">
                                        <div class="flex items-center">
                                            @if($task->assignedUser)
                                                <div class="assignee-avatar bg-blue-500 mr-1">
                                                    {{ strtoupper(substr($task->assignedUser->name, 0, 1)) }}
                                                </div>
                                                <span>{{ $task->assignedUser->name }}</span>
                                            @else
                                                <span>Unassigned</span>
                                            @endif
                                        </div>
                                        @if($task->due_date)
                                            <span>{{ $task->due_date->format('M j') }}</span>
                                        @endif
                                    </div>
                                    
                                    <div>
                                        <a href="{{ route('projects.tasks.show', [$project, $task]) }}" 
                                           class="text-blue-500 hover:text-blue-700 text-xs">View</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Members -->
            @if($project->members->count() > 0)
                <div class="mt-6 bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Team Members</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($project->members as $member)
                            <div class="flex items-center bg-gray-100 rounded-full px-3 py-1">
                                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs mr-2">
                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                </div>
                                <span class="text-sm text-gray-700">{{ $member->name }}</span>
                                <span class="text-xs text-gray-500 ml-1">({{ $member->pivot->role }})</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Add Task Modal -->
    <div id="quickAddModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-bold text-gray-900 text-center mb-4">Quick Add Task</h3>
                <form id="quickAddForm" class="space-y-4">
                    @csrf
                    <input type="hidden" id="quickTaskStatus" name="status" value="todo">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" id="quickTaskTitle" name="title" required
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Priority</label>
                        <select id="quickTaskPriority" name="priority" 
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                    
                    <div class="flex justify-end space-x-2 pt-4">
                        <button type="button" onclick="hideQuickAddForm()" 
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors">
                            Create Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Quick add form functions
        function showQuickAddForm(status) {
            document.getElementById('quickTaskStatus').value = status;
            document.getElementById('quickAddModal').classList.remove('hidden');
            document.getElementById('quickTaskTitle').focus();
        }

        function hideQuickAddForm() {
            document.getElementById('quickAddModal').classList.add('hidden');
            document.getElementById('quickAddForm').reset();
        }

        // Handle quick add form submission
        document.getElementById('quickAddForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const projectId = {{ $project->id }};
            
            fetch(`/projects/${projectId}/tasks`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    hideQuickAddForm();
                    showNotification('Task created successfully!', 'success');
                    // Reload the page to show the new task
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showNotification('Failed to create task', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to create task', 'error');
            });
        });

        // Close modal when clicking outside
        document.getElementById('quickAddModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideQuickAddForm();
            }
        });
    </script>
@endsection