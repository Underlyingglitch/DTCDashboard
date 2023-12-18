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
        Schema::table('wedstrijds', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('trainers', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('teams', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('declarations', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('gymnasts', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('juries', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('locations', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('match_days', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('competitions', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('clubs', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
