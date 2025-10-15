@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">My Tasks</h1>
            <p class="text-gray-600 mt-2">Manage all your assigned tasks in one place</p>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Tasks -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-500">Active Tasks</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $tasks->total() }}</div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Tasks -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-500">Due Soon</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $upcomingTasks->count() }}</div>
                    </div>
                </div>
            </div>

            <!-- Overdue Tasks -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-100 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-500">Overdue</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $overdueTasks->count() }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Tasks List -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">All My Tasks</h2>
                    </div>
                    
                    <div class="divide-y divide-gray-200">
                        @forelse($tasks as $task)
                            <div class="p-6 hover:bg-gray-50 transition-colors">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">
                                            <a href="{{ route('projects.tasks.show', [$task->project, $task]) }}" 
                                               class="hover:text-blue-600 transition-colors">
                                                {{ $task->title }}
                                            </a>
                                        </h3>
                                        
                                        @if($task->description)
                                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                                                {{ Str::limit($task->description, 150) }}
                                            </p>
                                        @endif
                                        
                                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                                            <span class="font-medium text-blue-600">{{ $task->project->name }}</span>
                                            
                                            @if($task->due_date)
                                                <span class="flex items-center @if($task->due_date->isPast() && $task->status !== 'done') text-red-600 @endif">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    {{ $task->due_date->format('M j, Y') }}
                                                    @if($task->due_date->isPast() && $task->status !== 'done')
                                                        (Overdue)
                                                    @endif
                                                </span>
                                            @endif
                                            
                                            <span>{{ $task->comments->count() }} comments</span>
                                        </div>
                                    </div>
                                    
                                    <div class="ml-4 flex flex-col items-end space-y-2">
                                        <!-- Status Badge -->
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($task->status === 'todo') bg-gray-100 text-gray-800
                                            @elseif($task->status === 'in_progress') bg-blue-100 text-blue-800
                                            @else bg-green-100 text-green-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                        </span>
                                        
                                        <!-- Priority Badge -->
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($task->priority === 'low') bg-gray-100 text-gray-800
                                            @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($task->priority) }} Priority
                                        </span>
                                        
                                        <!-- Quick Actions -->
                                        <div class="flex space-x-2">
                                            @if($task->status !== 'in_progress')
                                                <form method="POST" action="{{ route('tasks.update-status', [$task->project, $task]) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="in_progress">
                                                    <button type="submit" class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                                        Start
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            @if($task->status !== 'done')
                                                <form method="POST" action="{{ route('tasks.update-status', [$task->project, $task]) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="done">
                                                    <button type="submit" class="text-green-600 hover:text-green-800 text-xs font-medium">
                                                        Complete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-12 text-center">
                                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-1">No tasks assigned</h3>
                                <p class="text-gray-500">You don't have any tasks assigned to you at the moment.</p>
                            </div>
                        @endforelse
                    </div>
                    
                    @if($tasks->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200">
                            {{ $tasks->links() }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Upcoming Tasks -->
                @if($upcomingTasks->count() > 0)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Due Soon</h3>
                        <div class="space-y-3">
                            @foreach($upcomingTasks as $task)
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 w-2 h-2 bg-yellow-400 rounded-full mt-2"></div>
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('projects.tasks.show', [$task->project, $task]) }}" 
                                           class="text-sm font-medium text-gray-900 hover:text-blue-600 block">
                                            {{ Str::limit($task->title, 40) }}
                                        </a>
                                        <div class="text-xs text-gray-500">
                                            {{ $task->project->name }} • {{ $task->due_date->format('M j') }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Overdue Tasks -->
                @if($overdueTasks->count() > 0)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-red-600 mb-4">Overdue Tasks</h3>
                        <div class="space-y-3">
                            @foreach($overdueTasks as $task)
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 w-2 h-2 bg-red-400 rounded-full mt-2"></div>
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('projects.tasks.show', [$task->project, $task]) }}" 
                                           class="text-sm font-medium text-gray-900 hover:text-blue-600 block">
                                            {{ Str::limit($task->title, 40) }}
                                        </a>
                                        <div class="text-xs text-red-600">
                                            {{ $task->project->name }} • {{ $task->due_date->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('projects.index') }}" 
                           class="flex items-center text-sm text-gray-600 hover:text-blue-600 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            View All Projects
                        </a>
                        <a href="{{ route('dashboard') }}" 
                           class="flex items-center text-sm text-gray-600 hover:text-blue-600 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2H3"></path>
                            </svg>
                            Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection