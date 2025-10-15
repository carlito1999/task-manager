@extends('emails.layout')

@section('title', 'New Comment - ' . $task->title)

@section('header', 'New Comment Added')

@section('content')
    <h2 style="color: #1f2937; margin-bottom: 16px;">Hello!</h2>
    
    <p><strong>{{ $comment->user->name }}</strong> added a new comment to a task you're involved with.</p>
    
    <div class="task-info">
        <h3 style="margin: 0 0 12px 0; color: #1f2937;">{{ $task->title }}</h3>
        <div class="meta-info">
            <p><strong>Project:</strong> {{ $task->project->name }}</p>
        </div>
    </div>
    
    <div class="comment-box">
        <div class="user-info">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->user->name) }}&background=3B82F6&color=fff&size=32" 
                 alt="{{ $comment->user->name }}" class="user-avatar">
            <div>
                <strong>{{ $comment->user->name }}</strong>
                <div style="font-size: 12px; color: #6b7280;">{{ $comment->created_at->format('F j, Y \a\t g:i A') }}</div>
            </div>
        </div>
        <div style="color: #374151; line-height: 1.6;">
            {!! nl2br(e($comment->content)) !!}
        </div>
    </div>
    
    <div style="text-align: center; margin: 24px 0;">
        <a href="{{ route('projects.tasks.show', [$task->project, $task]) }}#comment-{{ $comment->id }}" class="btn">
            View Comment & Reply
        </a>
    </div>
    
    <p style="color: #6b7280; font-size: 14px;">
        You can reply to this comment and continue the conversation by clicking the button above.
    </p>
@endsection