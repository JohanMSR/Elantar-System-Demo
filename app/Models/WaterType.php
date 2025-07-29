<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// This model is now deprecated. Use App\Models\I013tTiposAgua instead.
class WaterType extends Model
{
    use HasFactory;

    protected $table = 'i023t_water_type';

    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function verifications()
    {
        return $this->belongsToMany(Verification::class, 'i025t_verification_by_water_type', 'water_type_id', 'verification_id');
    }

    public function expenses()
    {
        return $this->belongsToMany(Expense::class, 'i024t_expense_by_water_type', 'water_type_id', 'expense_id');
    }
} 