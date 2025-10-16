import './bootstrap';

import Alpine from 'alpinejs';
import Sortable from 'sortablejs';

window.Alpine = Alpine;
window.Sortable = Sortable;

Alpine.start();

// Kanban Board Functionality
document.addEventListener('DOMContentLoaded', function() {
    initializeKanbanBoard();
});

function initializeKanbanBoard() {
    const kanbanColumns = document.querySelectorAll('.kanban-column');
    
    kanbanColumns.forEach(column => {
        new Sortable(column, {
            group: 'kanban',
            animation: 150,
            ghostClass: 'task-ghost',
            chosenClass: 'task-chosen',
            dragClass: 'task-drag',
            onEnd: function(evt) {
                updateTaskStatusDragDrop(evt);
            }
        });
    });
}

function updateTaskStatusDragDrop(evt) {
    const taskCard = evt.item;
    const newColumn = evt.to;
    const taskId = taskCard.dataset.taskId;
    const projectId = taskCard.dataset.projectId;
    const newStatus = newColumn.dataset.status;
    
    // Add loading state
    taskCard.classList.add('task-updating');
    
    // Update task status via AJAX
    fetch(`/projects/${projectId}/tasks/${taskId}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            status: newStatus
        })
    })
    .then(response => response.json())
    .then(data => {
        taskCard.classList.remove('task-updating');
        if (data.success) {
            // Update the task card appearance based on new status
            updateTaskCardAppearance(taskCard, newStatus);
            // Update column counts
            updateColumnCounts();
            // Show success message
            showNotification('Task status updated successfully!', 'success');
        } else {
            // Revert the move if it failed
            evt.from.appendChild(taskCard);
            showNotification('Failed to update task status', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        taskCard.classList.remove('task-updating');
        // Revert the move if it failed
        evt.from.appendChild(taskCard);
        showNotification('Failed to update task status', 'error');
    });
}

// Global function for button-triggered status updates
window.updateTaskStatus = function(taskId, projectId, newStatus) {
    const taskCard = document.querySelector(`[data-task-id="${taskId}"]`);
    
    if (taskCard) {
        taskCard.classList.add('task-updating');
    }
    
    fetch(`/projects/${projectId}/tasks/${taskId}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            status: newStatus
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Move the task card to the correct column
            const targetColumn = document.querySelector(`[data-status="${newStatus}"]`);
            if (taskCard && targetColumn) {
                targetColumn.appendChild(taskCard);
                updateTaskCardAppearance(taskCard, newStatus);
                updateColumnCounts();
                showNotification('Task status updated successfully!', 'success');
            } else {
                // Reload page if DOM manipulation fails
                window.location.reload();
            }
        } else {
            showNotification('Failed to update task status', 'error');
        }
        
        if (taskCard) {
            taskCard.classList.remove('task-updating');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (taskCard) {
            taskCard.classList.remove('task-updating');
        }
        showNotification('Failed to update task status', 'error');
    });
};

function updateTaskCardAppearance(card, status) {
    // Remove old status classes
    card.classList.remove('bg-gray-50', 'border-gray-200', 'bg-blue-50', 'border-blue-200', 'bg-green-50', 'border-green-200');
    
    const titleElement = card.querySelector('h4');
    
    // Add new status classes
    if (status === 'todo') {
        card.classList.add('bg-gray-50', 'border-gray-200');
        titleElement.classList.remove('line-through');
        card.classList.remove('opacity-75');
    } else if (status === 'in_progress') {
        card.classList.add('bg-blue-50', 'border-blue-200');
        titleElement.classList.remove('line-through');
        card.classList.remove('opacity-75');
    } else if (status === 'done') {
        card.classList.add('bg-green-50', 'border-green-200');
        titleElement.classList.add('line-through');
        card.classList.add('opacity-75');
    }
}

function updateColumnCounts() {
    const columns = document.querySelectorAll('.kanban-column');
    columns.forEach(column => {
        const count = column.querySelectorAll('.task-card').length;
        const countBadge = column.parentElement.querySelector('.task-count');
        if (countBadge) {
            countBadge.textContent = count;
        }
    });
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-md shadow-lg z-50 transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}
