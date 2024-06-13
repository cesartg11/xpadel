<?php

namespace App;

use App\Models\ClubClass;
use App\Models\CourtRental;
use App\Models\TournamentMatch;
use Carbon\Carbon;

error_log('Helpers file loaded');

if (!defined('HELPERS_LOADED')) {
    define('HELPERS_LOADED', true);
    // Función para verificar la disponibilidad de una pista
    function isAvailable(int $courtId, string $startTime, string $endTime): bool
    {
        $startTime = Carbon::parse($startTime);
        $endTime = Carbon::parse($endTime);

        // Verifica conflictos con alquileres de la pista
        $courtRentalConflict = CourtRental::where('court_id', $courtId)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    $q->where('start_time', '<', $endTime)
                        ->where('end_time', '>', $startTime);
                })
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<', $startTime)
                            ->where('end_time', '>', $endTime);
                    });
            })
            ->exists();

        // Verifica conflictos con clases asignadas a la pista
        $clubClassConflict = ClubClass::where('court_id', $courtId)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    $q->where('start_time', '<', $endTime)
                        ->where('end_time', '>', $startTime);
                })
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<', $startTime)
                            ->where('end_time', '>', $endTime);
                    });
            })
            ->exists();

        $tournamentConflict = TournamentMatch::where('court_id', $courtId)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('scheduled_start_time', [$startTime, $endTime]);
            })
            ->exists();

        return !($courtRentalConflict || $clubClassConflict ||  $tournamentConflict);
    }
}
