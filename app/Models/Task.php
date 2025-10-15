<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Task extends Model
{
    protected $fillable = [
        'project_id',
        'assigned_to',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    // Status constants
    const STATUS_TODO = 'todo';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_DONE = 'done';

    // Priority constants
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';

    public static function getStatuses(): array
    {
        return [
            self::STATUS_TODO => 'Todo',
            self::STATUS_IN_PROGRESS => 'In Progress',
            self::STATUS_DONE => 'Done',
        ];
    }

    public static function getPriorities(): array
    {
        return [
            self::PRIORITY_LOW => 'Low',
            self::PRIORITY_MEDIUM => 'Medium',
            self::PRIORITY_HIGH => 'High',
        ];
    }

    /**
     * The project this task belongs to
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * The user assigned to this task
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Comments on this task
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get priority color class for UI
     */
    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            self::PRIORITY_HIGH => 'text-red-600 bg-red-100',
            self::PRIORITY_MEDIUM => 'text-yellow-600 bg-yellow-100',
            self::PRIORITY_LOW => 'text-green-600 bg-green-100',
            default => 'text-gray-600 bg-gray-100',
        };
    }

    /**
     * Get status color class for UI
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_DONE => 'text-green-600 bg-green-100',
            self::STATUS_IN_PROGRESS => 'text-blue-600 bg-blue-100',
            self::STATUS_TODO => 'text-gray-600 bg-gray-100',
            default => 'text-gray-600 bg-gray-100',
        };
    }

    /**
     * Check if task is overdue
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date && 
               $this->due_date->isPast() && 
               $this->status !== self::STATUS_DONE;
    }

    /**
     * Get days until due date
     */
    public function getDaysUntilDueAttribute(): ?int
    {
        if (!$this->due_date) {
            return null;
        }

        return Carbon::now()->diffInDays($this->due_date, false);
    }

    /**
     * Scope for overdue tasks
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                     ->where('status', '!=', self::STATUS_DONE);
    }

    /**
     * Scope for tasks assigned to a specific user
     */
    public function scopeMyTasks($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    /**
     * Scope for high priority tasks
     */
    public function scopeHighPriority($query)
    {
        return $query->where('priority', self::PRIORITY_HIGH);
    }

    /**
     * Scope for tasks due soon (within next 3 days)
     */
    public function scopeDueSoon($query)
    {
        return $query->where('due_date', '<=', now()->addDays(3))
                     ->where('due_date', '>=', now())
                     ->where('status', '!=', self::STATUS_DONE);
    }

    /**
     * Scope for incomplete tasks
     */
    public function scopeIncomplete($query)
    {
        return $query->where('status', '!=', self::STATUS_DONE);
    }

    /**
     * Mark task as completed
     */
    public function markAsCompleted(): bool
    {
        return $this->update(['status' => self::STATUS_DONE]);
    }

    /**
     * Assign task to user
     */
    public function assignTo(User $user): bool
    {
        return $this->update(['assigned_to' => $user->id]);
    }
}
