@extends('emails.layout')

@section('title', 'Task Status Updated - ' . $task->title)

@section('header', 'Task Status Updated')

@section('content')
    <h2 style="color: #1f2937; margin-bottom: 16px;">Hello {{ $task->assignedUser->name ?? 'Team Member' }}!</h2>
    
    <p>The status of a task has been updated by <strong>{{ $updatedBy->name }}</strong>.</p>
    
    <div class="task-info">
        <h3 style="margin: 0 0 12px 0; color: #1f2937;">{{ $task->title }}</h3>
        
        <div style="display: flex; align-items: center; margin: 16px 0; gap: 16px;">
            <div>
                <strong>Previous Status:</strong>
                <span class="status-badge status-{{ $oldStatus }}">{{ ucfirst(str_replace('_', ' ', $oldStatus)) }}</span>
            </div>
            <div style="font-size: 18px;">→</div>
            <div>
                <strong>New Status:</strong>
                <span class="status-badge status-{{ $newStatus }}">{{ ucfirst(str_replace('_', ' ', $newStatus)) }}</span>
            </div>
        </div>
        
        <div class="meta-info">
            <p><strong>Project:</strong> {{ $task->project->name }}</p>
            <p><strong>Priority:</strong> 
                <span class="status-badge priority-{{ $task->priority }}">{{ ucfirst($task->priority) }}</span>
            </p>
            <p><strong>Due Date:</strong> {{ $task->due_date ? $task->due_date->format('F j, Y') : 'Not set' }}</p>
        </div>
    </div>
    
    @if($newStatus === 'done')
        <div style="background-color: #d1fae5; border: 1px solid #10b981; border-radius: 6px; padding: 16px; margin: 16px 0;">
            <p style="margin: 0; color: #065f46; font-weight: 500;">🎉 Congratulations! This task has been completed.</p>
        </div>
    @elseif($newStatus === 'in_progress')
        <div style="background-color: #dbeafe; border: 1px solid #3b82f6; border-radius: 6px; padding: 16px; margin: 16px 0;">
            <p style="margin: 0; color: #1e40af; font-weight: 500;">🚀 This task is now in progress.</p>
        </div>
    @endif
    
    <div style="text-align: center; margin: 24px 0;">
        <a href="{{ route('projects.tasks.show', [$task->project, $task]) }}" class="btn">
            View Task Details
        </a>
    </div>
@endsection