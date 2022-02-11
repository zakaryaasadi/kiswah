<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Region extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'data', 'teams', 'fleet_id', 'tookan_id', 'added_by', 'days', 'capacity'];
    protected $casts = ['days' => 'array', 'teams' => 'array',];

    protected function getIsAvailableAttribute()
    {
        $count = DB::table('tasks')->whereIn('job_status', [0, 1, 4, 6, 7])->count();
        return $this->capacity > $count;
    }
}
