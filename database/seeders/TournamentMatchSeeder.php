<?php

namespace Database\Seeders;

use App\Models\Tournament;
use App\Models\TournamentMatch;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use function App\isAvailable;

class TournamentMatchSeeder extends Seeder
{

    public function run(): void
    {
        $tournaments = Tournament::with('registrations')->get();

        foreach ($tournaments as $tournament) {
            $registrations = $tournament->registrations;
            if ($registrations->isEmpty()) continue;

            $numRounds = ceil(log($registrations->count(), 2));

            $currentPairs = $registrations->pluck('id');

            for ($round = 1; $round <= $numRounds; $round++) {
                $nextRoundPairs = [];

                for ($i = 0; $i < $currentPairs->count(); $i += 2) {
                    if (!isset($currentPairs[$i + 1])) continue;

                    $result = $this->simulateMatch();
                    $winningPair = $result['result'] === 'pair1' ? $currentPairs[$i] : $currentPairs[$i + 1];
                    $nextRoundPairs[] = $winningPair;

                    $scheduledStartTime = Carbon::now()->addDays($round * 2); // Asume cada ronda cada dos días
                    $endTime = $scheduledStartTime->copy()->addHours(2); // Duración del partido

                    $courtId = $this->findAvailableCourt($tournament, $scheduledStartTime, $endTime);
                    if (!$courtId) {
                        continue;
                    }

                    TournamentMatch::create([
                        'tournament_id' => $tournament->id,
                        'round_number' => $round,
                        'court_id' => $courtId,
                        'pair1_id' => $currentPairs[$i],
                        'pair2_id' => $currentPairs[$i + 1],
                        'set1' => $result['set1'],
                        'set2' => $result['set2'],
                        'set3' => $result['set3'] ?? null,
                        'result' => $result['result'],
                        'scheduled_start_time' => $scheduledStartTime,
                    ]);
                }

                // Preparar las parejas para la siguiente ronda
                $currentPairs = collect($nextRoundPairs);
                if ($currentPairs->count() <= 1) break; // Terminar si queda una pareja o ninguna
            }
        }
    }

    private function findAvailableCourt(Tournament $tournament, Carbon $startTime, Carbon $endTime)
    {
        $club = $tournament->clubProfile;
        if (!$club) return null;

        foreach ($club->courts as $court) {
            if (isAvailable($court->id, $startTime->toDateTimeString(), $endTime->toDateTimeString())) {
                return $court->id;
            }
        }

        return null; // Retorna null si no hay pistas disponibles
    }


    // Simula el resultado del partido (puedes implementar tu lógica para determinar el ganador)
    private function simulateMatch()
    {
        // Simulamos los resultados de los sets
        $result = [
            'set1' => '',
            'set2' => '',
            'set3' => null,
            'result' => ''
        ];

        $pair1Wins = 0;
        $pair2Wins = 0;

        for ($i = 1; $i <= 2; $i++) {
            // Simulamos el marcador de dos sets
            $pair1Score = rand(0, 7); // Puntaje aleatorio para la pareja local

            if ($pair1Score == 6) {
                $pair2Score = rand(0, 5);
                $pair1Wins++;
            } else if ($pair1Score < 6) {
                $pair2Score = 6;
                $pair2Wins++;
            } else if ($pair1Score == 7) {
                $pair2Score = 6;
                $pair1Wins++;
            }

            // Agregamos el resultado del set al array
            $result['set' . $i] = "$pair1Score - $pair2Score";
        }

        //Si hay empate creamos el tercer set
        if ($pair1Wins ==  $pair2Wins) {
            $pair1Score = rand(0, 7);

            if ($pair1Score == 6) {
                $pair2Score = rand(0, 5);
                $pair1Wins++;
            } else if ($pair1Score < 6) {
                $pair2Score = 6;
                $pair2Wins++;
            } else if ($pair1Score == 7) {
                $pair2Score = 6;
                $pair1Wins++;
            }

            // Agregamos el resultado del set al array
            $result['set3'] = "$pair1Score - $pair2Score";
        }

        // Determinamos al ganador del partido
        $result['result'] = $pair1Wins > $pair2Wins ? 'pair1' : 'pair2';

        return $result;
    }
}
