<?php

use App\Models\Setting;
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
        Setting::setValue('score_correction_enabled', true);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
