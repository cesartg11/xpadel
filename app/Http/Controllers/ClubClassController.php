<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUpdateClassRequest;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\ClubClass;
use App\Models\ClubClassRegistration;
use App\Models\ClubProfile;
use App\Models\UserProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;

use function App\Helpers\isAvailable;

class ClubClassController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUpdateClassRequest $request, $clubId)
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

        $startDateTime = Carbon::parse($request->start_time);
        $endDateTime = Carbon::parse($request->end_time);
        $dayOfWeek = $startDateTime->format('l');

        $clubHour = $club->hours()->where('day_of_week', $dayOfWeek)->first();

        if (!$clubHour) {
            return redirect()->route('clubs.index')->with('error', 'No se encontró el horario para el día seleccionado.');
        }

        if ($startDateTime->lt(Carbon::parse($clubHour->opening_time)) || $endDateTime->gt(Carbon::parse($clubHour->closing_time))) {
            return redirect()->route('clubs.index')->with('error', "La clase debe estar dentro del horario de apertura del club ($clubHour->opening_time a $clubHour->closing_time).");
        }

        try {
            ClubClass::create([
                'club_profile_id' => $user->clubProfile->id,
                'court_id' => $request->court_id,
                'level' => $request->level,
                'start_time' => $startDateTime,
                'end_time' => $endDateTime,
            ]);

            return redirect()->back()->with('success', 'Clase creada con éxito.');
        } catch (Exception $e) {
            return redirect()->route('clubs.show', compact('club'))->with('error', 'Error al crear la clase: ' . $e->getMessage());
        }
    }

    /**
     * Registro de un usuario en una clase
     */
    public function createRegistration($clubId, $classId)
    {
        $user = auth()->user();
        if (!auth()->check() || !$user->userProfile) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para registrarte en la clase.');
        }

        $club = ClubProfile::findOrFail($clubId);

        if (!$user->hasRole('user')) {
            return redirect()->route('clubs.show', compact('club'))->with('error', 'No tienes permiso para realizar esta acción.');
        }

        $alreadyRegistered = ClubClassRegistration::where('class_id', $classId)
            ->where('user_profile_id', $user->userProfile->id)
            ->exists();

        if ($alreadyRegistered) {
            DB::rollback();
            return redirect()->route('clubs.show', compact('club'))->with('error', 'Ya estás registrado en esta clase.');
        }

        try {
            DB::beginTransaction();

            ClubClassRegistration::create([
                'class_id' => $classId,
                'user_profile_id' => $user->userProfile->id,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Te has apuntado a la clase con éxito');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('clubs.show', compact('club'))->with('error', 'Error al hacer el registro en la clase: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateUpdateClassRequest $request, $clubId, $classId)
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

        $startDateTime = Carbon::parse($request->start_time);
        $endDateTime = Carbon::parse($request->end_time);
        $dayOfWeek = $startDateTime->format('l'); // Obtiene el día de la semana en inglés

        // Busca el horario del club para ese día
        $clubHour = $club->hours()->where('day_of_week', $dayOfWeek)->first();

        if (!$clubHour) {
            return redirect()->route('classes.index')->with('error', 'No se encontró el horario para el día seleccionado.');
        }

        // Comprueba que la hora de inicio y fin esté dentro del horario de apertura y cierre
        if ($startDateTime->lt(Carbon::parse($clubHour->opening_time)) || $endDateTime->gt(Carbon::parse($clubHour->closing_time))) {
            return redirect()->route('classes.index')->with('error', "La clase debe estar dentro del horario de apertura del club ($clubHour->opening_time a $clubHour->closing_time).");
        }

        $class = ClubClass::findOrFail($classId);

        try {
            $class->update([
                'club_profile_id' => $user->clubProfile->id,
                'court_id' => $request->court_id,
                'level' => $request->level,
                'start_time' => $startDateTime,
                'end_time' => $endDateTime,
            ]);

            return redirect()->back()->with('success', 'Clase actualizada con éxito.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar la clase: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($clubId, $classId)
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
            $class = ClubClass::findOrFail($classId);
            $class->registrations()->delete(); //Borramos los registros de cada clase
            $class->delete(); //Borramos la clase
            return redirect()->back()->with('success', 'Clase eliminada con éxito.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar la clase: ' . $e->getMessage());
        }
    }

    /**
     * Elimina el registro del usuario de una determinada clase
     */
    public function destroyRegistraion(ClubProfile $club, ClubClassRegistration $registration)
    {
        $user = auth()->user();
        if (!auth()->check() || !$user->userProfile) {
            return redirect()->route('login')->with('error', 'Necesitas iniciar sesión para realizar esta acción.');
        }

        if ($user->hasRole('user')) {
            return redirect()->route('clubs.show', compact('club'))->with('error', 'No tienes permiso para realizar esta acción.');
        }

        if ($user->userProfile->id !== $registration->user_profile_id) {
            return redirect()->route('clubs.show', compact('club'))->with('error', 'No tienes permiso para realizar esta acción.');
        }

        try {
            $registration->delete();
            return redirect()->route('clubs.show', compact('club'))->with('success', 'Clase eliminada con éxito.');
        } catch (Exception $e) {
            return redirect()->route('clubs.show', compact('club'))->with('error', 'Error al eliminar la clase: ' . $e->getMessage());
        }
    }
}
