<?php

namespace Database\Seeders;

use App\Models\ClubHour;
use App\Models\ClubProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClubHourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $clubs = ClubProfile::all();

        foreach ($clubs as $club) {
            $days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
            $openTime = '08:00:00';
            $closeTime = '22:00:00';

            foreach ($days as $day) {
                ClubHour::create([
                    'club_profile_id' => $club->id,
                    'day_of_week' => $day,
                    'opening_time' => $openTime,
                    'closing_time' => $closeTime,
                ]);
            }
        }
    }
}
