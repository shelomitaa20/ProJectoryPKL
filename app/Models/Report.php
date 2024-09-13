<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';
    protected $primaryKey = 'report_id';
    public $timestamps = true;

    protected $fillable = [
        'admin_id',
        'month',
        'year',
        'total_projects',
        'total_in_progress',
        'total_completed',
        'total_users',
    ];

    // Relationship: Admin who generated the report.
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
