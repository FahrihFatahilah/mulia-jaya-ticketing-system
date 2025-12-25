<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->enum('purchase_type', ['agent', 'kantor'])->default('kantor')->after('type');
            $table->string('agent_code')->nullable()->after('purchase_type');
            $table->text('payment_description')->nullable()->after('payment_status');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['purchase_type', 'agent_code', 'payment_description']);
        });
    }
};