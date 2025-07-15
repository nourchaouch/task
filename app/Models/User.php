<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the tasks assigned to the user.
     */
    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

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
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * The projects that the user manages.
     */
    public function managedProjects()
    {
        return $this->hasMany(Project::class, 'manager_id');
    }

    /**
     * The projects that the user is a member of.
     */
    public function memberProjects()
    {
        return $this->belongsToMany(Project::class, 'project_members')->withTimestamps();
    }

    /**
     * Get all tasks created by the user.
     */
    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    /**
     * Get all comments made by the user.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Check if the user is a project manager.
     */
    public function isProjectManager(): bool
    {
        return $this->role === 'project_manager';
    }

    /**
     * Check if the user is a team member.
     */
    public function isTeamMember(): bool
    {
        return $this->role === 'team_member';
    }

    /**
     * Get the user's role as a formatted string.
     */
    public function getRoleLabel(): string
    {
        return match($this->role) {
            'project_manager' => 'Responsable de projet',
            'team_member' => 'Membre d\'équipe',
            default => 'Rôle inconnu'
        };
    }
}
