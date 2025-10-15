@extends('emails.layout')

@section('title', 'Task Due Reminder - ' . $task->title)

@section('header', 'Task Due Reminder')

@section('content')
    <h2 style="color: #1f2937; margin-bottom: 16px;">Hello {{ $task->assignedUser->name }}!</h2>
    
    @if($task->due_date->isToday())
        <div style="background-color: #fef3c7; border: 1px solid #f59e0b; border-radius: 6px; padding: 16px; margin: 16px 0;">
            <p style="margin: 0; color: #92400e; font-weight: 500;">⚠️ This task is due TODAY!</p>
        </div>
    @elseif($task->due_date->isPast())
        <div style="background-color: #fee2e2; border: 1px solid #ef4444; border-radius: 6px; padding: 16px; margin: 16px 0;">
            <p style="margin: 0; color: #dc2626; font-weight: 500;">🚨 This task is OVERDUE!</p>
        </div>
    @else
        <div style="background-color: #dbeafe; border: 1px solid #3b82f6; border-radius: 6px; padding: 16px; margin: 16px 0;">
            <p style="margin: 0; color: #1e40af; font-weight: 500;">📅 This task is due soon.</p>
        </div>
    @endif
    
    <p>Don't forget about this important task that needs your attention.</p>
    
    <div class="task-info priority-{{ $task->priority }}">
        <h3 style="margin: 0 0 12px 0; color: #1f2937;">{{ $task->title }}</h3>
        
        @if($task->description)
            <p style="margin: 8px 0;"><strong>Description:</strong></p>
            <p style="margin: 8px 0; color: #4b5563;">{{ Str::limit($task->description, 200) }}</p>
        @endif
        
        <div class="meta-info">
            <p><strong>Project:</strong> {{ $task->project->name }}</p>
            <p><strong>Priority:</strong> 
                <span class="status-badge priority-{{ $task->priority }}">{{ ucfirst($task->priority) }}</span>
            </p>
            <p><strong>Due Date:</strong> 
                <span style="font-weight: 600; color: {{ $task->due_date->isPast() ? '#dc2626' : '#1f2937' }};">
                    {{ $task->due_date->format('F j, Y') }}
                    @if($task->due_date->isPast())
                        ({{ $task->due_date->diffForHumans() }})
                    @else
                        ({{ $task->due_date->diffForHumans() }})
                    @endif
                </span>
            </p>
            <p><strong>Current Status:</strong> 
                <span class="status-badge status-{{ $task->status }}">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span>
            </p>
        </div>
    </div>
    
    <div style="text-align: center; margin: 24px 0;">
        <a href="{{ route('projects.tasks.show', [$task->project, $task]) }}" class="btn">
            Complete This Task
        </a>
    </div>
    
    <p style="color: #6b7280; font-size: 14px;">
        Click the button above to view the task details and update its status.
    </p>
@endsection