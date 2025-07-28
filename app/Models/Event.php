<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory ;

    /**
     * The attributes that are mass assignable.
     *
     */
    protected $fillable = [
        'title',
        'description',
        'project_id',
        'status',
        'start_date',
        'end_date',
        'created_by'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * The attributes that should be appended to arrays.
     */
    protected $appends = ['is_overdue', 'status_label'];

    /**
     * Get the project that owns the event.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user who created the event.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the comments for the event.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the attachments for the event.
     */
    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    /**
     * Check if the event is overdue.
     */
    public function getIsOverdueAttribute()
    {
        if (!$this->date) return false;
        return $this->status !== 'completed' && $this->date < now();
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
     * Get events due soon (within the next 3 days).
     */
    public static function getDueSoon()
    {
        return static::where('status', '!=', 'completed')
            ->whereBetween('date', [now(), now()->addDays(3)])
            ->get();
    }

    /**
     * Get overdue events.
     */
    public static function getOverdue()
    {
        return static::where('status', '!=', 'completed')
            ->where('date', '<', now())
            ->get();
    }

    /**
     * Get all events.
     *
     */
    public static function getAllEvents($search = null)
    {
        $query = static::query();

        // If there's a search criteria, filter events based on it
        if ($search !== null) {
            //$query->whereNull('events.deleted_at');
            $query->where('events.name', 'like', '%' . $search . '%');
            $query->join('projects', 'events.project', '=', 'projects.id');
            $query->select('events.*', 'projects.name as project_name');
        } else {
            //$query->whereNull('events.deleted_at');
            $query->join('projects', 'events.project', '=', 'projects.id');
            $query->select('events.*', 'projects.name as project_name');
        }

        return $query->get();
    }

    /**
     * Get all events and optionally filter them.
     *
     */
    public static function getAllEventWithFilters($filters = [])
    {
        $query = static::query();

        $query->join('projects', 'Event.project', '=', 'projects.id')
            ->select('events.*', 'projects.name as project_name')
            ->whereNull('events.deleted_at');

        if (isset($filters['project'])) {
            $query->where('events.project', $filters['project']);
        }

        if (isset($filters['is_completed'])) {
            $query->where('events.is_completed', $filters['is_completed']);
        }

    }


    /**
     * Create a new event.
     *
     */
    public static function createevent(array $data)
    {
        return static::create($data);
    }

    /**
     * Update a event
     *
     */
    public function updateevent(array $data)
    {
        return $this->update($data);
    }

    /**
     * Soft delete a event
     *
     */
    public function deleteevent()
    {
        return $this->delete();
    }
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->softDeletes();
        });
    }
 
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
