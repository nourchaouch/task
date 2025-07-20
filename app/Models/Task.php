<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     */
    protected $fillable = [
        'title',
        'description',
        'project_id',
        'status',
        'priority',
        'due_date',
        'created_by',
        'assigned_to'
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    /**
     * The attributes that should be appended to arrays.
     */
    protected $appends = ['is_overdue', 'status_label'];

    /**
     * Get the project that owns the task.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user who created the task.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the assigned users.
     */
    public function assignees()
    {
        return $this->belongsToMany(User::class, 'task_assignments', 'task_id', 'user_id')->withTimestamps();
    }

    /**
     * Get the user assigned to the task.
     */
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the comments for the task.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the attachments for the task.
     */
    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    /**
     * Check if the task is overdue.
     */
    public function getIsOverdueAttribute()
    {
        if (!$this->due_date) return false;
        return $this->status !== 'completed' && $this->due_date < now();
    }

    /**
     * Get the human-readable status label.
     */
    public function getStatusLabelAttribute()
    {
        return [
            'todo' => 'À faire',
            'in_progress' => 'En cours',
            'blocked' => 'Bloqué',
            'completed' => 'Terminé'
        ][$this->status] ?? $this->status;
    }

    /**
     * Get tasks due soon (within the next 3 days).
     */
    public static function getDueSoon()
    {
        return static::where('status', '!=', 'completed')
            ->whereBetween('due_date', [now(), now()->addDays(3)])
            ->get();
    }

    /**
     * Get overdue tasks.
     */
    public static function getOverdue()
    {
        return static::where('status', '!=', 'completed')
            ->where('due_date', '<', now())
            ->get();
    }

    /**
     * Get all tasks.
     *
     */
    public static function getAllTasks($search = null)
    {
        $query = static::query();

        // If there's a search criteria, filter tasks based on it
        if ($search !== null) {
            $query->whereNull('tasks.deleted_at');
            $query->where('tasks.name', 'like', '%' . $search . '%');
            $query->join('projects', 'tasks.project', '=', 'projects.id');
            $query->select('tasks.*', 'projects.name as project_name', 'projects.color as project_color');
            $query->orderBy('tasks.priority', 'ASC'); // Order by priority
        } else {
            $query->whereNull('tasks.deleted_at');
            $query->join('projects', 'tasks.project', '=', 'projects.id');
            $query->select('tasks.*', 'projects.name as project_name', 'projects.color as project_color');
            $query->orderBy('tasks.priority', 'ASC'); // Order by priority
        }

        return $query->get();
    }

    /**
     * Get all tasks and optionally filter them.
     *
     */
    public static function getAllTasksWithFilters($filters = [])
    {
        $query = static::query();

        $query->join('projects', 'tasks.project', '=', 'projects.id')
            ->select('tasks.*', 'projects.name as project_name', 'projects.color as project_color')
            ->whereNull('tasks.deleted_at');

        if (isset($filters['project'])) {
            $query->where('tasks.project', $filters['project']);
        }

        if (isset($filters['is_completed'])) {
            $query->where('tasks.is_completed', $filters['is_completed']);
        }

        return $query->orderBy('tasks.priority', 'ASC')->get();
    }


    /**
     * Create a new task.
     *
     */
    public static function createTask(array $data)
    {
        return static::create($data);
    }

    /**
     * Update a task.
     *
     */
    public function updateTask(array $data)
    {
        return $this->update($data);
    }

    /**
     * Soft delete a task.
     *
     */
    public function deleteTask()
    {
        return $this->delete();
    }
}
