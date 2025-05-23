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
        'client_id',
        'start_time',
        'end_time',
        'hours',
        'log_type',
        'description',
    ];

    // protected $fillable = ['user_id', 'client_id', 'project_id', 'date', 'hours'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Accessor to calculate hours dynamically
    public function getHoursAttribute()
    {
        if ($this->start_time && $this->end_time) {
            return round((strtotime($this->end_time) - strtotime($this->start_time)) / 3600, 2);
        }
        return 0;
    }
}
