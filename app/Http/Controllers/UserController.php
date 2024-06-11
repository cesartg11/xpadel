<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\User;
use App\Models\UserProfile;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::role('user')->with('userProfile')->get();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUserRequest $request)
    {
        try {

            DB::beginTransaction();

            if($request->password !== $request->password_confirmation){
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

            //Cancelamos la transacion
            DB::rollBack();
            return redirect()->route('clubs.index')->with('error', 'No se pudo crear el usuario. ' . $e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {

        DB::beginTransaction();

        try {

            $validatedData = $request->validated();

            $user->update([
                'email' => $validatedData['email'],
                // Actualiza la contraseña solo si se proporciona una nueva
                'password' => isset($validatedData['password']) ? bcrypt($validatedData['password']) : $user->password,
            ]);

            // Crear el perfil del usuario
            $user->userProfile()->update([
                'name' => $request->name,
                'surname' => $request->surname,
                'age' => $request->age,
                'telephone' => $request->telephone,
                'profile_photo_path' => $request->hasFile('profile_photo_path') ? $request->file('profile_photo_path')->store('users_profile_photo', 'discoImagenesUsers') : null,
            ]);

            DB::commit();

            return redirect('/users')->with('success', 'Pérfil de usuario modificado con éxito.');
        } catch (Exception $e) {

            DB::rollBack();

            return redirect('/users')->with('error', 'No se pudo modiifcar el pérfil de usuario. ' . $e->getMessage());
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
}
