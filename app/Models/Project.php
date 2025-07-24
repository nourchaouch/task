<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     *
     */
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
        'manager_id'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get all projects.
     *
     */
    public static function getAllProjects($search = null)
    {
        $query = static::query();

        // If there's a search criteria, filter projects based on it
        if ($search !== null) {
            //$query->whereNull('deleted_at');
            $query->where('name', 'like', '%' . $search . '%');
            $query->orderBy('id', 'DESC');
        }

        return $query->get();
    }

    /**
     * Create a new project.
     *
     */
    public static function createProject(array $data)
    {
        return static::create($data);
    }

    /**
     * Update a project.
     *
     */
    public function updateProject(array $data)
    {
        return $this->update($data);
    }

    /**
     * Soft delete a project.
     *
     */
    public function deleteProject()
    {
        return $this->delete();
    }

    /**
     * Get the manager of the project.
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Get the team members of the project.
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members')->withTimestamps();
    }

    /**
     * Get the tasks for the project.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get the events for the project.
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Get project progress percentage.
     */
    public function getProgressAttribute()
    {
        $totalTasks = $this->tasks()->count();
        if ($totalTasks === 0) return 0;
        
        $completedTasks = $this->tasks()->where('status', 'completed')->count();
        return ($completedTasks / $totalTasks) * 100;
    }
    public function totalevents()
    {
        $totalEvents = $this->events()->count();
        if ($totalEvents === 0) return 0;
        
        $completedEvents = $this->events()->where('status', 'completed')->count();
        return ($completedEvents / $totalEvents) * 100;
    }
}
