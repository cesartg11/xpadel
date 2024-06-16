<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\User;
use App\Models\ClubProfile;
use App\Models\ClubPhoto;
use App\Http\Requests\CreateClubRequest;
use App\Http\Requests\ImageClubRequest;
use App\Http\Requests\UpdateClubRequest;
use App\Http\Requests\UpdateInfoClubRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use function App\isAvailable;

class ClubController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clubs = ClubProfile::paginate(20);
        return view('clubs.index', compact('clubs'));
    }

    /**
     * Devuelve los datos de la tabla ClubProfile en formato JSON segun lo buscado por el usuario.
     */
    public function datosClubs(Request $request)
    {
        $query = ClubProfile::with('photos');

        if ($request->has('search')) {
            $query->whereRaw('LOWER(name) LIKE ?', ["%{$request->search}%"]);
        }

        if ($request->has('provincia') && $request->provincia != '') {
            $query->where('province', $request->provincia);
        }

        $clubs = $query->paginate(20);
        return response()->json($clubs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateClubRequest $request)
    {
        try {

            DB::beginTransaction();

            if ($request->password !== $request->password_confirmation) {
                DB::rollBack();
                return back()->withErrors(['password' => 'Las contraseñas no coinciden'])->withInput();
            }

            $user = User::create([
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            $user->assignRole('club');


            $club = $user->clubProfile();

            // Crea el perfil del club
            $club = $user->clubProfile()->create([
                'name' => $request->name,
                'telephone' => $request->telephone,
                'address' => $request->address,
                'province' => $request->province,
            ]);

            DB::commit();

            Auth::login($user);

            if (auth()->user()) {
                return redirect()->route('clubs.show', ['club' => $club]);
            } else {
                DB::rollback();
                return redirect()->route('register')->with('error', 'No se pudo crear el club.');
            }

        } catch (Exception $e) {
            dd($e->getMessage());
            DB::rollback();
            return redirect()->route('register')->with('error', 'No se pudo crear el club. ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ClubProfile $club)
    {
        $todayName = Carbon::now()->locale('es')->isoFormat('dddd');
        $todayHours = $club->hours()->where('day_of_week', $todayName)->first();
        $pistas = [];
        $userId = null;

        // Solo recupera el ID del usuario si está autenticado
        if (auth()->check() && auth()->user()->hasRole('user')) {
            $userId = auth()->user()->userProfile->id;
        }

        if ($todayHours) {
            $openingHour = Carbon::parse($todayHours->opening_time);
            $closingHour = Carbon::parse($todayHours->closing_time);

            while ($openingHour->lessThan($closingHour)) {
                $endTime = (clone $openingHour)->addHour();

                foreach ($club->courts as $court) {
                    $startFormatted = $openingHour->format('Y-m-d H:i:s');
                    $endFormatted = $endTime->format('Y-m-d H:i:s');

                    $available = isAvailable($court->id, $startFormatted, $endFormatted);
                    $userRental = false;

                    if ($userId) {
                        $userRental = $court->rental()->where('start_time', $startFormatted)
                            ->where('end_time', $endFormatted)
                            ->where('court_id', $court->id)
                            ->where('user_profile_id', $userId)
                            ->exists();
                    }

                    $pistas[$court->id][$startFormatted] = $userRental ? 'user' : ($available ? 'available' : 'occupied');
                }

                $openingHour->addHour();
            }
        }

        return view('clubs.show', compact('club', 'pistas'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user, ClubProfile $clubProfile)
    {
        return view('clubs.edit', compact('user', 'clubProfile'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClubRequest $request, User $user,)
    {

        $userReal = auth()->user();
        if (!auth()->check() || !$userReal->clubProfile) {
            return redirect()->route('login')->with('error', 'Necesitas iniciar sesión para realizar esta acción.');
        }

        if (!$userReal->hasRole('club')) {
            return redirect()->route('clubs.index')->with('error', 'No tienes permiso para realizar esta acción.');
        }

        if ($userReal->clubProfile->id !== $user->clubProfile->id) {
            return redirect()->route('clubs.index')->with('error', 'No tienes permiso para realizar acciones en este club.');
        }

        try {

            DB::beginTransaction();

            $user->update([
                'email' => $request->email,
                //'password' => bcrypt($request->password),
            ]);

            $clubProfile = $user->clubProfile();

            $clubProfile->upadte([
                'name' => $request->name,
                'telephone' => $request->telephone,
                'address' => $request->address,
                'province' => $request->province,
            ]);

            // Borrar y crear horarios y fotos
            $clubProfile->hours()->delete();

            foreach ($request->day_of_week as $index => $day) {
                $clubProfile->hours()->create([
                    'day_of_week' => $day,
                    'opening_time' => $request->opening_time[$index],
                    'closing_time' => $request->closing_time[$index],
                ]);
            }

            if ($request->hasFile('photo_url')) {
                $storedPhotoUrl = $request->file('photo_url')->store('club_photos', 'discoImagenesClubs');
                $profilePhoto = $clubProfile->photos()->where('photo_type', 'perfil')->first();

                if ($profilePhoto) {
                    Storage::disk('discoImagenesClubs')->delete($profilePhoto);
                    $profilePhoto->update(['photo_url' => $storedPhotoUrl]);
                } else {
                    $clubProfile->photos()->create([
                        'photo_url' => $storedPhotoUrl,
                        'photo_type' => 'perfil',
                    ]);
                }
            }

            DB::commit();
            return redirect('clubs.index')->with('success', 'Pérfil de club modificado con éxito');
        } catch (Exception $e) {
            DB::rollback();
            return redirect('clubs.index')->withErrors('Error al modificar el club. ' . $e->getMessage());
        }
    }

    /**
     * Elimina un club
     */
    public function delete(User $user, ClubProfile $clubProfile)
    {
        try {
            $clubProfile->delete();
            $user->delete();
        } catch (Exception $e) {
            return redirect('clubs.index')->withErrors('Error al eliminar el club. ' . $e->getMessage());
        }
    }

    /**
     * Crea o modifica la imagen de cabecera del club
     */
    public function cabecera(ImageClubRequest $request, $clubProfileId)
    {

        $user = auth()->user();
        if (!auth()->check() || !$user->clubProfile) {
            dd("no auth");
            return redirect()->route('login')->with('error', 'Necesitas iniciar sesión para realizar esta acción.');
        }

        if (!$user->hasRole('club')) {
            dd("no rol club");
            return redirect()->route('clubs.index')->with('error', 'No tienes permiso para realizar esta acción.');
        }

        $clubProfile = ClubProfile::findOrFail($clubProfileId);

        if ($user->clubProfile->id !== $clubProfile->id) {
            dd("no club roefile igual a id");
            return redirect()->route('clubs.index')->with('error', 'No tienes permiso para realizar acciones en este club.');
        }

        try {
            DB::beginTransaction();

            if ($request->hasFile('photo_url')) {
                $storedPhotoUrl = $request->file('photo_url')->store('club_photos', 'discoImagenesClubs');
                $headerPhoto = $clubProfile->photos()->where('photo_type', 'cabecera')->first();

                if ($headerPhoto) {
                    Storage::disk('discoImagenesClubs')->delete($headerPhoto->photo_url);
                    $headerPhoto->update(['photo_url' => $storedPhotoUrl]);
                } else {
                    $clubProfile->photos()->create([
                        'photo_url' => $storedPhotoUrl,
                        'photo_type' => 'cabecera',
                    ]);
                }
            }
            DB::commit();
            return redirect()->back();
        } catch (Exception $e) {
            dd($e->getMessage());
            DB::rollback();
            return redirect()->back()->withErrors('Error al subir la imagen de cabecera: ' . $e->getMessage());
        }
    }

    /**
     * Crea o modifica una imagen de tipo general
     */
    public function updateDetails(UpdateInfoClubRequest $request, $clubProfileId)
    {

        $user = auth()->user();
        if (!auth()->check() || !$user->clubProfile) {
            dd("no auth");
            return redirect()->route('login')->with('error', 'Necesitas iniciar sesión para realizar esta acción.');
        }

        if (!$user->hasRole('club')) {
            dd("Error rol no club");
            return redirect()->route('clubs.index')->with('error', 'No tienes permiso para realizar esta acción.');
        }

        $clubProfile = ClubProfile::findOrFail($clubProfileId);

        if ($user->clubProfile->id !== $clubProfile->id) {
            dd("Error club prof noigual a id");
            return redirect()->route('clubs.index')->with('error', 'No tienes permiso para realizar acciones en este club.');
        }

        try {

            DB::beginTransaction();

            if ($request->description) {
                $clubProfile->update([
                    'description' => $request->description,
                ]);
            }

            if ($request->hasFile('photo_url')) {
                $storedPhotoUrl = $request->file('photo_url')->store('club_photos', 'discoImagenesClubs');
                $generalPhoto = $clubProfile->photos()->where('photo_type', 'general')->first();

                if ($generalPhoto) {
                    Storage::disk('discoImagenesClubs')->delete($generalPhoto->photo_url);
                    $generalPhoto->update(['photo_url' => $storedPhotoUrl]);
                } else {
                    $clubProfile->photos()->create([
                        'photo_url' => $storedPhotoUrl,
                        'photo_type' => 'general',
                    ]);
                }
            }
            DB::commit();
            return redirect()->back();
        } catch (Exception $e) {
            dd($e->getMessage());
            DB::rollback();
            return redirect()->back()->withErrors('Error al subir la imagen: ' . $e->getMessage());
        }
    }
}
