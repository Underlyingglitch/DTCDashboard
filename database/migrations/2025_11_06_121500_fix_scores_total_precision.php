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
        // Ensure total column keeps 3 decimal places
        Schema::table('scores', function (Blueprint $table) {
            $table->float('total', 5, 3)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scores', function (Blueprint $table) {
            $table->float('total', 5)->nullable()->change();
        });
    }
};
