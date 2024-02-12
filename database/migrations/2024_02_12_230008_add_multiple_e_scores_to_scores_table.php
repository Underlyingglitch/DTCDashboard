<?php

use Illuminate\Support\Facades\DB;
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
        Schema::table('scores', function (Blueprint $table) {
            $table->float('e1', 5, 3)->after('e');
            $table->float('e2', 5, 3)->nullable()->after('e1');
            $table->float('e3', 5, 3)->nullable()->after('e2');
        });
        // Move the data from the e column to the e1 column
        DB::table('scores')->update(['e1' => DB::raw('e')]);
        // Drop the e column
        Schema::table('scores', function (Blueprint $table) {
            $table->dropColumn('e');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scores', function (Blueprint $table) {
            //
        });
    }
};
