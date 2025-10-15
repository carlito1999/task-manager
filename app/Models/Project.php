<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'status',
        'deadline',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    // Status constants
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_ARCHIVED = 'archived';

    public static function getStatuses(): array
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_ARCHIVED => 'Archived',
        ];
    }

    /**
     * The user who created/owns this project
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * All team members (including owner) with their roles
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('role')
                    ->withTimestamps();
    }

    /**
     * All tasks in this project
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Tasks grouped by status for Kanban view
     */
    public function tasksByStatus(): array
    {
        return [
            'todo' => $this->tasks()->where('status', 'todo')->get(),
            'in_progress' => $this->tasks()->where('status', 'in_progress')->get(),
            'done' => $this->tasks()->where('status', 'done')->get(),
        ];
    }

    /**
     * Get completion percentage
     */
    public function getCompletionPercentageAttribute(): int
    {
        $totalTasks = $this->tasks()->count();
        if ($totalTasks === 0) {
            return 0;
        }
        
        $completedTasks = $this->tasks()->where('status', 'done')->count();
        return round(($completedTasks / $totalTasks) * 100);
    }

    /**
     * Check if user can manage this project (owner or member)
     */
    public function userCanManage(User $user): bool
    {
        return $this->user_id === $user->id || 
               $this->members()->where('user_id', $user->id)
                              ->whereIn('role', ['owner', 'member'])
                              ->exists();
    }

    /**
     * Check if user can view this project
     */
    public function userCanView(User $user): bool
    {
        return $this->user_id === $user->id || 
               $this->members()->where('user_id', $user->id)->exists();
    }

    /**
     * Scope for active projects
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope for projects user is involved in
     */
    public function scopeForUser($query, User $user)
    {
        return $query->where('user_id', $user->id)
                     ->orWhereHas('members', function ($q) use ($user) {
                         $q->where('user_id', $user->id);
                     });
    }
}
