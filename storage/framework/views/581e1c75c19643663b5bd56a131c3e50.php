

<?php $__env->startSection('content'); ?>
    <!-- Header -->
    <div class="bg-white shadow mb-6">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Create New Task
                    </h2>
                    <p class="text-gray-600 mt-1">Add a new task to <?php echo e($project->name); ?></p>
                </div>
                <a href="<?php echo e(route('projects.show', $project)); ?>" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Project
                </a>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Display validation errors -->
                    <?php if($errors->any()): ?>
                        <div class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3>
                                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($error); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo e(route('projects.tasks.store', $project)); ?>">
                        <?php echo csrf_field(); ?>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Task Title -->
                            <div class="md:col-span-2">
                                <label for="title" class="block text-sm font-medium text-gray-700">
                                    Task Title *
                                </label>
                                <input type="text" 
                                       name="title" 
                                       id="title" 
                                       value="<?php echo e(old('title')); ?>"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       placeholder="Enter a descriptive task title"
                                       required>
                                <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Task Description -->
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700">
                                    Description
                                </label>
                                <textarea name="description" 
                                          id="description" 
                                          rows="4"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                          placeholder="Provide detailed information about this task..."><?php echo e(old('description')); ?></textarea>
                                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Priority -->
                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700">
                                    Priority *
                                </label>
                                <select name="priority" 
                                        id="priority"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm <?php $__errorArgs = ['priority'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Select Priority</option>
                                    <option value="low" <?php echo e(old('priority') === 'low' ? 'selected' : ''); ?>>
                                        🟢 Low Priority
                                    </option>
                                    <option value="medium" <?php echo e(old('priority') === 'medium' ? 'selected' : ''); ?>>
                                        🟡 Medium Priority
                                    </option>
                                    <option value="high" <?php echo e(old('priority') === 'high' ? 'selected' : ''); ?>>
                                        🔴 High Priority
                                    </option>
                                </select>
                                <?php $__errorArgs = ['priority'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">
                                    Initial Status
                                </label>
                                <select name="status" 
                                        id="status"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="todo" <?php echo e(old('status', 'todo') === 'todo' ? 'selected' : ''); ?>>
                                        📋 Todo
                                    </option>
                                    <option value="in_progress" <?php echo e(old('status') === 'in_progress' ? 'selected' : ''); ?>>
                                        🚀 In Progress
                                    </option>
                                    <option value="done" <?php echo e(old('status') === 'done' ? 'selected' : ''); ?>>
                                        ✅ Done
                                    </option>
                                </select>
                                <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Assigned To -->
                            <div>
                                <label for="assigned_to" class="block text-sm font-medium text-gray-700">
                                    Assign To
                                </label>
                                <select name="assigned_to" 
                                        id="assigned_to"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm <?php $__errorArgs = ['assigned_to'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Unassigned</option>
                                    
                                    <!-- Project Owner -->
                                    <option value="<?php echo e($project->user_id); ?>" 
                                            <?php echo e(old('assigned_to') == $project->user_id ? 'selected' : ''); ?>>
                                        👑 <?php echo e($project->owner->name); ?> (Project Owner)
                                    </option>
                                    
                                    <!-- Project Members -->
                                    <?php $__currentLoopData = $project->members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($member->id !== $project->user_id): ?>
                                            <option value="<?php echo e($member->id); ?>" 
                                                    <?php echo e(old('assigned_to') == $member->id ? 'selected' : ''); ?>>
                                                👤 <?php echo e($member->name); ?>

                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['assigned_to'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <p class="mt-1 text-sm text-gray-500">
                                    Only project members can be assigned to tasks.
                                </p>
                            </div>

                            <!-- Due Date -->
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-700">
                                    Due Date
                                </label>
                                <input type="date" 
                                       name="due_date" 
                                       id="due_date" 
                                       value="<?php echo e(old('due_date')); ?>"
                                       min="<?php echo e(date('Y-m-d')); ?>"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm <?php $__errorArgs = ['due_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['due_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <p class="mt-1 text-sm text-gray-500">
                                    Leave empty if no specific deadline is required.
                                </p>
                            </div>
                        </div>

                        <!-- Quick Task Templates (Optional Enhancement) -->
                        <div class="mt-8 p-4 bg-gray-50 rounded-lg border">
                            <h3 class="text-sm font-medium text-gray-900 mb-3">Quick Templates</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <button type="button" 
                                        onclick="applyTemplate('bug')"
                                        class="text-left p-3 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition-colors">
                                    <div class="font-medium text-red-600">🐛 Bug Fix</div>
                                    <div class="text-xs text-gray-500">High priority, needs immediate attention</div>
                                </button>
                                
                                <button type="button" 
                                        onclick="applyTemplate('feature')"
                                        class="text-left p-3 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition-colors">
                                    <div class="font-medium text-blue-600">✨ New Feature</div>
                                    <div class="text-xs text-gray-500">Medium priority, planned development</div>
                                </button>
                                
                                <button type="button" 
                                        onclick="applyTemplate('research')"
                                        class="text-left p-3 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition-colors">
                                    <div class="font-medium text-green-600">🔍 Research Task</div>
                                    <div class="text-xs text-gray-500">Low priority, investigation needed</div>
                                </button>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-8 flex items-center justify-between">
                            <div class="flex space-x-3">
                                <button type="submit" 
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition-colors">
                                    Create Task
                                </button>
                                
                                <button type="button" 
                                        onclick="saveDraft()"
                                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition-colors">
                                    Save Draft
                                </button>
                            </div>
                            
                            <a href="<?php echo e(route('projects.show', $project)); ?>" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition-colors">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Help Section -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Tips for Creating Effective Tasks</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li><strong>Clear Titles:</strong> Use action verbs and be specific about what needs to be done</li>
                                <li><strong>Detailed Descriptions:</strong> Include acceptance criteria and any relevant context</li>
                                <li><strong>Proper Priority:</strong> High for urgent, Medium for standard, Low for future improvements</li>
                                <li><strong>Smart Assignment:</strong> Assign to the person best suited for the task</li>
                                <li><strong>Realistic Deadlines:</strong> Consider dependencies and workload when setting due dates</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
    // Task template functions
    function applyTemplate(type) {
        const titleInput = document.getElementById('title');
        const descriptionTextarea = document.getElementById('description');
        const prioritySelect = document.getElementById('priority');
        
        switch(type) {
            case 'bug':
                titleInput.value = 'Fix: ';
                descriptionTextarea.value = '**Bug Description:**\n\n**Steps to Reproduce:**\n1. \n2. \n3. \n\n**Expected Behavior:**\n\n**Actual Behavior:**\n\n**Additional Notes:**';
                prioritySelect.value = 'high';
                break;
                
            case 'feature':
                titleInput.value = 'Feature: ';
                descriptionTextarea.value = '**Feature Description:**\n\n**User Story:**\nAs a [user type], I want [functionality] so that [benefit].\n\n**Acceptance Criteria:**\n- [ ] \n- [ ] \n- [ ] \n\n**Technical Notes:**';
                prioritySelect.value = 'medium';
                break;
                
            case 'research':
                titleInput.value = 'Research: ';
                descriptionTextarea.value = '**Research Objective:**\n\n**Questions to Answer:**\n- \n- \n- \n\n**Deliverables:**\n- [ ] \n- [ ] \n\n**Timeline:**';
                prioritySelect.value = 'low';
                break;
        }
        
        // Focus on the title to allow user to complete it
        titleInput.focus();
        titleInput.setSelectionRange(titleInput.value.length, titleInput.value.length);
    }
    
    // Save draft functionality (could be enhanced with local storage)
    function saveDraft() {
        const formData = {
            title: document.getElementById('title').value,
            description: document.getElementById('description').value,
            priority: document.getElementById('priority').value,
            status: document.getElementById('status').value,
            assigned_to: document.getElementById('assigned_to').value,
            due_date: document.getElementById('due_date').value
        };
        
        localStorage.setItem('task_draft_<?php echo e($project->id); ?>', JSON.stringify(formData));
        
        // Show feedback
        const button = event.target;
        const originalText = button.textContent;
        button.textContent = 'Draft Saved!';
        button.style.backgroundColor = '#10B981';
        
        setTimeout(() => {
            button.textContent = originalText;
            button.style.backgroundColor = '';
        }, 2000);
    }
    
    // Load draft on page load
    document.addEventListener('DOMContentLoaded', function() {
        const savedDraft = localStorage.getItem('task_draft_<?php echo e($project->id); ?>');
        if (savedDraft && confirm('A draft was found for this project. Would you like to load it?')) {
            const draftData = JSON.parse(savedDraft);
            
            Object.keys(draftData).forEach(key => {
                const element = document.getElementById(key);
                if (element && draftData[key]) {
                    element.value = draftData[key];
                }
            });
        }
    });
    
    // Clear draft after successful submission
    document.querySelector('form').addEventListener('submit', function() {
        localStorage.removeItem('task_draft_<?php echo e($project->id); ?>');
    });
    
    // Auto-resize description textarea
    const descriptionTextarea = document.getElementById('description');
    if (descriptionTextarea) {
        descriptionTextarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    }
    </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH A:\myprojects\task-manager\resources\views/tasks/create.blade.php ENDPATH**/ ?>