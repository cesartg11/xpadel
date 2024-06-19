<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Tournament;
use App\Models\ClubProfile;
use App\Http\Requests\RegisterTournamentRequest;
use App\Http\Requests\CreateUpdateTournamentRequest;
use App\Models\TournamentMatch;
use App\Models\TournamentRegistration;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use function App\isAvailable;

class TournamentController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tournaments = Tournament::all();
        return view('tournaments.index', compact('tournaments'));
    }

    public function datosTorneos(Request $request)
    {
        $query = $request->input('search');
        $tournaments = Tournament::where('name', 'LIKE', '%' . $query . '%')->get();

        return response()->json($tournaments); // Devuelve los resultados como JSON
    }


    /**
     * Crea un nuevo alquiler en una pista de un club determinado
     */
    public function store(CreateUpdateTournamentRequest $request, $clubId)
    {
        $user = auth()->user();
        if (!auth()->check() || !$user->clubProfile) {
            return redirect()->route('login')->with('error', 'Necesitas iniciar sesión para realizar esta acción.');
        }

        if (!$user->hasRole('club')) {
            return redirect()->route('clubs.index')->with('error', 'No tienes permiso para realizar esta acción.');
        }

        $club = ClubProfile::findOrFail($clubId);

        if ($user->clubProfile->id !== $club->id) {
            return redirect()->route('clubs.index')->with('error', 'No tienes permiso para realizar acciones en este club.');
        }

        try {
            Tournament::create([
                'club_profile_id' => $user->clubProfile->id,
                'name' => $request->name,
                'status' => $request->status,
                'description' => $request->description,
                'photo_url' => $request->hasFile('photo_url') ? $request->file('photo_url')->store('tournament_photos', 'discoImagenesTournaments') : null,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);

            return redirect()->back()->with('success', 'Torneo creado con éxito.');
        } catch (Exception $e) {
            return redirect()->route('clubs.show', compact('club'))->with('error', 'No se pudo crear el torneo. ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tournament $tournament)
    {
        return view('tournaments.show', compact('tournament'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateUpdateTournamentRequest $request, $clubId, $tournamentId)
    {
        $user = auth()->user();
        if (!auth()->check() || !$user->clubProfile) {
            return redirect()->route('login')->with('error', 'Necesitas iniciar sesión para realizar esta acción.');
        }

        if (!$user->hasRole('club')) {
            return redirect()->route('clubs.index')->with('error', 'No tienes permiso para realizar esta acción.');
        }

        $club = ClubProfile::findOrFail($clubId);

        if ($user->clubProfile->id !== $club->id) {
            return redirect()->route('clubs.index')->with('error', 'No tienes permiso para realizar acciones en este club.');
        }

        try {
            $tournament = Tournament::findOrFail($tournamentId);

            $tournament->update([
                'name' => $request->name,
                'status' => $request->status,
                'description' => $request->description,
                'photo_url' => $request->hasFile('photo_url') ? $request->file('photo_url')->store('tournament_photos', 'discoImagenesTournaments') : null,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);

            return redirect()->back()->with('success', 'Torneo modificado con éxito.');
        } catch (Exception $e) {
            return redirect()->route('clubs.show', compact('club'))->with('error', 'No se pudo modificar el torneo. ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($clubId, $tournamentId)
    {
        $user = auth()->user();
        if (!auth()->check() || !$user->clubProfile) {
            return redirect()->route('login')->with('error', 'Necesitas iniciar sesión para realizar esta acción.');
        }

        if (!$user->hasRole('club')) {
            return redirect()->route('clubs.index')->with('error', 'No tienes permiso para realizar esta acción.');
        }

        $club = ClubProfile::findOrFail($clubId);

        if ($user->clubProfile->id !== $club->id) {
            return redirect()->route('clubs.index')->with('error', 'No tienes permiso para realizar acciones en este club.');
        }

        try {
            $tournament = Tournament::findOrFail($tournamentId);

            $tournament->matches()->delete();
            $tournament->registrations()->delete();
            $tournament->delete();

            return redirect()->back()->with('success', 'Torneo eliminado con éxito.');
        } catch (Exception $e) {
            return redirect()->route('clubs.show', compact('club'))->with('error', 'No se pudo eliminar el torneo. ' . $e->getMessage());
        }
    }


    /**
     * Registra una pareja en un torneo
     */
    public function registrar(RegisterTournamentRequest $request, ClubProfile $club, Tournament $tournament)
    {

        $user = auth()->user();
        if (!auth()->check() || !$user->userProfile) {
            return redirect()->route('login')->with('error', 'Necesitas iniciar sesión para realizar esta acción.');
        }

        if ($user->hasRole('user')) {
            return redirect()->route('clubs.show', compact('club'))->with('error', 'No tienes permiso para realizar esta acción.');
        }

        $player1 = User::where('email', $request->emailPlayer1)->firstOrFail();
        $player2 = User::where('email', $request->emailPlayer2)->firstOrFail();

        if ($player1->id === $player2->id) {
            return redirect()->route('club.show', $tournament)->with('error', 'No puedes registrar al mismo jugador como ambos participantes.');
        }

        $existingRegistration = $tournament->registrations()
            ->where(function ($query) use ($player1, $player2) {
                $query->where('player1_id', $player1->id)->where('player2_id', $player2->id);
            })->orWhere(function ($query) use ($player1, $player2) {
                $query->where('player1_id', $player2->id)->where('player2_id', $player1->id);
            })->exists();

        if ($existingRegistration) {
            return redirect()->route('club.show', $tournament)->with('error', 'Esta pareja ya está registrada en este torneo.');
        }

        $existingPlayer1Registration = $tournament->registrations()
            ->where(function ($query) use ($player1) {
                $query->where('player1_id', $player1->id)->orWhere('player2_id', $player1->id);
            })->exists();

        $existingPlayer2Registration = $tournament->registrations()
            ->where(function ($query) use ($player2) {
                $query->where('player1_id', $player2->id)->orWhere('player2_id', $player2->id);
            })->exists();

        if ($existingPlayer1Registration || $existingPlayer2Registration) {
            return redirect()->route('club.show', $tournament)->with('error', 'Uno de los jugadores ya está registrado en otra pareja.');
        }

        try {
            DB::beginTransaction();

            $tournament->registrations()->create([
                'player1_id' => $player1->userProfile->id,
                'player2_id' => $player2->userProfile->id,
            ]);

            DB::commit();
            return redirect()->route('tournaments.show', $tournament)->with('success', 'Pareja registrada con éxito en el torneo.');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('tournaments.show', $tournament)->with('error', 'Error al registrar los datos: ' . $e->getMessage());
        }
    }

    /**
     * Comprueba si la pista está libre, si es así devuelve el id de la pista
     */
    private function assignCourt(Tournament $tournament, Carbon $startTime, Carbon $endTime)
    {
        $club = $tournament->clubProfile()->first();

        if ($club) {
            $courts = $club->courts;

            foreach ($courts as $court) {
                if (isAvailable($court->id, $startTime->toDateTimeString(), $endTime->toDateTimeString())) {
                    return $court->id;
                }
            }
        }

        return null; // Retorna null si no hay pistas disponibles o no se encontró el club
    }

    /**
     * Genera partidos aleatorios
     */
    public function matchGenerator(Tournament $tournament)
    {
        $now = now();

        if ($tournament->start_date->diffInHours($now) > 48) {
            return redirect()->route('tournaments.show', $tournament)
                ->with('warning', 'Los partidos sólo pueden generarse cuando quedan 48 horas o menos para el inicio del torneo.');
        }

        $registrations = $tournament->registrations()->get();

        if ($registrations->count() % 2 != 0) {
            $bye = $registrations->random();
            $registrations = $registrations->where('id', '!=', $bye->id);
        }

        $shuffledRegistrations = $registrations->shuffle();

        foreach ($shuffledRegistrations->chunk(2) as $pair) {
            if (count($pair) < 2)
                continue;

            $pair1 = $pair[0];
            $pair2 = $pair[1];

            $startTime = $tournament->start_date->addHours(2);
            $endTime = $startTime->copy()->addHours(1)->addMinutes(15);

            $courtId = $this->assignCourt($tournament, $startTime, $endTime);

            if ($courtId) {
                TournamentMatch::create([
                    'tournament_id' => $tournament->id,
                    'round' => 1,
                    'pair1_id' => $pair1->id,
                    'pair2_id' => $pair2->id,
                    'court_id' => $courtId,
                    'scheduled_start_time' => $startTime
                ]);
            }
        }

        return redirect()->route('tournaments.show', $tournament)->with('success', 'Partidos generados con éxito.');
    }

    public function destroyRegister(Tournament $tournament, TournamentRegistration $tournamentRegistration)
    {
        try {
            $tournamentRegistration->delete();
        } catch (Exception $e) {
            return redirect()->route('tournament.show', compact('tournament'))->with('error', 'Error al registrar los datos: ' . $e->getMessage());
        }
    }
}
