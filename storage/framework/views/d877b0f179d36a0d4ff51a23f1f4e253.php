<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    <?php echo e($project->name); ?>

                </h2>
                <p class="text-gray-600 mt-1"><?php echo e($project->description); ?></p>
            </div>
            <div class="flex space-x-2">
                <?php if($project->userCanManage(auth()->user())): ?>
                    <a href="<?php echo e(route('projects.tasks.create', $project)); ?>" 
                       class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Add Task
                    </a>
                    <a href="<?php echo e(route('projects.edit', $project)); ?>" 
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Edit Project
                    </a>
                <?php endif; ?>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Project Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4">
                        <div class="text-sm font-medium text-gray-500">Progress</div>
                        <div class="text-2xl font-bold text-gray-900"><?php echo e($project->completion_percentage); ?>%</div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo e($project->completion_percentage); ?>%"></div>
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
                        <span class="px-2 py-1 text-sm rounded-full 
                            <?php if($project->status === 'active'): ?> bg-green-100 text-green-800
                            <?php elseif($project->status === 'completed'): ?> bg-blue-100 text-blue-800  
                            <?php else: ?> bg-gray-100 text-gray-800
                            <?php endif; ?>">
                            <?php echo e(ucfirst($project->status)); ?>

                        </span>
                    </div>
                </div>
            </div>

            <!-- Kanban Board -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Todo Column -->
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="font-semibold text-gray-800 flex items-center">
                            <div class="w-3 h-3 bg-gray-400 rounded-full mr-2"></div>
                            Todo 
                            <span class="ml-2 bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full">
                                <?php echo e($tasksByStatus['todo']->count()); ?>

                            </span>
                        </h3>
                    </div>
                    <div class="p-4 space-y-3 min-h-96">
                        <?php $__currentLoopData = $tasksByStatus['todo']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 hover:shadow-md transition-shadow">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-medium text-gray-900 text-sm"><?php echo e($task->title); ?></h4>
                                    <span class="px-2 py-1 text-xs rounded-full <?php echo e($task->priority_color); ?>">
                                        <?php echo e(ucfirst($task->priority)); ?>

                                    </span>
                                </div>
                                
                                <?php if($task->description): ?>
                                    <p class="text-gray-600 text-xs mb-2 line-clamp-2"><?php echo e($task->description); ?></p>
                                <?php endif; ?>
                                
                                <div class="flex justify-between items-center text-xs text-gray-500">
                                    <span>
                                        <?php if($task->assignedUser): ?>
                                            <?php echo e($task->assignedUser->name); ?>

                                        <?php else: ?>
                                            Unassigned
                                        <?php endif; ?>
                                    </span>
                                    <?php if($task->due_date): ?>
                                        <span class="<?php if($task->is_overdue): ?> text-red-600 <?php endif; ?>">
                                            <?php echo e($task->due_date->format('M j')); ?>

                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="mt-2 flex space-x-1">
                                    <a href="<?php echo e(route('projects.tasks.show', [$project, $task])); ?>" 
                                       class="text-blue-500 hover:text-blue-700 text-xs">View</a>
                                    <?php if($project->userCanManage(auth()->user()) || $task->assigned_to === auth()->id()): ?>
                                        <form method="POST" action="<?php echo e(route('tasks.update-status', [$project, $task])); ?>" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <input type="hidden" name="status" value="in_progress">
                                            <button type="submit" class="text-green-500 hover:text-green-700 text-xs ml-2">
                                                Start
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <!-- In Progress Column -->
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="font-semibold text-gray-800 flex items-center">
                            <div class="w-3 h-3 bg-blue-400 rounded-full mr-2"></div>
                            In Progress 
                            <span class="ml-2 bg-blue-100 text-blue-600 text-xs px-2 py-1 rounded-full">
                                <?php echo e($tasksByStatus['in_progress']->count()); ?>

                            </span>
                        </h3>
                    </div>
                    <div class="p-4 space-y-3 min-h-96">
                        <?php $__currentLoopData = $tasksByStatus['in_progress']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 hover:shadow-md transition-shadow">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-medium text-gray-900 text-sm"><?php echo e($task->title); ?></h4>
                                    <span class="px-2 py-1 text-xs rounded-full <?php echo e($task->priority_color); ?>">
                                        <?php echo e(ucfirst($task->priority)); ?>

                                    </span>
                                </div>
                                
                                <?php if($task->description): ?>
                                    <p class="text-gray-600 text-xs mb-2 line-clamp-2"><?php echo e($task->description); ?></p>
                                <?php endif; ?>
                                
                                <div class="flex justify-between items-center text-xs text-gray-500">
                                    <span>
                                        <?php if($task->assignedUser): ?>
                                            <?php echo e($task->assignedUser->name); ?>

                                        <?php else: ?>
                                            Unassigned
                                        <?php endif; ?>
                                    </span>
                                    <?php if($task->due_date): ?>
                                        <span class="<?php if($task->is_overdue): ?> text-red-600 <?php endif; ?>">
                                            <?php echo e($task->due_date->format('M j')); ?>

                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="mt-2 flex space-x-1">
                                    <a href="<?php echo e(route('projects.tasks.show', [$project, $task])); ?>" 
                                       class="text-blue-500 hover:text-blue-700 text-xs">View</a>
                                    <?php if($project->userCanManage(auth()->user()) || $task->assigned_to === auth()->id()): ?>
                                        <form method="POST" action="<?php echo e(route('tasks.update-status', [$project, $task])); ?>" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <input type="hidden" name="status" value="done">
                                            <button type="submit" class="text-green-500 hover:text-green-700 text-xs ml-2">
                                                Complete
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <!-- Done Column -->
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="font-semibold text-gray-800 flex items-center">
                            <div class="w-3 h-3 bg-green-400 rounded-full mr-2"></div>
                            Done 
                            <span class="ml-2 bg-green-100 text-green-600 text-xs px-2 py-1 rounded-full">
                                <?php echo e($tasksByStatus['done']->count()); ?>

                            </span>
                        </h3>
                    </div>
                    <div class="p-4 space-y-3 min-h-96">
                        <?php $__currentLoopData = $tasksByStatus['done']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="bg-green-50 border border-green-200 rounded-lg p-3 hover:shadow-md transition-shadow opacity-75">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-medium text-gray-900 text-sm line-through"><?php echo e($task->title); ?></h4>
                                    <span class="px-2 py-1 text-xs rounded-full <?php echo e($task->priority_color); ?>">
                                        <?php echo e(ucfirst($task->priority)); ?>

                                    </span>
                                </div>
                                
                                <?php if($task->description): ?>
                                    <p class="text-gray-600 text-xs mb-2 line-clamp-2"><?php echo e($task->description); ?></p>
                                <?php endif; ?>
                                
                                <div class="flex justify-between items-center text-xs text-gray-500">
                                    <span>
                                        <?php if($task->assignedUser): ?>
                                            <?php echo e($task->assignedUser->name); ?>

                                        <?php else: ?>
                                            Unassigned
                                        <?php endif; ?>
                                    </span>
                                    <?php if($task->due_date): ?>
                                        <span><?php echo e($task->due_date->format('M j')); ?></span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="mt-2">
                                    <a href="<?php echo e(route('projects.tasks.show', [$project, $task])); ?>" 
                                       class="text-blue-500 hover:text-blue-700 text-xs">View</a>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH A:\myprojects\task-manager\resources\views/projects/show.blade.php ENDPATH**/ ?>