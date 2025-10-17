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
    if (!taskId || !projectId) {
        console.error('Missing taskId or projectId on task card', { taskId, projectId });
        taskCard.classList.remove('task-updating');
        return;
    }
    console.log(`Updating task ${taskId} to status ${newStatus}`);
    // Add loading state
    taskCard.classList.add('task-updating');
    
    // Update task status via AJAX
    fetch(`/projects/${projectId}/tasks/${taskId}/status`, {
        method: 'PATCH',
        credentials: 'same-origin',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            status: newStatus
        })
    })
    .then(response => {
        const contentType = response.headers.get('content-type') || '';
        if (!response.ok) {
            if (response.status === 401 || response.status === 302 || response.status === 301) {
                // Authentication/redirect detected
                throw new Error('auth-redirect');
            }
            throw new Error(`HTTP ${response.status}`);
        }
        if (!contentType.includes('application/json')) {
            // Likely a redirect to login page (HTML)
            throw new Error('non-json');
        }
        return response.json();
    })
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
        try { evt.from.appendChild(taskCard); } catch (e) { /* ignore */ }
        if (error.message === 'auth-redirect' || error.message === 'non-json') {
            showNotification('You appear to be logged out. Please refresh the page and sign in again.', 'error');
        } else {
            showNotification('Failed to update task status', 'error');
        }
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
        credentials: 'same-origin',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            status: newStatus
        })
    })
    .then(response => {
        const contentType = response.headers.get('content-type') || '';
        if (!response.ok) {
            if (response.status === 401 || response.status === 302 || response.status === 301) {
                throw new Error('auth-redirect');
            }
            throw new Error(`HTTP ${response.status}`);
        }
        if (!contentType.includes('application/json')) {
            throw new Error('non-json');
        }
        return response.json();
    })
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
                // If DOM manipulation fails, show error but don't reload
                showNotification('Task moved, but UI update failed.', 'error');
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
        if (error.message === 'auth-redirect' || error.message === 'non-json') {
            showNotification('You appear to be logged out. Please refresh the page and sign in again.', 'error');
        } else {
            showNotification('Failed to update task status', 'error');
        }
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
