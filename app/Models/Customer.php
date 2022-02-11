<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    protected $fillable = ['name', 'phone', 'username', 'email', 'password', 'avatar', 'tookan_id'];
    protected $hidden = ['id', 'password', 'updated_at', 'delete_at', 'tookan_id'];

//    protected $with = ['locations'];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function locations()
    {
        return $this->hasMany(Locations::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

}
