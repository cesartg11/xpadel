<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\ClubProfile;
use App\Models\Court;
use App\Http\Requests\CreateUpdateCourtRentalRequest;
use App\Http\Requests\CreateUpdateCourtRequest;
use App\Models\CourtRental;
use App\Models\UserProfile;
use Carbon\Carbon;
use function App\Helpers\isAvailable;

class CourtController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUpdateCourtRequest $request, $clubId)
    {
        $user = auth()->user();
        if (!auth()->check() || !$user->clubProfile) {
            dd("NO AUTH");
            return redirect()->route('login')->with('error', 'Necesitas iniciar sesión para realizar esta acción.');
        }

        if (!$user->hasRole('club')) {
            dd("NO CLUB");
            return redirect()->route('clubs.index')->with('error', 'No tienes permiso para realizar esta acción.');
        }

        $club = ClubProfile::findOrFail($clubId);

        if ($user->clubProfile->id !== $club->id) {
            dd("NO MISMO CLUB");
            return redirect()->route('clubs.index')->with('error', 'No tienes permiso para realizar acciones en este club.');
        }

        try {
            Court::create([
                'club_profile_id' => $user->clubProfile->id,
                'number' => $request->number,
                'type' => $request->type,
            ]);

            return redirect()->back()->with('success', 'Pista creadda con éxito.');
        } catch (Exception $e) {
            dd($e->getMessage());
            return redirect('clubs.index')->with('error', 'No se pudo crear la pista. ' . $e->getMessage());
        }
    }

    /**
     * Crea un nuevo alquiler en una pista de un club determinado
     */
    public function createRent(CreateUpdateCourtRentalRequest $request, ClubProfile $club, Court $court)
    {
        $user = auth()->user();
        if (!auth()->check() || !$user->userProfile) {
            return redirect()->route('login')->with('error', 'Necesitas iniciar sesión para realizar esta acción.');
        }

        if (!$user->hasRole('user')) {
            return redirect()->route('clubs.show', compact('club'))->with('error', 'No tienes permiso para realizar esta acción.');
        }

        $alreadyRegistered = CourtRental::where('court_id', $court->id)
            ->where('user_profile_id', $user->userProfile->id)
            ->where('start_time', $request->start_time)
            ->where('end_time', $request->end_time)
            ->exists();

        if ($alreadyRegistered) {
            return redirect()->route('clubs.show', compact('club'))->with('error', 'Ya tienes un alquiler de esta pista en durante este periodo de tiempo.');
        }

        $startDateTime = Carbon::parse($request->start_time);
        $endDateTime = Carbon::parse($request->end_time);
        $dayOfWeek = $startDateTime->format('l');

        $clubHour = $club->hours()->where('day_of_week', $dayOfWeek)->first();

        if (!$clubHour) {
            return redirect()->route('clubs.show', compact('club'))->with('error', 'No se encontró el horario para el día seleccionado.');
        }

        if ($startDateTime->lt(Carbon::parse($clubHour->opening_time)) || $endDateTime->gt(Carbon::parse($clubHour->closing_time))) {
            return redirect()->route('clubs.show', compact('club'))->with('error', "El alquiler de la pista debe estar dentro del horario de apertura del club ($clubHour->opening_time a $clubHour->closing_time).");
        }

        if (!isAvailable($court->id, $request->start_time, $request->end_time)) {
            return redirect()->route('clubs.show', compact('club'))->with('error', 'La pista no está disponible en este horario.');
        }

        try {

            DB::beginTransaction();

            CourtRental::create([
                'user_profile_id' => $user->userProfile->id,
                'court_id' => $court->id,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
            ]);

            DB::commit();

            return redirect()->route('clubs.show', compact('club'))->with('success', 'Pista alquilada con éxito');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('clubs.show',  compact('club'))->with('error', 'No se pudo alquilar la pista. ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateUpdateCourtRequest $request, ClubProfile $club, Court $court)
    {
        $user = auth()->user();

        if (!auth()->check() || !$user->clubProfile) {
            return redirect()->route('login')->with('error', 'Necesitas iniciar sesión para realizar esta acción.');
        }

        if (!$user->hasRole('club')) {
            return redirect()->route('clubs.index')->with('error', 'No tienes permiso para realizar esta acción.');
        }

        if ($user->clubProfile->id !== $club->id) {
            return redirect()->route('clubs.index')->with('error', 'No tienes permiso para realizar acciones en este club.');
        }

        try {
            $court->update([
                'number' => $request->number,
                'type' => $request->type,
            ]);

            return redirect('/clubs')->with('success', 'Pista modificada con éxito');
        } catch (Exception $e) {
            return redirect('/clubs')->with('error', 'No se pudo modificar la pista. ' . $e->getMessage());
        }
    }

    /**
     * Modifica el alquiler de una pista del club
     */
    public function updateRent(CreateUpdateCourtRentalRequest $request, ClubProfile $club, CourtRental $courtRental)
    {
        $user = auth()->user();
        if (!auth()->check() || !$user->userProfile) {
            return redirect()->route('login')->with('error', 'Necesitas iniciar sesión para realizar esta acción.');
        }

        if (!$user->hasRole('user')) {
            return redirect()->route('clubs.show', compact('club'))->with('error', 'No tienes permiso para realizar esta acción.');
        }

        $startDateTime = Carbon::parse($request->start_time);
        $endDateTime = Carbon::parse($request->end_time);
        $dayOfWeek = $startDateTime->format('l');

        $clubHour = $club->hours()->where('day_of_week', $dayOfWeek)->first();

        if (!$clubHour) {
            return redirect()->route('clubs.show', compact('club'))->with('error', 'No se encontró el horario para el día seleccionado.');
        }

        if ($startDateTime->lt(Carbon::parse($clubHour->opening_time)) || $endDateTime->gt(Carbon::parse($clubHour->closing_time))) {
            return redirect()->route('clubs.show', compact('club'))->with('error', "El alquiler debe estar dentro del horario de apertura del club ($clubHour->opening_time a $clubHour->closing_time).");
        }

        $court = $courtRental->court_id;

        if (!isAvailable($court, $request->start_time, $request->end_time)) {
            return redirect()->route('clubs.show', compact('club'))->with('error', 'La pista no está disponible en el horario seleccionado.');
        }

        try {
            DB::beginTransaction();

            $courtRental->update([
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
            ]);

            DB::commit();
            return redirect()->route('clubs.show', compact('club'))->with('success', 'Alquiler modificado con éxito');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('clubs.show', compact('club'))->with('error', 'No se pudo modificar el alquiler de la pista. ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClubProfile $club, Court $court)
    {
        $user = auth()->user();


        if (!auth()->check() || !$user->clubProfile) {
            return redirect()->route('login')->with('error', 'Necesitas iniciar sesión para realizar esta acción.');
        }

        if (!$user->hasRole('club')) {
            return redirect()->route('clubs.index')->with('error', 'No tienes permiso para realizar esta acción.');
        }

        if ($user->clubProfile->id !== $club->id) {
            return redirect()->route('clubs.index')->with('error', 'No tienes permiso para realizar acciones en este club.');
        }

        try {
            $court->rentals()->delete();
            $court->delete();
            return redirect('clubs.index')->with('success', 'Pista eliminada con éxito.');
        } catch (Exception $e) {
            return redirect('clubs.index')->with('error', 'No se pudo eliminar la pista. ' . $e->getMessage());
        }
    }

    /**
     * Elimina el alquiler de una pista del club
     */
    public function deleteRent(ClubProfile $club, CourtRental $courtRental)
    {
        $user = auth()->user();
        if (!auth()->check() || !$user->userProfile) {
            return redirect()->route('login')->with('error', 'Necesitas iniciar sesión para realizar esta acción.');
        }

        if (!$user->hasRole('user')) {
            return redirect()->route('clubs.show', compact('club'))->with('error', 'No tienes permiso para realizar esta acción.');
        }

        if ($user->userProfile->id !== $courtRental->user_profile_id) {
            return redirect()->route('clubs.show', compact('club'))->with('error', 'No tienes permiso para realizar esta acción.');
        }

        try {
            $courtRental->delete();
            return redirect()->route('clubs.show', compact('club'))->with('success', 'Alquiler eliminado con éxito');
        } catch (Exception $e) {
            return redirect()->route('clubs.show', compact('club'))->with('error', 'No se pudo eliminar el alquiler de la pista. ' . $e->getMessage());
        }
    }
}
