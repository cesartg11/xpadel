<?php

namespace App\Http\Controllers;

use App\Models\ClubProfile;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\User;
use App\Models\UserProfile;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUserRequest $request)
    {
        try {
            DB::beginTransaction();

            if ($request->password !== $request->password_confirmation) {
                DB::rollBack();
                return back()->withErrors(['password' => 'Las contraseñas no coinciden'])->withInput();
            }

            $user = User::create([
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            $user->assignRole('user');

            // Crear el perfil del usuario
            $user->userProfile()->create([
                'name' => $request->name,
                'surname' => $request->surname,
                'age' => $request->age,
                'telephone' => $request->telephone,
            ]);

            DB::commit();

            Auth::login($user);

            return redirect()->route('clubs.index');

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('clubs.index')->with('error', 'No se pudo crear el usuario. ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        if (auth()->check()) {
            if ($user->hasRole('user')) {
                $profile = $user->userProfile;
            } else if ($user->hasRole('club')) {
                $profile = $user->clubProfile;
            }

            return view('users.edit', compact('user', 'profile'));
        } else {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {

        $userReal = auth()->user();
        if (!auth()->check() || !$userReal->userProfile) {
            return redirect()->route('login')->with('error', 'Necesitas iniciar sesión para realizar esta acción.');
        }

        if (!$user->hasRole('user')) {
            return redirect()->route('clubs.index', compact('club'))->with('error', 'No tienes permiso para realizar esta acción.');
        }

        $userProfile = UserProfile::where('user_id', $user->id)->first();

        if (!$userProfile) {
            return redirect()->route('clubs.index')->with('error', 'Perfil de club no encontrado.');
        }

        if ($userReal->userProfile->id !== $userProfile->id) {
            return redirect()->route('clubs.index')->with('error', 'No tienes permiso para realizar acciones en este club.');
        }

        DB::beginTransaction();

        try {

            $validatedData = $request->validated();

            $user->update([
                'email' => $validatedData['email'],
                //'password' => isset($validatedData['password']) ? bcrypt($validatedData['password']) : $user->password,
            ]);

            if ($request->hasFile('profile_photo_path')) {
                // Borrar la imagen antigua si existe
                if ($userProfile->profile_photo_path) {
                    Storage::disk('discoImagenesUsers')->delete($userProfile->profile_photo_path);
                }
                // Guardar la nueva imagen y obtener la ruta
                $path = $request->file('profile_photo_path')->store('users_profile_photo', 'discoImagenesUsers');
            } else {
                // Mantener la ruta antigua si no hay archivo nuevo
                $path = $userProfile->profile_photo_path;
            }

            // Actualizar el perfil del usuario
            $userProfile->update([
                'name' => $request->name,
                'surname' => $request->surname,
                'age' => $request->age,
                'telephone' => $request->telephone,
                'profile_photo_path' => $path,  // Usar la nueva o antigua ruta de la imagen
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Pérfil de usuario modificado con éxito.');
        } catch (Exception $e) {

            DB::rollBack();

            return redirect()->back()->with('error', 'No se pudo modiifcar el pérfil de usuario. ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->userProfile()->delete();  //Eliminar el perfil
            $user->delete();  //Eliminar el usuario
            return redirect('/users')->with('success', 'Usuario eliminado con éxito.');
        } catch (Exception $e) {
            return redirect('/users')->with('error', 'No se pudo eliminar el usuario. ' . $e->getMessage());
        }
    }

    /**
     * Muestra las actividades del usuario
     */
    public function misActividades()
    {
        if (auth()->check()) {
            $user = auth()->user(); // Obtener el usuario actual
            $userProfile = $user->userProfile;

            $alquileres = $userProfile->alquileres()->with('court')->get();
            $clases = $userProfile->clases()->with('clubClass')->get();
            $torneos = $userProfile->torneos()->with('tournament')->get();

            // Pasar datos a la vista
            return view('users.misActividades', compact('alquileres', 'clases', 'torneos'));
        } else {
            return redirect()->route('login')->with('error', 'Necesitas iniciar sesión para realizar esta acción.');
        }
    }
}
