<?php

namespace Database\Seeders;

use App\Models\Niveau;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class NiveauSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public static function run(): void
    {
        Niveau::create(['name' => 'Instap', 'supplement' => 'A']);
        Niveau::create(['name' => 'Instap', 'supplement' => 'B']);
        Niveau::create(['name' => 'Pupil', 'supplement' => 'B']);
        Niveau::create(['name' => 'Pupil', 'supplement' => 'C']);
        Niveau::create(['name' => 'Jeugd', 'supplement' => 'B']);
        Niveau::create(['name' => 'Jeugd', 'supplement' => 'C']);
        Niveau::create(['name' => 'Jeugd', 'supplement' => 'D']);
        Niveau::create(['name' => 'Junior 1', 'supplement' => 'C']);
        Niveau::create(['name' => 'Junior 2', 'supplement' => 'C']);
        Niveau::create(['name' => 'Senior', 'supplement' => 'C']);
    }
}
