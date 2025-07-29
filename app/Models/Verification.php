<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verification extends Model
{
    use HasFactory;

    protected $table = 'i027t_verification';

    protected $fillable = [
        'name',
        'description',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function waterTypes()
    {
        return $this->belongsToMany(\App\Models\I013tTiposAgua::class, 'i025t_verification_by_water_type', 'verification_id', 'co_tipo_agua');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
} 