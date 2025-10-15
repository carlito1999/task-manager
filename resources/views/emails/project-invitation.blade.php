@extends('emails.layout')

@section('title', 'Project Invitation - ' . $project->name)

@section('header', 'Project Invitation')

@section('content')
    <h2 style="color: #1f2937; margin-bottom: 16px;">Hello!</h2>
    
    <p>You have been invited to join a project by <strong>{{ $invitedBy->name }}</strong>.</p>
    
    <div style="background-color: #f0f9ff; border: 1px solid #3b82f6; border-radius: 6px; padding: 16px; margin: 16px 0;">
        <p style="margin: 0; color: #1e40af; font-weight: 500;">🎉 Welcome to the team!</p>
    </div>
    
    <div class="project-info">
        <h3 style="margin: 0 0 12px 0; color: #1f2937;">{{ $project->name }}</h3>
        
        @if($project->description)
            <p style="margin: 8px 0;"><strong>Description:</strong></p>
            <p style="margin: 8px 0; color: #4b5563;">{{ $project->description }}</p>
        @endif
        
        <div class="meta-info">
            <p><strong>Project Owner:</strong> {{ $project->user->name }}</p>
            <p><strong>Status:</strong> 
                <span class="status-badge status-{{ $project->status }}">{{ ucfirst($project->status) }}</span>
            </p>
            <p><strong>Created:</strong> {{ $project->created_at->format('F j, Y') }}</p>
            @if($project->due_date)
                <p><strong>Project Due Date:</strong> {{ $project->due_date->format('F j, Y') }}</p>
            @endif
        </div>
    </div>
    
    <div style="background-color: #f8f9fa; border-radius: 6px; padding: 16px; margin: 16px 0;">
        <h4 style="margin: 0 0 8px 0; color: #1f2937;">As a project member, you can:</h4>
        <ul style="margin: 8px 0; padding-left: 20px; color: #4b5563;">
            <li>View and comment on project tasks</li>
            <li>Be assigned to tasks</li>
            <li>Upload files and attachments</li>
            <li>Collaborate with the team</li>
            <li>Receive notifications about project updates</li>
        </ul>
    </div>
    
    <div style="text-align: center; margin: 24px 0;">
        <a href="{{ route('projects.show', $project) }}" class="btn">
            View Project
        </a>
    </div>
    
    <p style="color: #6b7280; font-size: 14px;">
        Click the button above to start collaborating on this project. You can view tasks, add comments, and work with your team.
    </p>
@endsection