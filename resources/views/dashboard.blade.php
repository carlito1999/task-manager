@extends('layouts.app')

@section('content')
    <!-- Header -->
    <div class="bg-white shadow mb-6">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Dashboard') }}
                </h2>
                <a href="{{ route('projects.create') }}" class="btn-primary">
                    Create Project
                </a>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Tasks Stats -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Total Tasks</div>
                                <div class="text-2xl font-bold text-gray-900">{{ $taskStats['total'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Completed Tasks -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Completed</div>
                                <div class="text-2xl font-bold text-gray-900">{{ $taskStats['completed'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Projects Stats -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Active Projects</div>
                                <div class="text-2xl font-bold text-gray-900">{{ $projectStats['active'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Overdue Tasks -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Overdue</div>
                                <div class="text-2xl font-bold text-gray-900">{{ $taskStats['overdue'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Projects -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Recent Projects</h3>
                            <a href="{{ route('projects.index') }}" class="link-primary">View All</a>
                        </div>
                        
                        @if($userProjects->count() > 0)
                            <div class="space-y-3">
                                @foreach($userProjects as $project)
                                    <div class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900">{{ $project->name }}</h4>
                                            <p class="text-sm text-gray-500">{{ $project->tasks->count() }} tasks • {{ $project->completion_percentage }}% complete</p>
                                        </div>
                                        <div class="flex space-x-2">
                                            <span class="project-status-badge 
                                                @if($project->status === 'active') project-active
                                                @elseif($project->status === 'completed') project-completed  
                                                @else project-archived
                                                @endif">
                                                {{ ucfirst($project->status) }}
                                            </span>
                                            <a href="{{ route('projects.show', $project) }}" class="text-blue-500 hover:text-blue-700">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No projects</h3>
                                <p class="mt-1 text-sm text-gray-500">Get started by creating your first project.</p>
                                <div class="mt-6">
                                    <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                        <svg class="-ml-1 mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                        </svg>
                                        New Project
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Upcoming Tasks -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Upcoming Tasks</h3>
                            <a href="{{ route('tasks.my-tasks') }}" class="text-blue-500 hover:text-blue-700">View All</a>
                        </div>

                        @if($upcomingTasks->count() > 0)
                            <div class="space-y-3">
                                @foreach($upcomingTasks as $task)
                                    <div class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900">{{ $task->title }}</h4>
                                            <p class="text-sm text-gray-500">
                                                {{ $task->project->name }} • 
                                                Due {{ $task->due_date->format('M j') }}
                                            </p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="px-2 py-1 text-xs rounded-full {{ $task->priority_color }}">
                                                {{ ucfirst($task->priority) }}
                                            </span>
                                            <a href="{{ route('projects.tasks.show', [$task->project, $task]) }}" class="text-blue-500 hover:text-blue-700">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No upcoming tasks</h3>
                                <p class="mt-1 text-sm text-gray-500">All caught up! No tasks due soon.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Overdue Tasks Alert -->
            @if($overdueTasks->count() > 0)
                <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                You have {{ $overdueTasks->count() }} overdue tasks
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach($overdueTasks as $task)
                                        <li>
                                            <a href="{{ route('projects.tasks.show', [$task->project, $task]) }}" class="underline">
                                                {{ $task->title }}
                                            </a> 
                                            ({{ $task->project->name }})
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
