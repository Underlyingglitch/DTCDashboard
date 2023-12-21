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
        Schema::create('processed_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wedstrijd_id')->constrained()->cascadeOnDelete();
            $table->integer('group_id');
            $table->integer('toestel');
            $table->boolean('completed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('processed_scores');
    }
};
