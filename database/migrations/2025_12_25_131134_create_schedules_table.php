<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained();
            $table->foreignId('bus_id')->constrained();
            $table->date('departure_date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['route_id', 'bus_id', 'departure_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
