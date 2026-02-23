<?php

use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Setting::getDBValue('current_competition') == null) {
            Setting::setValue('current_competition', 0, true);
        }
        if (Setting::getDBValue('current_match_day') == null) {
            Setting::setValue('current_match_day', 0, true);
        }
        if (Setting::getDBValue('current_wedstrijd') == null) {
            Setting::setValue('current_wedstrijd', 0, true);
        }
        if (Setting::getDBValue('current_round') == null) {
            Setting::setValue('current_round', 0, true);
        }
        if (Setting::getDBValue('oefenstof_last_updated') == null) {
            Setting::setValue('oefenstof_last_updated', Carbon::parse('2021-01-01 00:00:00'), true);
        }
        if (Setting::getDBValue('sync_enabled') == null) {
            Setting::setValue('sync_enabled', false, true);
        }
        if (Setting::getDBValue('db_write_enabled') == null) {
            Setting::setValue('db_write_enabled', true, true);
        }
        if (Setting::getDBValue('dg_resources_last_update') == null) {
            Setting::setValue('dg_resources_last_update', Carbon::parse('2024-02-11 06:00:08'), true);
        }
        if (Setting::getDBValue('score_correction_enabled') == null) {
            Setting::setValue('score_correction_enabled', true, true);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
