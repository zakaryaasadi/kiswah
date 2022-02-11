<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Locations extends Model
{
    protected $table = 'locations';
    use HasFactory, SoftDeletes;

    protected $fillable = ['latitude', 'longitude', 'title', 'address', 'customer_id', 'is_default',
        'building_no', 'floor', 'apartment_no', 'description', 'region_id'];
    protected $hidden = ['updated_at', 'delete_at', 'customer_id',];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
