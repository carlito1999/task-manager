<div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg attachment-item" data-attachment-id="{{ $attachment->id }}">
    <div class="flex items-center space-x-3">
        <div class="text-2xl">{{ $attachment->icon }}</div>
        <div class="flex-1 min-w-0">
            <div class="text-sm font-medium text-gray-900 truncate">
                {{ $attachment->filename }}
            </div>
            <div class="text-xs text-gray-500">
                {{ $attachment->formatted_size }} • Uploaded by {{ $attachment->user->name }} • {{ $attachment->created_at->diffForHumans() }}
            </div>
        </div>
    </div>
    <div class="flex items-center space-x-2">
        <a href="{{ route('attachments.download', $attachment) }}" 
           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
            Download
        </a>
        @if($attachment->user_id === auth()->id() || $project->userCanManage(auth()->user()))
            <button onclick="deleteAttachment({{ $attachment->id }})" 
                    class="text-red-600 hover:text-red-800 text-sm font-medium ml-2">
                Delete
            </button>
        @endif
    </div>
</div>