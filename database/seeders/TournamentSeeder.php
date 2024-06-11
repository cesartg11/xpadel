<?php

namespace Database\Seeders;

use App\Models\ClubProfile;
use App\Models\Tournament;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TournamentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        $clubs = ClubProfile::inRandomOrder()->limit(10)->get();

        foreach ($clubs as $club) {
            $startDate = $faker->dateTimeBetween('+1 week', '+1 month');

            $endDate = (clone $startDate)->modify('+1 to 2 days');

            Tournament::create([
                'club_profile_id' => $club->id,
                'name' => $faker->words(2, true),
                'status' => 'Cerrado',
                'description' => $faker->paragraph(),
                'photo_url' => null,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);
        }
    }
}
