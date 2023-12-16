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
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_day_id')->constrained()->cascadeOnDelete();
            $table->integer('startnumber');
            $table->integer('toestel');
            $table->float('d', 5, 3);
            $table->float('e', 5, 3);
            $table->float('n', 5, 3);
            $table->float('total', 5, 3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};
