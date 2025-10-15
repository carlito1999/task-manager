@extends('emails.layout')

@section('title', 'Task Assigned - ' . $task->title)

@section('header', 'Task Assigned')

@section('content')
    <h2 style="color: #1f2937; margin-bottom: 16px;">Hello {{ $task->assignedUser->name }}!</h2>
    
    <p>You have been assigned a new task by <strong>{{ $assignedBy->name }}</strong>.</p>
    
    <div class="task-info priority-{{ $task->priority }}">
        <h3 style="margin: 0 0 12px 0; color: #1f2937;">{{ $task->title }}</h3>
        
        @if($task->description)
            <p style="margin: 8px 0;"><strong>Description:</strong></p>
            <p style="margin: 8px 0; color: #4b5563;">{{ $task->description }}</p>
        @endif
        
        <div class="meta-info">
            <p><strong>Project:</strong> {{ $task->project->name }}</p>
            <p><strong>Priority:</strong> 
                <span class="status-badge priority-{{ $task->priority }}">{{ ucfirst($task->priority) }}</span>
            </p>
            <p><strong>Due Date:</strong> {{ $task->due_date ? $task->due_date->format('F j, Y') : 'Not set' }}</p>
            <p><strong>Status:</strong> 
                <span class="status-badge status-{{ $task->status }}">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span>
            </p>
        </div>
    </div>
    
    <div style="text-align: center; margin: 24px 0;">
        <a href="{{ route('projects.tasks.show', [$task->project, $task]) }}" class="btn">
            View Task Details
        </a>
    </div>
    
    <p style="color: #6b7280; font-size: 14px;">
        You can view this task, add comments, and update its status by clicking the button above.
    </p>
@endsection