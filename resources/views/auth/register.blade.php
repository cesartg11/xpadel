<style>
    .toggle-checkbox:checked {
        @apply: right-0 var(--destacado);
        right: 0;
        border-color: var(--destacado);
    }

    .toggle-checkbox:checked+.toggle-label {
        @apply: var(--destacado);
        background-color: var(--destacado);
    }

    .toggle-checkbox:checked+.toggle-label {
        transform: translateX(100%);
    }
</style>

<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />
        <div class="flex justify-center">
            <div class="toggle-container relative w-36 h-10 bg-gray-300 rounded-full p-1 shadow-inner">
                <!-- Checkbox oculto -->
                <input type="checkbox" id="toggle"
                    class="toggle-checkbox absolute h-full w-full opacity-0 z-20 cursor-pointer" />
                <!-- Etiqueta para el diseño visual -->
                <label for="toggle"
                    class="toggle-label block h-full w-1/2 bg-white rounded-full shadow-md transition-transform duration-300 ease-in-out"></label>
                <!-- Texto -->
                <div
                    class="toggle-text absolute inset-0 flex items-center justify-between px-3 text-sm font-bold text-black select-none">
                    <span>Usuario</span>
                    <span class="mr-2">Clubs</span>
                </div>
            </div>
        </div>

        {{-- Registro de usuario --}}
        <form method="POST" action="{{ route('users.store') }}" class="space-y-6" id="userForm">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <!-- Columna izquierda -->
                <div>
                    <div>
                        <x-label for="email" value="{{ __('Email') }}" />
                        <x-input id="email" class="block mt-1 w-full" type="email" name="email"
                            :value="old('email')" placeholder="Email" required autocomplete="username" />
                    </div>

                    <div class="mt-6">
                        <x-label for="password" value="{{ __('Contraseña') }}" />
                        <x-input id="password" class="block mt-1 w-full" type="password" name="password" placeholder="Contraseña" required
                            autocomplete="new-password" />
                    </div>

                    <div class="mt-6">
                        <x-label for="password_confirmation" value="{{ __('Confirmar contraseña') }}" />
                        <x-input id="password_confirmation" class="block mt-1 w-full" type="password"
                            name="password_confirmation" placeholder="Confirmar contraseña" required />
                    </div>
                </div>

                <!-- Columna derecha -->
                <div>
                    <div>
                        <x-label for="name" value="{{ __('Nombre') }}" />
                        <x-input id="name" class="block mt-1 w-full" type="text" name="name"
                            :value="old('name')" placeholder="Nombre" required autocomplete="name" />
                    </div>

                    <div class="mt-6">
                        <x-label for="surname" value="{{ __('Apellidos') }}" />
                        <x-input id="surname" class="block mt-1 w-full" type="text" name="surname"
                            :value="old('surname')" placeholder="Apellidos" required autocomplete="surname" />
                    </div>

                    <div class="mt-6">
                        <x-label for="age" value="{{ __('Edad') }}" />
                        <x-input id="age" class="block mt-1 w-full" type="number" name="age"
                            :value="old('age')" placeholder="Edad" required autocomplete="age" />
                    </div>
                </div>

                <div class="col-span-2 mx-auto">
                    <x-label for="telephone" value="{{ __('Teléfono') }}" />
                    <x-input id="telephone" class="block mt-1 w-full" type="text" pattern="[0-9]{9}" name="telephone"
                        :value="old('telephone')" placeholder="Teléfono" required autocomplete="telephone" />
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

        {{-- Registro de club --}}
        <form method="POST" action="{{ route('clubs.store') }}" class="space-y-6 hidden" id="clubForm">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <!-- Columna izquierda -->
                <div>
                    <div>
                        <x-label for="email" value="{{ __('Email') }}" />
                        <x-input id="email" class="block mt-1 w-full" type="email" name="email"
                            :value="old('email')" placeholder="Email" required autocomplete="username" />
                    </div>

                    <div class="mt-6">
                        <x-label for="password" value="{{ __('Contraseña') }}" />
                        <x-input id="password" class="block mt-1 w-full" type="password" name="password" placeholder="Contraseña" required
                            autocomplete="password" />
                    </div>

                    <div class="mt-6">
                        <x-label for="password_confirmation" value="{{ __('Confirmar contraseña') }}" />
                        <x-input id="password_confirmation" class="block mt-1 w-full" type="password"
                            name="password_confirmation" placeholder="Confirmar contraseña" required />
                    </div>
                </div>

                <!-- Columna derecha -->
                <div>
                    <div>
                        <x-label for="name" value="{{ __('Nombre del club') }}" />
                        <x-input id="name" class="block mt-1 w-full" type="text" name="name"
                            :value="old('name')" placeholder="Nombre del club" required autocomplete="name" />
                    </div>

                    <div class="mt-6">
                        <x-label for="address" value="{{ __('Dirección') }}" />
                        <x-input id="address" class="block mt-1 w-full" type="text" name="address"
                            :value="old('address')" placeholder="Dirección" required autocomplete="address" />
                    </div>

                    <div class="mt-6">
                        <x-label for="province" value="{{ __('Provincia') }}" />
                        <select id="province" name="province"
                            class="block mt-1 w-full border-gray-300 text-gray-500 rounded-md shadow-sm"
                            required autocomplete="province">
                            <option value="">Seleccione una provincia</option>
                            <option value="Álava" {{ old('province') == 'Álava' ? 'selected' : '' }}>Álava</option>
                            <option value="Albacete" {{ old('province') == 'Albacete' ? 'selected' : '' }}>Albacete
                            </option>
                            <option value="Alicante" {{ old('province') == 'Alicante' ? 'selected' : '' }}>Alicante
                            </option>
                            <option value="Almería" {{ old('province') == 'Almería' ? 'selected' : '' }}>Almería
                            </option>
                            <option value="Asturias" {{ old('province') == 'Asturias' ? 'selected' : '' }}>Asturias
                            </option>
                            <option value="Ávila" {{ old('province') == 'Ávila' ? 'selected' : '' }}>Ávila</option>
                            <option value="Badajoz" {{ old('province') == 'Badajoz' ? 'selected' : '' }}>Badajoz
                            </option>
                            <option value="Baleares" {{ old('province') == 'Baleares' ? 'selected' : '' }}>Baleares
                                (Illes)</option>
                            <option value="Barcelona" {{ old('province') == 'Barcelona' ? 'selected' : '' }}>Barcelona
                            </option>
                            <option value="Burgos" {{ old('province') == 'Burgos' ? 'selected' : '' }}>Burgos</option>
                            <option value="Cáceres" {{ old('province') == 'Cáceres' ? 'selected' : '' }}>Cáceres
                            </option>
                            <option value="Cádiz" {{ old('province') == 'Cádiz' ? 'selected' : '' }}>Cádiz</option>
                            <option value="Cantabria" {{ old('province') == 'Cantabria' ? 'selected' : '' }}>Cantabria
                            </option>
                            <option value="Castellón" {{ old('province') == 'Castellón' ? 'selected' : '' }}>Castellón
                            </option>
                            <option value="Ciudad Real" {{ old('province') == 'Ciudad Real' ? 'selected' : '' }}>
                                Ciudad Real</option>
                            <option value="Córdoba" {{ old('province') == 'Córdoba' ? 'selected' : '' }}>Córdoba
                            </option>
                            <option value="Coruña, A" {{ old('province') == 'Coruña, A' ? 'selected' : '' }}>Coruña
                            </option>
                            <option value="Cuenca" {{ old('province') == 'Cuenca' ? 'selected' : '' }}>Cuenca</option>
                            <option value="Girona" {{ old('province') == 'Girona' ? 'selected' : '' }}>Girona</option>
                            <option value="Granada" {{ old('province') == 'Granada' ? 'selected' : '' }}>Granada
                            </option>
                            <option value="Guadalajara" {{ old('province') == 'Guadalajara' ? 'selected' : '' }}>
                                Guadalajara</option>
                            <option value="Gipuzkoa" {{ old('province') == 'Gipuzkoa' ? 'selected' : '' }}>Gipuzkoa
                            </option>
                            <option value="Huelva" {{ old('province') == 'Huelva' ? 'selected' : '' }}>Huelva</option>
                            <option value="Huesca" {{ old('province') == 'Huesca' ? 'selected' : '' }}>Huesca</option>
                            <option value="Jaén" {{ old('province') == 'Jaén' ? 'selected' : '' }}>Jaén</option>
                            <option value="León" {{ old('province') == 'León' ? 'selected' : '' }}>León</option>
                            <option value="Lleida" {{ old('province') == 'Lleida' ? 'selected' : '' }}>Lleida</option>
                            <option value="Lugo" {{ old('province') == 'Lugo' ? 'selected' : '' }}>Lugo</option>
                            <option value="Madrid" {{ old('province') == 'Madrid' ? 'selected' : '' }}>Madrid</option>
                            <option value="Málaga" {{ old('province') == 'Málaga' ? 'selected' : '' }}>Málaga</option>
                            <option value="Murcia" {{ old('province') == 'Murcia' ? 'selected' : '' }}>Murcia</option>
                            <option value="Navarra" {{ old('province') == 'Navarra' ? 'selected' : '' }}>Navarra
                            </option>
                            <option value="Ourense" {{ old('province') == 'Ourense' ? 'selected' : '' }}>Ourense
                            </option>
                            <option value="Palencia" {{ old('province') == 'Palencia' ? 'selected' : '' }}>Palencia
                            </option>
                            <option value="Las Palmas" {{ old('province') == 'Las Palmas' ? 'selected' : '' }}>Las
                                Palmas</option>
                            <option value="Pontevedra" {{ old('province') == 'Pontevedra' ? 'selected' : '' }}>
                                Pontevedra</option>
                            <option value="Salamanca" {{ old('province') == 'Salamanca' ? 'selected' : '' }}>Salamanca
                            </option>
                            <option value="Santa Cruz de Tenerife"
                                {{ old('province') == 'Santa Cruz de Tenerife' ? 'selected' : '' }}>Santa Cruz de
                                Tenerife</option>
                            <option value="Segovia" {{ old('province') == 'Segovia' ? 'selected' : '' }}>Segovia
                            </option>
                            <option value="Sevilla" {{ old('province') == 'Sevilla' ? 'selected' : '' }}>Sevilla
                            </option>
                            <option value="Soria" {{ old('province') == 'Soria' ? 'selected' : '' }}>Soria</option>
                            <option value="Tarragona" {{ old('province') == 'Tarragona' ? 'selected' : '' }}>Tarragona
                            </option>
                            <option value="Teruel" {{ old('province') == 'Teruel' ? 'selected' : '' }}>Teruel</option>
                            <option value="Toledo" {{ old('province') == 'Toledo' ? 'selected' : '' }}>Toledo</option>
                            <option value="Valencia" {{ old('province') == 'Valencia' ? 'selected' : '' }}>Valencia
                            </option>
                            <option value="Valladolid" {{ old('province') == 'Valladolid' ? 'selected' : '' }}>
                                Valladolid</option>
                            <option value="Bizkaia" {{ old('province') == 'Bizkaia' ? 'selected' : '' }}>Bizkaia
                            </option>
                            <option value="Zamora" {{ old('province') == 'Zamora' ? 'selected' : '' }}>Zamora</option>
                            <option value="Zaragoza" {{ old('province') == 'Zaragoza' ? 'selected' : '' }}>Zaragoza
                            </option>
                            <option value="Ceuta" {{ old('province') == 'Ceuta' ? 'selected' : '' }}>Ceuta</option>
                            <option value="Melilla" {{ old('province') == 'Melilla' ? 'selected' : '' }}>Melilla
                            </option>
                        </select>
                    </div>

                </div>

                <div class="col-span-2 mx-auto">
                    <x-label for="telephone" value="{{ __('Teléfono') }}" />
                    <x-input id="telephone" class="block mt-1 w-full" type="text" pattern="[0-9]{9}" name="telephone"
                        :value="old('telephone')" placeholder="Teléfono" required autocomplete="telephone" />
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

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', () => {
        var toggle = document.getElementById('toggle');
        var userForm = document.getElementById('userForm');
        var clubForm = document.getElementById('clubForm');
        var toggleLabel = document.getElementById('toggleLabel');

        toggle.addEventListener('change', function() {
            if (this.checked) {
                userForm.classList.add('hidden');
                clubForm.classList.remove('hidden');
                toggleLabel.textContent = 'Club';
            } else {
                userForm.classList.remove('hidden');
                clubForm.classList.add('hidden');
                toggleLabel.textContent = 'Usuario';
            }
        });
    });
</script>
