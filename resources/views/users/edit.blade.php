@extends('layouts.plantilla')

@section('titulo', 'Editar Perfil de Usuario')

@section('contenido')
    <div class="mt-32 w-full max-w-xl mx-auto rounded-lg bg-white p-5">
        @role('user')
            @if (auth()->user()->userProfile && auth()->user()->userProfile->id == $profile->id)
                <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="flex items-center justify-center">
                        <div class="flex items-center justify-center relative" style="height: 100px;">
                            <div
                                class="relative w-24 h-24 rounded-full overflow-hidden border-2 border-black cursor-pointer flex justify-center items-center">
                                @if (auth()->user()->userProfile->profile_photo_path)
                                    <img src="{{ asset('assets/users/' . auth()->user()->userProfile->profile_photo_path) }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <svg class="h-full w-20 text-black" width="24" height="24" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" />
                                        <circle cx="12" cy="7" r="4" />
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                    </svg>
                                @endif
                            </div>
                            <div id="editButton" class="absolute right-0 top-0 cursor-pointer bg-white border rounded-full p-1 hover:bg-lime-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </div>
                            <input type="file" id="fileInput" name="profile_photo_path" style="display: none;">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Correo Electrónico:</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </div>

                    <div class="mb-6">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Nombre:</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $profile->name) }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </div>

                    <div class="mb-6">
                        <label for="surname" class="block mb-2 text-sm font-medium text-gray-900">Apellidos:</label>
                        <input type="text" name="surname" id="surname" value="{{ old('surname', $profile->surname) }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </div>

                    <div class="mb-6">
                        <label for="age" class="block mb-2 text-sm font-medium text-gray-900">Edad:</label>
                        <input type="number" name="age" id="age" value="{{ old('age', $profile->age) }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </div>

                    <div class="mb-6">
                        <label for="telephone" class="block mb-2 text-sm font-medium text-gray-900">Teléfono:</label>
                        <input type="text" name="telephone" id="telephone"
                            value="{{ old('telephone', $profile->telephone) }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </div>

                    <div class="mt-4 flex items-center justify-center">
                        <button type="submit" class="px-4 py-2 bg-lime-300 text-black rounded-md hover:bg-lime-400">
                            Guardar cambios</button>
                    </div>
                </form>
            @endif
        @endrole
        @role('club')
            @if (auth()->user()->clubProfile && auth()->user()->clubProfile->id == $profile->id)
                <form action="{{ route('clubs.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="flex items-center justify-center">
                        @php
                            $profilePhoto = auth()->user()->clubProfile->photos->where('photo_type', 'perfil')->first();
                            $photoUrl = $profilePhoto ? 'assets/clubs/club_photos' . $profilePhoto->photo_url : null;
                        @endphp
                        @if ($photoUrl)
                            <img src="{{ $photoUrl }}" alt="Perfil del Club" class="rounded-full h-24 w-24 object-cover">
                        @else
                            <div
                                class="w-24 h-24 rounded-full overflow-hidden flex justify-center align-items-center border-2 border-black cursor-pointer">
                                <svg class="h-full w-20 text-black" width="24" height="24" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" />
                                    <circle cx="12" cy="7" r="4" />
                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    <div class="mt-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" name="name" id="name" value="{{ $user->clubProfile->name }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-lime-400 focus:border-lime-400 block w-full p-2.5">
                    </div>

                    <div class="mt-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ $user->email }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-lime-400 focus:border-lime-400 block w-full p-2.5">
                    </div>

                    <div class="mt-4">
                        <label for="telephone" class="block text-sm font-medium text-gray-700">Teléfono</label>
                        <input type="text" name="telephone" id="telephone" value="{{ $user->clubProfile->telephone }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-lime-400 focus:border-lime-400 block w-full p-2.5">
                    </div>

                    <div class="mt-4">
                        <label for="address" class="block text-sm font-medium text-gray-700">Dirección</label>
                        <input type="text" name="address" id="address" value="{{ $user->clubProfile->address }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-lime-400 focus:border-lime-400 block w-full p-2.5">
                    </div>

                    <div class="mt-4">
                        <label for="province" class="block text-sm font-medium text-gray-700">Provincia</label>
                        <input type="text" name="province" id="province" value="{{ $user->clubProfile->province }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-lime-400 focus:border-lime-400 block w-full p-2.5">
                    </div>

                    <div class="mt-4">
                        <label for="hoaraios" class="block text-sm font-medium text-gray-700">Horario</label>
                        <input type="text" name="province" id="province" value="{{ $user->clubProfile->province }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-lime-400 focus:border-lime-400 block w-full p-2.5">
                    </div>

                    <div class="mt-4 flex items-center justify-center">
                        <button type="submit" class="px-4 py-2 bg-lime-300 text-black rounded-md hover:bg-lime-400">
                            Guardar cambios</button>
                    </div>
                </form>
            @endif
        @endrole
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var editFoto = document.getElementById('editButton');
        var editFotoClub = document.getElementById('editButtonClub');
        var editFotoClub2 = document.getElementById('editButtonClub2');

        if (editFoto) {
            editFoto.addEventListener('click', function() {
                document.getElementById('fileInput').click();
            });
        }

        if (editFotoClub) {
            editFotoClub.addEventListener('click', function() {
                document.getElementById('fileInputClub').click();
            });
        }
    });
</script>
