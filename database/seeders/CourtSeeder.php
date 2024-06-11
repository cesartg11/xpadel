<?php

namespace Database\Seeders;

use App\Models\ClubProfile;
use App\Models\Court;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asegúrate de recuperar el Faker\Generator
        $faker = \Faker\Factory::create();

        $clubs = ClubProfile::all();

        foreach ($clubs as $club) {
            $courtsNumber = rand(3, 20);  // Número aleatorio de pistas por club

            for ($i = 0; $i < $courtsNumber; $i++) {
                Court::create([
                    'club_profile_id' => $club->id,
                    'number' => $i + 1,  // Esto asegura que las pistas estén numeradas de 1 a courtsNumber
                    'type' => $faker->randomElement(['Muro', 'Cristal']),
                ]);
            }
        }
    }
}
