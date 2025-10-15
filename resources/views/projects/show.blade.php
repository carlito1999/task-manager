@extends('layouts.app')

@section('content')
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
                        <a href="{{ route('projects.tasks.create', $project) }}" 
                           class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Add Task
                        </a>
                    <a href="{{ route('projects.edit', $project) }}" 
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Edit Project
                    </a>
                @endif
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

            <!-- Kanban Board -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Todo Column -->
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="font-semibold text-gray-800 flex items-center">
                            <div class="w-3 h-3 bg-gray-400 rounded-full mr-2"></div>
                            Todo 
                            <span class="ml-2 bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full">
                                {{ $tasksByStatus['todo']->count() }}
                            </span>
                        </h3>
                    </div>
                    <div class="p-4 space-y-3 min-h-96">
                        @foreach($tasksByStatus['todo'] as $task)
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 hover:shadow-md transition-shadow">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-medium text-gray-900 text-sm">{{ $task->title }}</h4>
                                    <span class="px-2 py-1 text-xs rounded-full {{ $task->priority_color }}">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </div>
                                
                                @if($task->description)
                                    <p class="text-gray-600 text-xs mb-2 line-clamp-2">{{ $task->description }}</p>
                                @endif
                                
                                <div class="flex justify-between items-center text-xs text-gray-500">
                                    <span>
                                        @if($task->assignedUser)
                                            {{ $task->assignedUser->name }}
                                        @else
                                            Unassigned
                                        @endif
                                    </span>
                                    @if($task->due_date)
                                        <span class="@if($task->is_overdue) text-red-600 @endif">
                                            {{ $task->due_date->format('M j') }}
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="mt-2 flex space-x-1">
                                    <a href="{{ route('projects.tasks.show', [$project, $task]) }}" 
                                       class="text-blue-500 hover:text-blue-700 text-xs">View</a>
                                    @if($project->userCanManage(auth()->user()) || $task->assigned_to === auth()->id())
                                        <form method="POST" action="{{ route('tasks.update-status', [$project, $task]) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="in_progress">
                                            <button type="submit" class="text-green-500 hover:text-green-700 text-xs ml-2">
                                                Start
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- In Progress Column -->
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="font-semibold text-gray-800 flex items-center">
                            <div class="w-3 h-3 bg-blue-400 rounded-full mr-2"></div>
                            In Progress 
                            <span class="ml-2 bg-blue-100 text-blue-600 text-xs px-2 py-1 rounded-full">
                                {{ $tasksByStatus['in_progress']->count() }}
                            </span>
                        </h3>
                    </div>
                    <div class="p-4 space-y-3 min-h-96">
                        @foreach($tasksByStatus['in_progress'] as $task)
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 hover:shadow-md transition-shadow">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-medium text-gray-900 text-sm">{{ $task->title }}</h4>
                                    <span class="px-2 py-1 text-xs rounded-full {{ $task->priority_color }}">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </div>
                                
                                @if($task->description)
                                    <p class="text-gray-600 text-xs mb-2 line-clamp-2">{{ $task->description }}</p>
                                @endif
                                
                                <div class="flex justify-between items-center text-xs text-gray-500">
                                    <span>
                                        @if($task->assignedUser)
                                            {{ $task->assignedUser->name }}
                                        @else
                                            Unassigned
                                        @endif
                                    </span>
                                    @if($task->due_date)
                                        <span class="@if($task->is_overdue) text-red-600 @endif">
                                            {{ $task->due_date->format('M j') }}
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="mt-2 flex space-x-1">
                                    <a href="{{ route('projects.tasks.show', [$project, $task]) }}" 
                                       class="text-blue-500 hover:text-blue-700 text-xs">View</a>
                                    @if($project->userCanManage(auth()->user()) || $task->assigned_to === auth()->id())
                                        <form method="POST" action="{{ route('tasks.update-status', [$project, $task]) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="done">
                                            <button type="submit" class="text-green-500 hover:text-green-700 text-xs ml-2">
                                                Complete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Done Column -->
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="font-semibold text-gray-800 flex items-center">
                            <div class="w-3 h-3 bg-green-400 rounded-full mr-2"></div>
                            Done 
                            <span class="ml-2 bg-green-100 text-green-600 text-xs px-2 py-1 rounded-full">
                                {{ $tasksByStatus['done']->count() }}
                            </span>
                        </h3>
                    </div>
                    <div class="p-4 space-y-3 min-h-96">
                        @foreach($tasksByStatus['done'] as $task)
                            <div class="bg-green-50 border border-green-200 rounded-lg p-3 hover:shadow-md transition-shadow opacity-75">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-medium text-gray-900 text-sm line-through">{{ $task->title }}</h4>
                                    <span class="px-2 py-1 text-xs rounded-full {{ $task->priority_color }}">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </div>
                                
                                @if($task->description)
                                    <p class="text-gray-600 text-xs mb-2 line-clamp-2">{{ $task->description }}</p>
                                @endif
                                
                                <div class="flex justify-between items-center text-xs text-gray-500">
                                    <span>
                                        @if($task->assignedUser)
                                            {{ $task->assignedUser->name }}
                                        @else
                                            Unassigned
                                        @endif
                                    </span>
                                    @if($task->due_date)
                                        <span>{{ $task->due_date->format('M j') }}</span>
                                    @endif
                                </div>
                                
                                <div class="mt-2">
                                    <a href="{{ route('projects.tasks.show', [$project, $task]) }}" 
                                       class="text-blue-500 hover:text-blue-700 text-xs">View</a>
                                </div>
                            </div>
                        @endforeach
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
@endsection