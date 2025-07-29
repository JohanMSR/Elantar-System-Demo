<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $table = 'i023t_expense';

    protected $fillable = [
        'name',
        'description',
        'estimated_cost',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'estimated_cost' => 'decimal:2',
    ];

    public function waterTypes()
    {
        return $this->belongsToMany(\App\Models\I013tTiposAgua::class, 'i024t_expense_by_water_type', 'expense_id', 'co_tipo_agua');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
} 