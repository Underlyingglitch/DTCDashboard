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
        Schema::table('score_corrections', function (Blueprint $table) {
            $table->integer('startnumber')->after('id')->nullable();
        });
        $scs = \App\Models\ScoreCorrection::withTrashed();
        foreach ($scs->get() as $sc) {
            $sc->update(['startnumber' => $sc->score->startnumber ?? null]);
        }
        Schema::table('score_corrections', function (Blueprint $table) {
            $table->integer('startnumber')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('score_corrections', function (Blueprint $table) {
            $table->dropColumn('startnumber');
        });
    }
};
