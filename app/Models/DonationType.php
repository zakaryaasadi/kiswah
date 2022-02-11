<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'icon', 'is_available', 'is_acceptable'];
//    protected $hidden = ['id', 'updated_at', 'created_at'];l
}
