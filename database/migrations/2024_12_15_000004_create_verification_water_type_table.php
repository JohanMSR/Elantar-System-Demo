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
        Schema::create('i025t_verification_by_water_type', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('verification_id');
            $table->unsignedBigInteger('co_tipo_agua');
            $table->timestamps();
            
            $table->foreign('verification_id')->references('id')->on('i027t_verification')->onDelete('cascade');
            $table->foreign('co_tipo_agua')->references('co_tipo_agua')->on('i013t_tipos_aguas')->onDelete('cascade');
            
            $table->unique(['verification_id', 'co_tipo_agua']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('i025t_verification_by_water_type');
    }
}; 