<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->date('report_date');
            $table->integer('total_bookings')->default(0);
            $table->integer('passenger_bookings')->default(0);
            $table->integer('cargo_bookings')->default(0);
            $table->integer('agent_bookings')->default(0);
            $table->integer('kantor_bookings')->default(0);
            $table->decimal('total_income', 15, 2)->default(0);
            $table->decimal('agent_income', 15, 2)->default(0);
            $table->decimal('kantor_income', 15, 2)->default(0);
            $table->timestamps();
            
            $table->unique('report_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_reports');
    }
};