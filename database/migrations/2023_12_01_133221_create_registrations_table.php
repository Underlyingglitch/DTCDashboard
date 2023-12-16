<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_day_id')->constrained()->cascadeOnDelete();
            $table->foreignId('gymnast_id')->constrained()->restrictOnDelete();
            $table->foreignId('club_id')->constrained()->restrictOnDelete();
            $table->foreignId('niveau_id')->constrained('niveaus')->restrictOnDelete();
            $table->integer('startnumber');
            $table->foreignId('group_id')->constrained()->restrictOnDelete();
            $table->foreignId('team_id')->nullable()->constrained()->restrictOnDelete();
            $table->boolean('signed_off')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
