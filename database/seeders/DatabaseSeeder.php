<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
use App\Models\Club;
use App\Models\User;
use App\Models\Niveau;
use App\Models\Setting;
use App\Models\Trainer;
use App\Models\Location;
use App\Models\MatchDay;
use App\Models\Wedstrijd;
use App\Models\Competition;
use App\Models\UserSetting;
use Illuminate\Database\Seeder;
use Database\Seeders\NiveauSeeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'trainer']);
        Role::create(['name' => 'dtc']);
        Role::create(['name' => 'jury']);
        Role::create(['name' => 'user']);
        $admin_user = User::create(['name' => 'admin', 'email' => 'admin@test.dev', 'password' => bcrypt('admin'), 'active' => true, 'email_verified_at' => Carbon::now()]);
        $admin_user->assignRole('admin');

        NiveauSeeder::run();
        $competition = Competition::create(['name' => 'Test competition']);
        $location = Location::create(['name' => 'Test location', 'address' => 'Test address']);
        $matchday = MatchDay::create(['competition_id' => $competition->id, 'location_id' => $location->id, 'date' => Carbon::now()]);
        $wedstrijd = Wedstrijd::create(['match_day_id' => $matchday->id, 'index' => 1]);
        $wedstrijd->niveaus()->attach(1);

        Setting::setValue('current_competition', $competition->id);
        Setting::setValue('current_match_day', $matchday->id);
        Setting::setValue('current_wedstrijd', 1);
        Setting::setValue('current_round', 1);

        $club = Club::create(['id' => 1, 'name' => 'Test club', 'email' => 'club@test.dev', 'place' => 'test', 'district' => 'test']);
        $trainer = Trainer::create(['name' => 'Test trainer', 'email' => 'trainer@test.dev', 'phone' => '0123456789', 'club_id' => 1]);
        $trainer->competitions()->attach($competition->id);

        $trainer_user = User::create(['name' => 'trainer', 'email' => 'trainer@test.dev', 'password' => bcrypt('trainer'), 'active' => true, 'email_verified_at' => Carbon::now()]);
        $trainer_user->assignRole('trainer');
    }
}
