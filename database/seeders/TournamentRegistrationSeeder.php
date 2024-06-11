<?php

namespace Database\Seeders;

use App\Models\Tournament;
use App\Models\TournamentRegistration;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;

class TournamentRegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        $tournaments = Tournament::all();

        foreach ($tournaments as $tournament) {

            $registeredPairs = [];

            while (count($registeredPairs) < 16) {
                $userIds = $faker->randomElements(UserProfile::pluck('id')->toArray(), 2, false);
                sort($userIds);

                // Crear un string único para la pareja actual
                $pairString = implode('-', $userIds);

                if (!in_array($pairString, $registeredPairs)) {
                    $existingRegistration = TournamentRegistration::where('tournament_id', $tournament->id)
                        ->where(function ($query) use ($userIds) {
                            $query->where('player1_id', $userIds[0])
                                  ->where('player2_id', $userIds[1]);
                        })
                        ->exists();

                    if (!$existingRegistration) {
                        TournamentRegistration::create([
                            'tournament_id' => $tournament->id,
                            'player1_id' => $userIds[0],
                            'player2_id' => $userIds[1],
                        ]);
                        $registeredPairs[] = $pairString; //Añadir la pareja a la lista de registrados
                    }
                }

                //Evitar un bucle infinito en caso de que no haya suficientes usuarios disponibles
                if (count($registeredPairs) + TournamentRegistration::where('tournament_id', $tournament->id)->count() >= UserProfile::count()) {
                    break;
                }
            }
        }
    }
}
