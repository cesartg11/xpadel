<x-guest-layout>
        <x-authentication-card>

            <x-slot name="logo">
                <x-authentication-card-logo />
            </x-slot>

            <x-validation-errors class="mb-4" />

            @session('status')
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ $value }}
                </div>
            @endsession

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div>
                    <x-label for="email" value="{{ __('Email') }}" />
                    <x-input id="email" class="block mt-1 w-full focus:outline-none focus:border-destacado"
                        type="email" name="email" :value="old('email')" placeholder="Email" required autofocus autocomplete="username" />
                </div>

                <div class="mt-4">
                    <x-label for="password" value="{{ __('Password') }}" />
                    <x-input id="password" class="block mt-1 w-full focus:outline-none focus:border-destacado"
                        type="password" name="password" placeholder="Contraseña" required autocomplete="current-password" />
                </div>

                <div class="block mt-4">
                    <label for="remember_me" class="flex items-center">
                        <x-checkbox id="remember_me" name="remember" />
                        <span class="ms-2 text-sm text-gray-600">{{ __('Recuerdame') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-start mt-4">
                    <x-button class="ms-4">
                        {{ __('Iniciar sesión') }}
                    </x-button>
                </div>

                <hr class="w-full my-5">

                <div class="flex items-center justify-start mt-4">
                    <a href="{{ route('register') }}" class="underline text-gray-700 hover:text-gray-900">¿Eres nuevo?,
                        regístrate</a>
                </div>

                <div class="flex items-center justify-start mt-4">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md"
                            href="{{ route('password.request') }}">
                            {{ __('¿Has olvidado la contraseña?') }}
                        </a>
                    @endif
                </div>

            </form>
        </x-authentication-card>
</x-guest-layout>
