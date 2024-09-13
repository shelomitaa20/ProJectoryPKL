<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Task;
use App\Models\UserProject;

class Project extends Model
{
    use HasFactory;

    protected $primaryKey = 'project_id';

    protected $fillable = [
        'name', 'description', 'owner_id', 'status', 'start_date', 'end_date'
    ];

    // Relationship: Owner of the project.
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // Relationship: Team members involved in the project.
    public function teamMembers()
    {
        return $this->belongsToMany(User::class, 'user_projects', 'project_id', 'user_id')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    // Relationship: All users associated with the project.
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_projects', 'project_id', 'user_id')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    // Relationship: Tasks associated with the project.
    public function tasks()
    {
        return $this->hasMany(Task::class, 'project_id');
    }

    // All members except the owner (Optional).
    public function otherMembers()
    {
        return $this->teamMembers()->where('user_id', '!=', $this->owner_id);
    }

    // Relationship: UserProject model.
    public function userProjects()
    {
        return $this->hasMany(UserProject::class, 'project_id');
    }
}