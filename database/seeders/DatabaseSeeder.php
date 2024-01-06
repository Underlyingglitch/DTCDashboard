<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Location;
use App\Models\MatchDay;
use App\Models\Competition;
use App\Models\UserSetting;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::create(['name' => 'admin', 'email' => 'admin@admin.com', 'password' => bcrypt('admin'), 'active' => true, 'email_verified_at' => Carbon::now()]);
        $user->assignRole('admin');
        $competition = Competition::create(['name' => 'Test competition']);
        $location = Location::create(['name' => 'Test location', 'address' => 'Test address']);
        $matchday = MatchDay::create(['competition_id' => $competition->id, 'location_id' => $location->id, 'date' => Carbon::now()]);

        UserSetting::create(['user_id' => null, 'key' => 'current_competition', 'value' => $competition->id]);
        UserSetting::create(['user_id' => null, 'key' => 'current_match_day', 'value' => $matchday->id]);
        UserSetting::create(['user_id' => null, 'key' => 'current_wedstrijd', 'value' => 1]);
        UserSetting::create(['user_id' => null, 'key' => 'current_round', 'value' => 1]);
    }
}
