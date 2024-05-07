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
        Schema::create('calendar_items', function (Blueprint $table) {
            $table->id();
            $table->integer('event_id');
            $table->string('title');
            $table->string('discipline')->nullable();
            $table->string('district');
            $table->string('place')->nullable();
            $table->date('date_from');
            $table->date('date_to')->nullable();
            $table->text('results')->nullable();
            $table->json('results_files')->default('[]');
            $table->text('program')->nullable();
            $table->json('program_files')->default('[]');
            $table->text('description')->nullable();
            $table->json('description_files')->default('[]');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_items');
    }
};
