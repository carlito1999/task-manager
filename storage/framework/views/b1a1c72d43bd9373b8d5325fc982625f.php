<div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg attachment-item" data-attachment-id="<?php echo e($attachment->id); ?>">
    <div class="flex items-center space-x-3">
        <div class="text-2xl"><?php echo e($attachment->icon); ?></div>
        <div class="flex-1 min-w-0">
            <div class="text-sm font-medium text-gray-900 truncate">
                <?php echo e($attachment->filename); ?>

            </div>
            <div class="text-xs text-gray-500">
                <?php echo e($attachment->formatted_size); ?> • Uploaded by <?php echo e($attachment->user->name); ?> • <?php echo e($attachment->created_at->diffForHumans()); ?>

            </div>
        </div>
    </div>
    <div class="flex items-center space-x-2">
        <a href="<?php echo e(route('attachments.download', $attachment)); ?>" 
           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
            Download
        </a>
        <?php if($attachment->user_id === auth()->id() || $project->userCanManage(auth()->user())): ?>
            <button onclick="deleteAttachment(<?php echo e($attachment->id); ?>)" 
                    class="text-red-600 hover:text-red-800 text-sm font-medium ml-2">
                Delete
            </button>
        <?php endif; ?>
    </div>
</div><?php /**PATH A:\myprojects\task-manager\resources\views/partials/attachment.blade.php ENDPATH**/ ?>