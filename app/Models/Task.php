<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $primaryKey = 'task_id';

    protected $fillable = [
        'project_id', 'name', 'description', 'status', 'assigned_to', 'due_date', 'pending_completion', 'file_path', 'file_link', 'rejection_reason'
    ];

    // Relationship: A task belongs to a project.
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    // Relationship: A task is assigned to a user.
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}