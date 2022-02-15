<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'title_ar', 'image', 'text', 'text_ar', 'type', 'language', 'variation', 'cta',];
    protected $casts = ['cta' => 'array'];
}

