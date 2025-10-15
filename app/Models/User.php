<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Projects owned by this user
     */
    public function ownedProjects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Projects this user is a member of (including owned projects)
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class)
                    ->withPivot('role')
                    ->withTimestamps();
    }

    /**
     * Tasks assigned to this user
     */
    public function assignedTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    /**
     * Comments made by this user
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get all projects user has access to (owned + member of)
     */
    public function allProjects()
    {
        return Project::where('user_id', $this->id)
                     ->orWhereHas('members', function ($query) {
                         $query->where('user_id', $this->id);
                     });
    }

    /**
     * Get user's tasks that are due soon
     */
    public function upcomingTasks()
    {
        return $this->assignedTasks()
                   ->dueSoon()
                   ->incomplete()
                   ->with('project')
                   ->orderBy('due_date', 'asc');
    }

    /**
     * Get user's overdue tasks
     */
    public function overdueTasks()
    {
        return $this->assignedTasks()
                   ->overdue()
                   ->with('project')
                   ->orderBy('due_date', 'asc');
    }

    /**
     * Get task statistics for dashboard
     */
    public function getTaskStatsAttribute(): array
    {
        $assignedTasks = $this->assignedTasks();
        
        return [
            'total' => $assignedTasks->count(),
            'completed' => $assignedTasks->where('status', Task::STATUS_DONE)->count(),
            'in_progress' => $assignedTasks->where('status', Task::STATUS_IN_PROGRESS)->count(),
            'todo' => $assignedTasks->where('status', Task::STATUS_TODO)->count(),
            'overdue' => $assignedTasks->overdue()->count(),
        ];
    }

    /**
     * Check if user can access a project
     */
    public function canAccessProject(Project $project): bool
    {
        return $project->userCanView($this);
    }

    /**
     * Check if user can manage a project
     */
    public function canManageProject(Project $project): bool
    {
        return $project->userCanManage($this);
    }
}
