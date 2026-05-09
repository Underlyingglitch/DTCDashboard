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
        Schema::table('devices', function (Blueprint $table) {
            $table->boolean('is_online')->default(false)->after('device_id');
            $table->json('current_state')->nullable()->after('is_online');
            $table->json('battery')->nullable()->after('current_state');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->dropColumn(['is_online', 'current_state', 'battery']);
        });
    }
};
