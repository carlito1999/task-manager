<?php $__env->startSection('content'); ?>
    <!-- CSRF Token for AJAX requests -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <!-- Header -->
    <div class="bg-white shadow mb-6">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        <?php echo e($project->name); ?>

                    </h2>
                    <p class="text-gray-600 mt-1"><?php echo e($project->description); ?></p>
                </div>
                <div class="flex space-x-2">
                    <?php if($project->userCanManage(auth()->user())): ?>
                    <?php echo csrf_field(); ?>
                        <a href="<?php echo e(route('projects.tasks.create', $project)); ?>" 
                           class="btn-success">
                            Add Task
                        </a>
                    <a href="<?php echo e(route('projects.edit', $project)); ?>" 
                       class="btn-primary">
                        Edit Project
                    </a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('projects.index')); ?>" 
                       class="btn-secondary">
                        Back to Projects
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Project Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4">
                        <div class="text-sm font-medium text-gray-500">Progress</div>
                        <div class="text-2xl font-bold text-gray-900"><?php echo e($project->completion_percentage); ?>%</div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo e($project->completion_percentage ?? 0); ?>%"></div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4">
                        <div class="text-sm font-medium text-gray-500">Total Tasks</div>
                        <div class="text-2xl font-bold text-gray-900"><?php echo e($project->tasks->count()); ?></div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4">
                        <div class="text-sm font-medium text-gray-500">Team Members</div>
                        <div class="text-2xl font-bold text-gray-900"><?php echo e($project->members->count()); ?></div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4">
                        <div class="text-sm font-medium text-gray-500">Status</div>
                        <span class="project-status-badge 
                            <?php if($project->status === 'active'): ?> project-active
                            <?php elseif($project->status === 'completed'): ?> project-completed
                            <?php else: ?> project-archived
                            <?php endif; ?>">
                            <?php echo e(ucfirst($project->status)); ?>

                        </span>
                    </div>
                </div>
            </div>

            <!-- Enhanced Kanban Board -->
            <div class="kanban-columns grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Todo Column -->
                <div class="kanban-column-wrapper">
                    <div class="bg-white rounded-lg shadow-sm">
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="font-semibold text-gray-800 flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-gray-400 rounded-full mr-2"></div>
                                    Todo 
                                    <span class="task-count badge-count badge-todo">
                                        <?php echo e($tasksByStatus['todo']->count()); ?>

                                    </span>
                                </div>
                                <?php if($project->userCanManage(auth()->user())): ?>
                                    <button onclick="showQuickAddForm('todo')" 
                                            class="text-gray-400 hover:text-gray-600 transition-colors">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                <?php endif; ?>
                            </h3>
                        </div>
                        <div class="kanban-column p-4 space-y-3 min-h-96" data-status="todo">
                            <?php $__currentLoopData = $tasksByStatus['todo']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="task-card bg-gray-50 border border-gray-200 rounded-lg p-3 hover:shadow-md transition-shadow priority-<?php echo e($task->priority); ?>"
                                     data-task-id="<?php echo e($task->id); ?>" data-project-id="<?php echo e($project->id); ?>">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="font-medium text-gray-900 text-sm"><?php echo e($task->title); ?></h4>
                                        <span class="px-2 py-1 text-xs rounded-full <?php echo e($task->priority_color); ?>">
                                            <?php echo e(ucfirst($task->priority)); ?>

                                        </span>
                                    </div>
                                    
                                    <?php if($task->description): ?>
                                        <p class="text-gray-600 text-xs mb-2 line-clamp-2"><?php echo e($task->description); ?></p>
                                    <?php endif; ?>
                                    
                                    <div class="flex justify-between items-center text-xs text-gray-500 mb-2">
                                        <div class="flex items-center">
                                            <?php if($task->assignedUser): ?>
                                                <div class="assignee-avatar bg-blue-500 mr-1">
                                                    <?php echo e(strtoupper(substr($task->assignedUser->name, 0, 1))); ?>

                                                </div>
                                                <span><?php echo e($task->assignedUser->name); ?></span>
                                            <?php else: ?>
                                                <span>Unassigned</span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if($task->due_date): ?>
                                            <span class="<?php if($task->is_overdue): ?> due-overdue <?php elseif($task->due_date->isToday()): ?> due-today <?php endif; ?>">
                                                <?php echo e($task->due_date->format('M j')); ?>

                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="flex space-x-1">
                                        <a href="<?php echo e(route('projects.tasks.show', [$project, $task])); ?>" 
                                           class="link-primary text-xs">View</a>
                                        <?php if($project->userCanManage(auth()->user()) || $task->assigned_to === auth()->id()): ?>
                                            <button onclick="updateTaskStatus(<?php echo e($task->id); ?>, <?php echo e($project->id); ?>, 'in_progress')"
                                                    class="text-green-500 hover:text-green-700 text-xs ml-2">
                                                Start
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>

                <!-- In Progress Column -->
                <div class="kanban-column-wrapper">
                    <div class="bg-white rounded-lg shadow-sm">
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="font-semibold text-gray-800 flex items-center">
                                <div class="w-3 h-3 bg-blue-400 rounded-full mr-2"></div>
                                In Progress 
                                <span class="task-count badge-count badge-progress">
                                    <?php echo e($tasksByStatus['in_progress']->count()); ?>

                                </span>
                            </h3>
                        </div>
                        <div class="kanban-column p-4 space-y-3 min-h-96" data-status="in_progress">
                            <?php $__currentLoopData = $tasksByStatus['in_progress']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="task-card bg-blue-50 border border-blue-200 rounded-lg p-3 hover:shadow-md transition-shadow priority-<?php echo e($task->priority); ?>"
                                     data-task-id="<?php echo e($task->id); ?>" data-project-id="<?php echo e($project->id); ?>">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="font-medium text-gray-900 text-sm"><?php echo e($task->title); ?></h4>
                                        <span class="px-2 py-1 text-xs rounded-full <?php echo e($task->priority_color); ?>">
                                            <?php echo e(ucfirst($task->priority)); ?>

                                        </span>
                                    </div>
                                    
                                    <?php if($task->description): ?>
                                        <p class="text-gray-600 text-xs mb-2 line-clamp-2"><?php echo e($task->description); ?></p>
                                    <?php endif; ?>
                                    
                                    <div class="flex justify-between items-center text-xs text-gray-500 mb-2">
                                        <div class="flex items-center">
                                            <?php if($task->assignedUser): ?>
                                                <div class="assignee-avatar bg-blue-500 mr-1">
                                                    <?php echo e(strtoupper(substr($task->assignedUser->name, 0, 1))); ?>

                                                </div>
                                                <span><?php echo e($task->assignedUser->name); ?></span>
                                            <?php else: ?>
                                                <span>Unassigned</span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if($task->due_date): ?>
                                            <span class="<?php if($task->is_overdue): ?> due-overdue <?php elseif($task->due_date->isToday()): ?> due-today <?php endif; ?>">
                                                <?php echo e($task->due_date->format('M j')); ?>

                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="flex space-x-1">
                                        <a href="<?php echo e(route('projects.tasks.show', [$project, $task])); ?>" 
                                           class="text-blue-500 hover:text-blue-700 text-xs">View</a>
                                        <?php if($project->userCanManage(auth()->user()) || $task->assigned_to === auth()->id()): ?>
                                            <button onclick="updateTaskStatus(<?php echo e($task->id); ?>, <?php echo e($project->id); ?>, 'done')"
                                                    class="text-green-500 hover:text-green-700 text-xs ml-2">
                                                Complete
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>

                <!-- Done Column -->
                <div class="kanban-column-wrapper">
                    <div class="bg-white rounded-lg shadow-sm">
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="font-semibold text-gray-800 flex items-center">
                                <div class="w-3 h-3 bg-green-400 rounded-full mr-2"></div>
                                Done 
                                <span class="task-count badge-count badge-done">
                                    <?php echo e($tasksByStatus['done']->count()); ?>

                                </span>
                            </h3>
                        </div>
                        <div class="kanban-column p-4 space-y-3 min-h-96" data-status="done">
                            <?php $__currentLoopData = $tasksByStatus['done']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="task-card bg-green-50 border border-green-200 rounded-lg p-3 hover:shadow-md transition-shadow opacity-75 priority-<?php echo e($task->priority); ?>"
                                     data-task-id="<?php echo e($task->id); ?>" data-project-id="<?php echo e($project->id); ?>">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="font-medium text-gray-900 text-sm line-through"><?php echo e($task->title); ?></h4>
                                        <span class="px-2 py-1 text-xs rounded-full <?php echo e($task->priority_color); ?>">
                                            <?php echo e(ucfirst($task->priority)); ?>

                                        </span>
                                    </div>
                                    
                                    <?php if($task->description): ?>
                                        <p class="text-gray-600 text-xs mb-2 line-clamp-2"><?php echo e($task->description); ?></p>
                                    <?php endif; ?>
                                    
                                    <div class="flex justify-between items-center text-xs text-gray-500 mb-2">
                                        <div class="flex items-center">
                                            <?php if($task->assignedUser): ?>
                                                <div class="assignee-avatar bg-blue-500 mr-1">
                                                    <?php echo e(strtoupper(substr($task->assignedUser->name, 0, 1))); ?>

                                                </div>
                                                <span><?php echo e($task->assignedUser->name); ?></span>
                                            <?php else: ?>
                                                <span>Unassigned</span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if($task->due_date): ?>
                                            <span><?php echo e($task->due_date->format('M j')); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div>
                                        <a href="<?php echo e(route('projects.tasks.show', [$project, $task])); ?>" 
                                           class="text-blue-500 hover:text-blue-700 text-xs">View</a>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Members -->
            <?php if($project->members->count() > 0): ?>
                <div class="mt-6 bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Team Members</h3>
                    <div class="flex flex-wrap gap-2">
                        <?php $__currentLoopData = $project->members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center bg-gray-100 rounded-full px-3 py-1">
                                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs mr-2">
                                    <?php echo e(strtoupper(substr($member->name, 0, 1))); ?>

                                </div>
                                <span class="text-sm text-gray-700"><?php echo e($member->name); ?></span>
                                <span class="text-xs text-gray-500 ml-1">(<?php echo e($member->pivot->role); ?>)</span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Quick Add Task Modal -->
    <div id="quickAddModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-bold text-gray-900 text-center mb-4">Quick Add Task</h3>
                <form id="quickAddForm" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="quickTaskStatus" name="status" value="todo">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" id="quickTaskTitle" name="title" required
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Priority</label>
                        <select id="quickTaskPriority" name="priority" 
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                    
                    <div class="flex justify-end space-x-2 pt-4">
                        <button type="button" onclick="hideQuickAddForm()" 
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors">
                            Create Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Quick add form functions
        function showQuickAddForm(status) {
            document.getElementById('quickTaskStatus').value = status;
            document.getElementById('quickAddModal').classList.remove('hidden');
            document.getElementById('quickTaskTitle').focus();
        }

        function hideQuickAddForm() {
            document.getElementById('quickAddModal').classList.add('hidden');
            document.getElementById('quickAddForm').reset();
        }

        // Handle quick add form submission
        document.getElementById('quickAddForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const projectId = <?php echo e($project->id); ?>;
            
            fetch(`/projects/${projectId}/tasks`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    hideQuickAddForm();
                    showNotification('Task created successfully!', 'success');
                    // Reload the page to show the new task
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showNotification('Failed to create task', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to create task', 'error');
            });
        });

        // Close modal when clicking outside
        document.getElementById('quickAddModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideQuickAddForm();
            }
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH A:\myprojects\task-manager\resources\views/projects/show.blade.php ENDPATH**/ ?>