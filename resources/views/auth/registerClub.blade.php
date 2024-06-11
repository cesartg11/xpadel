<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('users.store') }}" class="space-y-6 ">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <!-- Columna izquierda -->
                <div>
                    <div>
                        <x-label for="email" value="{{ __('Email') }}" />
                        <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                            required autocomplete="username" />
                    </div>

                    <div class="mt-6">
                        <x-label for="password" value="{{ __('Contraseña') }}" />
                        <x-input id="password" class="block mt-1 w-full" type="password" name="password" required
                            autocomplete="new-password" />
                    </div>

                    <div class="mt-6">
                        <x-label for="password_confirmation" value="{{ __('Confirmar contraseña') }}" />
                        <x-input id="password_confirmation" class="block mt-1 w-full" type="password"
                            name="password_confirmation" required />
                    </div>
                </div>

                <!-- Columna derecha -->
                <div>
                    <div>
                        <x-label for="name" value="{{ __('Nombre') }}" />
                        <x-input id="name" class="block mt-1 w-full" type="text" name="name"
                            :value="old('name')" required autocomplete="name" />
                    </div>

                    <div class="mt-6">
                        <x-label for="surname" value="{{ __('Apellidos') }}" />
                        <x-input id="surname" class="block mt-1 w-full" type="text" name="surname"
                            :value="old('surname')" required autocomplete="surname" />
                    </div>

                    <div class="mt-6">
                        <x-label for="age" value="{{ __('Edad') }}" />
                        <x-input id="age" class="block mt-1 w-full" type="number" name="age"
                            :value="old('age')" required autocomplete="age" />
                    </div>
                </div>

                <div class="col-span-2 mx-auto">
                    <x-label for="telephone" value="{{ __('Telefono') }}" />
                    <x-input id="telephone" class="block mt-1 w-full" type="text" pattern="[0-9]{9}" name="telephone" :value="old('telephone')" required autocomplete="telephone" />
                </div>

            </div>

            <div class="flex items-center justify-between mt-6">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('Ya estas registrado?') }}
                </a>

                <x-button class="ml-4">
                    {{ __('Registrarse') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
