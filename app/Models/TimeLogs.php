<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TimeLogs extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'start_time',
        'end_time',
        'hours',
        'log_type',
        'description',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
