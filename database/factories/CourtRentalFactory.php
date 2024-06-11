<?php

namespace Database\Factories;

use App\Models\ClubProfile;
use App\Models\Court;
use App\Models\CourtRental;
use App\Models\UserProfile;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use function App\isAvailable;

class CourtRentalFactory extends Factory
{
    protected $model = CourtRental::class;

    public function definition(): array
    {
        $users = UserProfile::pluck('id');
        $clubs = ClubProfile::with('hours', 'courts')->get();

        $club = $this->faker->randomElement($clubs);
        $courts = $club->courts->pluck('id');

        $user = $this->faker->randomElement($users);
        $court = $this->faker->randomElement($courts);

        $dayOfWeek = $this->faker->randomElement(['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo']);
        $clubHour = $club->hours->where('day_of_week', $dayOfWeek)->first();

        $openingTime = Carbon::parse($clubHour->opening_time);
        $closingTime = Carbon::parse($clubHour->closing_time);

        $startTime = Carbon::instance($this->faker->dateTimeBetween($openingTime, $closingTime->subHour()));
        $endTime = (clone $startTime)->addHour();  // Asegura que el tiempo final sea una hora después del inicio

        // Revisa si la cancha está disponible en las horas generadas
        while (!isAvailable($court, $startTime->format('Y-m-d H:i:s'), $endTime->format('Y-m-d H:i:s'))) {
            $startTime = Carbon::instance($this->faker->dateTimeBetween($openingTime, $closingTime->subHour()));
            $endTime = (clone $startTime)->addHour();
        }

        return [
            'user_profile_id' => $user,
            'court_id' => $court,
            'start_time' => $startTime,
            'end_time' => $endTime,
        ];
    }
}
