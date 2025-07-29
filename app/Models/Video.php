<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table = 'c022t_videos';
    
    protected $fillable = [
        'title',
        'description',
        'url',
        'thumbnail',
        'duration',
        'status'
    ];
} 