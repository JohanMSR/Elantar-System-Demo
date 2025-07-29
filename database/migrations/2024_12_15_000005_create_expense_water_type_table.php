<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('i024t_expense_by_water_type', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('expense_id');
            $table->unsignedBigInteger('co_tipo_agua');
            $table->timestamps();
            
            $table->foreign('expense_id')->references('id')->on('i023t_expense')->onDelete('cascade');
            $table->foreign('co_tipo_agua')->references('co_tipo_agua')->on('i013t_tipos_aguas')->onDelete('cascade');
            
            $table->unique(['expense_id', 'co_tipo_agua']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('i024t_expense_by_water_type');
    }
}; 