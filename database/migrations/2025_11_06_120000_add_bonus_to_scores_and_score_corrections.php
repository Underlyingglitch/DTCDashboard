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
        Schema::table('scores', function (Blueprint $table) {
            if (!Schema::hasColumn('scores', 'b')) {
                $table->float('b', 5, 3)->default(0)->after('n')->nullable();
            }
        });

        Schema::table('score_corrections', function (Blueprint $table) {
            if (!Schema::hasColumn('score_corrections', 'b')) {
                $table->float('b', 5, 3)->default(0)->after('n')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scores', function (Blueprint $table) {
            if (Schema::hasColumn('scores', 'b')) {
                $table->dropColumn('b');
            }
        });

        Schema::table('score_corrections', function (Blueprint $table) {
            if (Schema::hasColumn('score_corrections', 'b')) {
                $table->dropColumn('b');
            }
        });
    }
};
