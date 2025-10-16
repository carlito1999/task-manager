@extends('layouts.app')

@section('content')
    <!-- Header -->
    <div class="bg-white shadow mb-6">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Edit Project: {{ $project->name }}
                </h2>
                <a href="{{ route('projects.show', $project) }}" class="btn-secondary">
                    Back to Project
                </a>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('projects.update', $project) }}">
                        @csrf
                        @method('PATCH')

                        <!-- Project Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Project Name</label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name', $project->name) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('name') border-red-500 @enderror"
                                   required>
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Project Description -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="4"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('description') border-red-500 @enderror">{{ old('description', $project->description) }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Project Status -->
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" 
                                    id="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('status') border-red-500 @enderror">
                                <option value="active" {{ old('status', $project->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="completed" {{ old('status', $project->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="on_hold" {{ old('status', $project->status) === 'on_hold' ? 'selected' : '' }}>On Hold</option>
                            </select>
                            @error('status')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Team Members -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Team Members</label>
                            <div class="space-y-2 max-h-40 overflow-y-auto border border-gray-300 rounded-md p-3">
                                @foreach($users as $user)
                                    <div class="flex items-center">
                                        <input type="checkbox" 
                                               name="members[]" 
                                               value="{{ $user->id }}"
                                               id="member_{{ $user->id }}"
                                               {{ $project->members->contains($user->id) ? 'checked' : '' }}
                                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <label for="member_{{ $user->id }}" class="ml-2 text-sm text-gray-900">
                                            {{ $user->name }} ({{ $user->email }})
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Select team members who can access this project.</p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between">
                            <div class="flex space-x-3">
                                <button type="submit" 
                                        class="btn-primary focus:outline-none focus:shadow-outline">
                                    Update Project
                                </button>
                                
                                <a href="{{ route('projects.show', $project) }}" 
                                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    Cancel
                                </a>
                            </div>
                            
                            <!-- Delete Project Button -->
                            @if($project->userCanManage(auth()->user()))
                                <button type="button" 
                                        onclick="if(confirm('Are you sure you want to delete this project? This action cannot be undone and will delete all associated tasks.')) { document.getElementById('delete-form').submit(); }"
                                        class="btn-danger focus:outline-none focus:shadow-outline">
                                    Delete Project
                                </button>
                            @endif
                        </div>
                    </form>

                    <!-- Hidden Delete Form -->
                    @if($project->userCanManage(auth()->user()))
                        <form id="delete-form" 
                              method="POST" 
                              action="{{ route('projects.destroy', $project) }}" 
                              style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection