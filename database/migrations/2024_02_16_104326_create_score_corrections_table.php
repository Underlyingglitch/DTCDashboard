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
        Schema::create('score_corrections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('score_id')->constrained();
            $table->float('d', 5, 3);
            $table->float('e1', 5, 3);
            $table->float('e2', 5, 3)->nullable();
            $table->float('e3', 5, 3)->nullable();
            $table->float('n', 5, 3)->default(0);
            $table->float('total', 5, 3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('score_corrections');
    }
};
