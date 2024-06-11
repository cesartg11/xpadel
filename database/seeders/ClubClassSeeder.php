<?php

namespace Database\Seeders;

use App\Models\ClubClass;
use App\Models\ClubProfile;
use App\Models\Court;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use function App\isAvailable;

class ClubClassSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        $clubs = ClubProfile::with('hours')->get(); // Precarga los horarios
        $courts = Court::pluck('id')->all();

        foreach ($clubs as $club) {
            $classNumber = rand(3, 20); // Número aleatorio de clases por club

            foreach ($club->hours as $hour) {
                $openingTime = Carbon::createFromTimeString($hour->opening_time);
                $closingTime = Carbon::createFromTimeString($hour->closing_time);

                for ($i = 0; $i < $classNumber; $i++) {
                    $courtId = $faker->randomElement($courts);
                    $startTime = Carbon::today()->setHour($openingTime->hour)->setMinute($openingTime->minute);
                    $endTime = (clone $startTime)->addHour();

                    // Asegúrate de que la hora de inicio no exceda la hora de cierre
                    if ($endTime->greaterThan($closingTime)) {
                        continue; // Salta esta iteración si el tiempo de finalización excede el horario de cierre
                    }

                    // Verifica disponibilidad de la pista
                    if (isAvailable($courtId, $startTime->toDateTimeString(), $endTime->toDateTimeString())) {
                        ClubClass::create([
                            'club_profile_id' => $club->id,
                            'court_id' => $courtId,
                            'level' => $faker->randomElement(['Pro', 'Medio', 'Principiante']),
                            'start_time' => $startTime->toDateTimeString(),
                            'end_time' => $endTime->toDateTimeString(),
                        ]);
                    }
                }
            }
        }
    }
}
