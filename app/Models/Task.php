<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id', 'job_description', 'job_delivery_datetime', 'team_id', 'is_pickup', 'donations', 'tookan_id',
        'customer_id', 'location_id', 'tags', 'meta_data', 'ref_images', 'fleet_id', 'donation_id', 'auto_assignment',
        'tracking_link',
        'task_history', 'job_status', 'arrived_datetime', 'started_datetime', 'completed_datetime', 'acknowledged_datetime'];


    protected $with = ['location', 'status',];

    protected $appends = ['donation_types'];

    protected $hidden = [
        'location_id', 'auto_assignment', 'team_id', 'deleted_at',
        'fleet_id', 'updated_at', 'auto_assignment', 'customer_id', 'region_id'
    ];

    protected $casts = [
        'ref_images' => 'array',
        'meta_data' => 'array',
        'tags' => 'array',
        'donations' => 'array',
        'task_history' => 'array',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->order_id = "ORD" . random_int(100000, 999999);
        });
    }


    public function location()
    {
        return $this->hasOne(Locations::class, 'id', 'location_id');
    }

    public function status()
    {
        return $this->hasOne(TaskStatus::class);
    }

    public function donation()
    {
        return $this->donations;
    }

    public function getDonationTypesAttribute()
    {
        return DonationType::whereIn('id', $this->donations ?? [])->get();
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }
}
