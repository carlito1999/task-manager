<?php $__env->startSection('content'); ?>
    <!-- Header -->
    <div class="bg-white shadow mb-6">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    <?php echo e(__('My Projects')); ?>

                </h2>
                <a href="<?php echo e(route('projects.create')); ?>" class="btn-primary">
                    Create New Project
                </a>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <?php if($projects->count() > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                            <div class="p-6">
                                <!-- Project Header -->
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                            <a href="<?php echo e(route('projects.show', $project)); ?>" class="hover:text-blue-600">
                                                <?php echo e($project->name); ?>

                                            </a>
                                        </h3>
                                        <span class="project-status-badge 
                                            <?php if($project->status === 'active'): ?> project-active
                                            <?php elseif($project->status === 'completed'): ?> project-completed  
                                            <?php else: ?> project-archived
                                            <?php endif; ?>">
                                            <?php echo e(ucfirst($project->status)); ?>

                                        </span>
                                    </div>
                                    
                                    <!-- Actions Dropdown -->
                                    <div x-data="{ open: false }" class="relative">
                                        <button @click="open = !open" class="text-gray-400 hover:text-gray-600">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                            </svg>
                                        </button>
                                        
                                        <div x-show="open" @click.away="open = false" 
                                             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10">
                                            <div class="py-1">
                                                <a href="<?php echo e(route('projects.show', $project)); ?>" 
                                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">View</a>
                                                <?php if($project->userCanManage(auth()->user())): ?>
                                                    <a href="<?php echo e(route('projects.edit', $project)); ?>" 
                                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit</a>
                                                <?php endif; ?>
                                                <?php if($project->user_id === auth()->id()): ?>
                                                    <form method="POST" action="<?php echo e(route('projects.destroy', $project)); ?>" 
                                                          onsubmit="return confirm('Are you sure?')">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" 
                                                                class="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-gray-100">
                                                            Delete
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Project Description -->
                                <?php if($project->description): ?>
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-2"><?php echo e($project->description); ?></p>
                                <?php endif; ?>

                                <!-- Project Stats -->
                                <div class="space-y-3">
                                    <!-- Progress Bar -->
                                    <div>
                                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                                            <span>Progress</span>
                                            <span><?php echo e($project->completion_percentage); ?>%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" 
                                                 style="width: <?php echo e($project->completion_percentage); ?>%"></div>
                                        </div>
                                    </div>

                                    <!-- Task Count -->
                                    <div class="flex justify-between text-sm text-gray-600">
                                        <span>Tasks</span>
                                        <span><?php echo e($project->tasks->count()); ?> total</span>
                                    </div>

                                    <!-- Team Members -->
                                    <div class="flex justify-between text-sm text-gray-600">
                                        <span>Team</span>
                                        <span><?php echo e($project->members->count()); ?> members</span>
                                    </div>

                                    <!-- Deadline -->
                                    <?php if($project->deadline): ?>
                                        <div class="flex justify-between text-sm text-gray-600">
                                            <span>Deadline</span>
                                            <span class="<?php if($project->deadline->isPast()): ?> text-red-600 <?php endif; ?>">
                                                <?php echo e($project->deadline->format('M j, Y')); ?>

                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Action Buttons -->
                                <div class="mt-4 flex space-x-2">
                                    <a href="<?php echo e(route('projects.show', $project)); ?>" 
                                       class="flex-1 text-center btn-primary text-sm">
                                        View Project
                                    </a>
                                    <?php if($project->userCanManage(auth()->user())): ?>
                                        <a href="<?php echo e(route('projects.tasks.create', $project)); ?>" 
                                           class="flex-1 text-center btn-success text-sm">
                                            Add Task
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    <?php echo e($projects->links()); ?>

                </div>
            <?php else: ?>
                <!-- Empty State -->
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No projects</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating your first project.</p>
                    <div class="mt-6">
                        <a href="<?php echo e(route('projects.create')); ?>" 
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            New Project
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH A:\myprojects\task-manager\resources\views/projects/index.blade.php ENDPATH**/ ?>