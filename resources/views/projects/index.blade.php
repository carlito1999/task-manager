@extends('layouts.app')

@section('content')
    <!-- Header -->
    <div class="bg-white shadow mb-6">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('My Projects') }}
                </h2>
                <a href="{{ route('projects.create') }}" class="btn-primary">
                    Create New Project
                </a>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($projects->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($projects as $project)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                            <div class="p-6">
                                <!-- Project Header -->
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                            <a href="{{ route('projects.show', $project) }}" class="hover:text-blue-600">
                                                {{ $project->name }}
                                            </a>
                                        </h3>
                                        <span class="project-status-badge 
                                            @if($project->status === 'active') project-active
                                            @elseif($project->status === 'completed') project-completed  
                                            @else project-archived
                                            @endif">
                                            {{ ucfirst($project->status) }}
                                        </span>
                                    </div>
                                    
                                    <!-- Actions Dropdown -->
                                    <div x-data="{ open: false }" class="relative">
                                        <button @click="open = !open" class="text-gray-400 hover:text-gray-600">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                            </svg>
                                        </button>
                                        
                                        <div x-show="open" @click.away="open = false" 
                                             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10">
                                            <div class="py-1">
                                                <a href="{{ route('projects.show', $project) }}" 
                                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">View</a>
                                                @if($project->userCanManage(auth()->user()))
                                                    <a href="{{ route('projects.edit', $project) }}" 
                                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit</a>
                                                @endif
                                                @if($project->user_id === auth()->id())
                                                    <form method="POST" action="{{ route('projects.destroy', $project) }}" 
                                                          onsubmit="return confirm('Are you sure?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-gray-100">
                                                            Delete
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Project Description -->
                                @if($project->description)
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $project->description }}</p>
                                @endif

                                <!-- Project Stats -->
                                <div class="space-y-3">
                                    <!-- Progress Bar -->
                                    <div>
                                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                                            <span>Progress</span>
                                            <span>{{ $project->completion_percentage }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" 
                                                 style="width: {{ $project->completion_percentage }}%"></div>
                                        </div>
                                    </div>

                                    <!-- Task Count -->
                                    <div class="flex justify-between text-sm text-gray-600">
                                        <span>Tasks</span>
                                        <span>{{ $project->tasks->count() }} total</span>
                                    </div>

                                    <!-- Team Members -->
                                    <div class="flex justify-between text-sm text-gray-600">
                                        <span>Team</span>
                                        <span>{{ $project->members->count() }} members</span>
                                    </div>

                                    <!-- Deadline -->
                                    @if($project->deadline)
                                        <div class="flex justify-between text-sm text-gray-600">
                                            <span>Deadline</span>
                                            <span class="@if($project->deadline->isPast()) text-red-600 @endif">
                                                {{ $project->deadline->format('M j, Y') }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Action Buttons -->
                                <div class="mt-4 flex space-x-2">
                                    <a href="{{ route('projects.show', $project) }}" 
                                       class="flex-1 text-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                        View Project
                                    </a>
                                    @if($project->userCanManage(auth()->user()))
                                        <a href="{{ route('projects.tasks.create', $project) }}" 
                                           class="flex-1 text-center bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                                            Add Task
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $projects->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No projects</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating your first project.</p>
                    <div class="mt-6">
                        <a href="{{ route('projects.create') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
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
@endsection