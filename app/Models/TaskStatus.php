<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskStatus extends Model
{
    use HasFactory;

    protected $fillable = ['task_id', 'requested', 'agent_assigned', 'picked', 'on_way', 'completed', 'status_readable'];
    protected $hidden = ['task_id', 'id', 'updated_at', 'created_at'];
    protected $appends = ['status_index'];

    public function getStatusIndexAttribute()
    {
        try {
            return [
                'Request Sent' => 0,
                'Requested' => 0,
                'Agent Assigned' => 1,
                'Picked Up' => 2,
                'On the Way' => 3,
                'Completed' => 4
            ][$this->status_readable];
        } catch (\Exception $e) {
            return 0;
        }
    }

}
