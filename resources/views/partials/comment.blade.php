<div class="flex space-x-3 comment-item" data-comment-id="{{ $comment->id }}">
    <img class="w-8 h-8 rounded-full" 
         src="https://ui-avatars.com/api/?name={{ urlencode($comment->user->name) }}&background=3B82F6&color=fff" 
         alt="{{ $comment->user->name }}">
    <div class="flex-1">
        <div class="bg-gray-50 rounded-lg p-3">
            <div class="flex justify-between items-start mb-1">
                <h4 class="font-medium text-gray-900">{{ $comment->user->name }}</h4>
                <div class="flex items-center space-x-2">
                    <time class="text-xs text-gray-500" datetime="{{ $comment->created_at->toISOString() }}">
                        {{ $comment->created_at->diffForHumans() }}
                    </time>
                    @if($comment->user_id === auth()->id())
                        <button onclick="deleteComment({{ $comment->id }})" 
                                class="text-xs text-red-600 hover:text-red-800">
                            Delete
                        </button>
                    @endif
                </div>
            </div>
            <p class="text-gray-700 text-sm">{!! nl2br(e($comment->content)) !!}</p>
        </div>
    </div>
</div>