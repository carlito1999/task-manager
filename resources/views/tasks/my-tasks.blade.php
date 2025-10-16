@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">My Tasks</h1>
                <p class="text-gray-600 mt-2">Manage all your assigned tasks in one place</p>
            </div>
            <div class="flex items-center space-x-3">
                <div class="flex items-center text-sm text-gray-500">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                    </svg>
                    <span id="last-updated">Updated just now</span>
                </div>
                <button onclick="refreshStatistics()" class="btn-refresh">
                    Refresh
                </button>
            </div>
        </div>

        <!-- Enhanced Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8" id="statistics-container">
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
                        <div class="text-sm font-medium text-gray-500">Total Tasks</div>
                        <div class="text-2xl font-bold text-gray-900" data-stat="total">{{ $taskStats['total'] }}</div>
                        <div class="text-xs text-gray-500 mt-1">
                            <span data-stat="completion_rate">{{ $taskStats['completion_rate'] }}%</span> completed
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Tasks -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-500">In Progress</div>
                        <div class="text-2xl font-bold text-gray-900" data-stat="in_progress">{{ $taskStats['in_progress'] }}</div>
                        <div class="text-xs text-gray-500 mt-1">
                            <span data-stat="todo">{{ $taskStats['todo'] }}</span> to-do
                        </div>
                    </div>
                </div>
            </div>

            <!-- Due Soon -->
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
                        <div class="text-sm font-medium text-gray-500">Due This Week</div>
                        <div class="text-2xl font-bold text-gray-900" data-stat="due_this_week">{{ $taskStats['due_this_week'] }}</div>
                        <div class="text-xs text-gray-500 mt-1">
                            <span data-stat="due_today">{{ $taskStats['due_today'] }}</span> due today
                        </div>
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
                        <div class="text-2xl font-bold text-gray-900" data-stat="overdue">{{ $taskStats['overdue'] }}</div>
                        <div class="text-xs text-red-500 mt-1">Need attention!</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Productivity & Priority Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Productivity Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Productivity</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">This Week</span>
                        <span class="font-semibold text-green-600" data-stat="completed_this_week">{{ $taskStats['completed_this_week'] }} completed</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">This Month</span>
                        <span class="font-semibold text-blue-600" data-stat="completed_this_month">{{ $taskStats['completed_this_month'] }} completed</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Subtasks Progress</span>
                        <span class="font-semibold text-purple-600">
                            <span data-stat="completed_subtasks">{{ $taskStats['completed_subtasks'] }}</span>/<span data-stat="total_subtasks">{{ $taskStats['total_subtasks'] }}</span> 
                            (<span data-stat="subtask_completion_rate">{{ $taskStats['subtask_completion_rate'] }}%</span>)
                        </span>
                    </div>
                </div>
            </div>

            <!-- Priority Breakdown -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Priority Breakdown</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span>
                            <span class="text-sm text-gray-600">High Priority</span>
                        </div>
                        <span class="font-semibold text-red-600" data-stat="high_priority">{{ $taskStats['high_priority'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <span class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></span>
                            <span class="text-sm text-gray-600">Medium Priority</span>
                        </div>
                        <span class="font-semibold text-yellow-600" data-stat="medium_priority">{{ $taskStats['medium_priority'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                            <span class="text-sm text-gray-600">Low Priority</span>
                        </div>
                        <span class="font-semibold text-green-600" data-stat="low_priority">{{ $taskStats['low_priority'] }}</span>
                    </div>
                    
                    <!-- Priority Progress Bar -->
                    <div class="mt-4">
                        <div class="flex text-xs text-gray-600 mb-1">
                            <span>Priority Distribution</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            @php
                                $totalActiveTasks = $taskStats['high_priority'] + $taskStats['medium_priority'] + $taskStats['low_priority'];
                                $highPercent = $totalActiveTasks > 0 ? ($taskStats['high_priority'] / $totalActiveTasks) * 100 : 0;
                                $mediumPercent = $totalActiveTasks > 0 ? ($taskStats['medium_priority'] / $totalActiveTasks) * 100 : 0;
                                $lowPercent = $totalActiveTasks > 0 ? ($taskStats['low_priority'] / $totalActiveTasks) * 100 : 0;
                            @endphp
                            <div class="h-2 rounded-full flex">
                                <div class="bg-red-500 h-2 rounded-l-full" style="width: {{ $highPercent }}%"></div>
                                <div class="bg-yellow-500 h-2" style="width: {{ $mediumPercent }}%"></div>
                                <div class="bg-green-500 h-2 rounded-r-full" style="width: {{ $lowPercent }}%"></div>
                            </div>
                        </div>
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
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium status-badge
                                            @if($task->status === 'todo') status-todo
                                            @elseif($task->status === 'in_progress') status-in-progress
                                            @else status-done @endif">
                                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                        </span>
                                        
                                        <!-- Priority Badge -->
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium priority-badge
                                            @if($task->priority === 'low') priority-low
                                            @elseif($task->priority === 'medium') priority-medium
                                            @else priority-high @endif">
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

@push('scripts')
<script>
let updateInterval;
let lastUpdateTime = new Date();

function refreshStatistics() {
    console.log('Refreshing statistics...');
    
    fetch('{{ route("tasks.statistics") }}', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json',
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Statistics updated:', data);
        updateStatisticsDisplay(data);
        updateLastUpdateTime();
    })
    .catch(error => {
        console.error('Error refreshing statistics:', error);
        showNotification('Failed to update statistics', 'error');
    });
}

function updateStatisticsDisplay(stats) {
    // Update all data-stat elements
    Object.keys(stats).forEach(key => {
        const elements = document.querySelectorAll(`[data-stat="${key}"]`);
        elements.forEach(element => {
            const oldValue = element.textContent;
            const newValue = stats[key];
            
            if (oldValue !== newValue.toString()) {
                // Add animation class for changes
                element.classList.add('stat-updated');
                element.textContent = newValue;
                
                // Remove animation class after animation completes
                setTimeout(() => {
                    element.classList.remove('stat-updated');
                }, 500);
            }
        });
    });
    
    // Update priority progress bar
    updatePriorityProgressBar(stats);
    
    // Show success notification
    showNotification('Statistics updated successfully!', 'success');
}

function updatePriorityProgressBar(stats) {
    const totalActiveTasks = stats.high_priority + stats.medium_priority + stats.low_priority;
    
    if (totalActiveTasks > 0) {
        const highPercent = (stats.high_priority / totalActiveTasks) * 100;
        const mediumPercent = (stats.medium_priority / totalActiveTasks) * 100;
        const lowPercent = (stats.low_priority / totalActiveTasks) * 100;
        
        const progressBar = document.querySelector('.h-2.rounded-full.flex');
        if (progressBar) {
            progressBar.innerHTML = `
                <div class="bg-red-500 h-2 rounded-l-full transition-all duration-500" style="width: ${highPercent}%"></div>
                <div class="bg-yellow-500 h-2 transition-all duration-500" style="width: ${mediumPercent}%"></div>
                <div class="bg-green-500 h-2 rounded-r-full transition-all duration-500" style="width: ${lowPercent}%"></div>
            `;
        }
    }
}

function updateLastUpdateTime() {
    lastUpdateTime = new Date();
    const timeElement = document.getElementById('last-updated');
    if (timeElement) {
        timeElement.textContent = 'Updated just now';
    }
}

function updateRelativeTime() {
    const timeElement = document.getElementById('last-updated');
    if (timeElement) {
        const now = new Date();
        const diffMinutes = Math.floor((now - lastUpdateTime) / 60000);
        
        if (diffMinutes === 0) {
            timeElement.textContent = 'Updated just now';
        } else if (diffMinutes === 1) {
            timeElement.textContent = 'Updated 1 minute ago';
        } else if (diffMinutes < 60) {
            timeElement.textContent = `Updated ${diffMinutes} minutes ago`;
        } else {
            const diffHours = Math.floor(diffMinutes / 60);
            timeElement.textContent = `Updated ${diffHours} hour${diffHours > 1 ? 's' : ''} ago`;
        }
    }
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
        type === 'success' ? 'bg-green-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

function startAutoUpdate() {
    // Update statistics every 30 seconds
    updateInterval = setInterval(refreshStatistics, 30000);
    
    // Update relative time every 30 seconds
    setInterval(updateRelativeTime, 30000);
    
    console.log('Auto-update started');
}

function stopAutoUpdate() {
    if (updateInterval) {
        clearInterval(updateInterval);
        console.log('Auto-update stopped');
    }
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('My Tasks page loaded, starting auto-update...');
    startAutoUpdate();
    
    // Listen for page visibility changes
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            stopAutoUpdate();
        } else {
            refreshStatistics();
            startAutoUpdate();
        }
    });
    
    // Refresh when user comes back to the tab
    window.addEventListener('focus', function() {
        refreshStatistics();
    });
});

// Clean up when page unloads
window.addEventListener('beforeunload', function() {
    stopAutoUpdate();
});
</script>

<style>
.stat-updated {
    animation: statUpdate 0.5s ease-in-out;
}

@keyframes statUpdate {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); color: #3B82F6; }
    100% { transform: scale(1); }
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endpush
@endsection