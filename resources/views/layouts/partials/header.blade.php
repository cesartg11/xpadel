<header class="w-full h-24 flex items-center justify-center shadow bg-white fixed top-0 z-50">
    <nav class="w-11/12 h-24 flex items-center justify-between flex-row px-4 py-2 bg-white z-50">

        {{-- Botón menu deplegable --}}
        <div class="text-black flex justify-between">
            <button class="mobile-menu-button p-2 hover:bg-gray-100 rounded-full" id="sidebar-menu-button">
                <svg class="h-9 w-9" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        {{-- Menu sidebar deplegable --}}
        <div id="sidebar"
            class="sidebar h-screen bg-white  text-black w-64 space-y-6 py-7 px-2 absolute inset-y-0 left-0 transform -translate-x-full transition duration-200 ease-in-out shadow z-50">
            {{-- Contenido del menú --}}
            <img src="{{ asset('assets/imagenes/nombre.png') }}" alt="logo" class="w-5/6 h-10">

            <div class="flex flex-row ml-5">
                <a href="{{ route('clubs.index') }}" class="block px-4 py-2 text-sm flex flex-row justify-center align-items-center gap-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    Home
                </a>
            </div>

            <div class="flex flex-row align-content-center gap-1 ml-5">
                <a href="#" class="block px-4 py-2 text-sm flex flex-row justify-center align-items-center gap-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 0 0 7.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 0 0 2.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 0 1 2.916.52 6.003 6.003 0 0 1-5.395 4.972m0 0a6.726 6.726 0 0 1-2.749 1.35m0 0a6.772 6.772 0 0 1-3.044 0" />
                    </svg>
                    Torneos
                </a>
            </div>

            @auth
                <div class="flex flex-row align-content-center gap-1 ml-5">
                    <a href="#" class="block px-4 py-2 text-sm flex flex-row justify-center align-items-center gap-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 0 1-.923 1.785A5.969 5.969 0 0 0 6 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337Z" />
                        </svg>
                        Chat
                    </a>
                </div>

                @role('club')
                    <div class="flex flex-row align-content-center gap-1 ml-5">
                        <a href="{{ route('clubs.show', auth()->user()->clubProfile) }}" class="block px-4 py-2 text-sm flex flex-row justify-center align-items-center gap-4">
                            <img src="{{ asset('assets/imagenes/tennis-ball-svgrepo-com.svg') }}" alt="Pelota de tenins"
                                class="size-5">
                            Mi club
                        </a>
                    </div>
                @else
                    <div class="flex flex-row align-content-center gap-1 ml-5">
                        <a href="#" class="block px-4 py-2 text-sm flex flex-row justify-center align-items-center gap-4">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                            </svg>
                            Mis actividades
                        </a>
                    </div>
                @endrole
            @endauth

        </div>

        {{-- Logo --}}
        <div class="absolute left-1/2 transform -translate-x-1/2">
            <img src="{{ asset('assets/imagenes/logo.png') }}" alt="logo" class="w-24">
        </div>

        {{-- Perfil --}}
        @auth
            <div class="relative inline-block text-left">

                {{-- Foto de pérfil --}}
                <div id="profile-picture"
                    class="w-12 h-12 rounded-full overflow-hidden flex justify-center align-items-center hover:bg-gray-100 cursor-pointer">
                    @role('club')
                        @php
                            $profilePhoto = auth()->user()->clubProfile->photos->where('photo_type', 'perfil')->first();
                            $photoUrl = $profilePhoto ? 'storage/' . $profilePhoto->photo_url : null;
                        @endphp
                        @if ($photoUrl)
                            <img src="{{ asset('storage/' . $photoUrl) }}" alt="Foto de pérfil"
                                class="w-full h-full object-cover">
                        @else
                            <svg class="h-full w-8 text-black" width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" />
                                <circle cx="12" cy="7" r="4" />
                                <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                            </svg>
                        @endif
                    @else
                        @if (auth()->user()->userProfile->profile_photo_path)
                            <img src="{{ asset('assets/users/' . auth()->user()->userProfile->profile_photo_path) }}"
                                class="w-full h-full object-cover">
                        @else
                            <svg class="h-full w-8 text-black" width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" />
                                <circle cx="12" cy="7" r="4" />
                                <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                            </svg>
                        @endif
                    @endrole
                </div>

                {{-- Menú desplegable --}}
                <div class="flex flex items-center justify-content-end relative inline-block text-left hidden"
                    id="menu">
                    <div class="absolute right-0 z-10 mt-48 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                        role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                        <div class="py-1" role="none">
                            <p class="text-gray-700 block px-4 py-2 text-sm" role="menuitem" tabindex="-1">
                                @role('club')
                                    {{ auth()->user()->clubProfile->name }}</p>
                            @else
                                {{ auth()->user()->userProfile->name }}</p>
                            @endrole
                            <p class="text-gray-700 block px-4 py-2 text-sm" role="menuitem">{{ auth()->user()->email }}
                            </p>
                            <hr class="my-2">
                            <a href="#" class="text-gray-700 block px-4 py-2 text-sm" role="menuitem"
                                id="menu-item-0">Pérfil</a>
                            <form method="POST" action="{{ route('logout') }}" role="none">
                                @csrf
                                <button type="submit" class="text-gray-700 block w-full px-4 py-2 text-left text-sm"
                                    role="menuitem" tabindex="-1" id="menu-item-3">
                                    Cerrar sesión
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M10 12.5a.5.5 0 01-.5.5h-8a.5.5 0 01-.5-.5v-9a.5.5 0 01.5-.5h8a.5.5 0 01.5.5v2a.5.5 0 001 0v-2A1.5 1.5 0 009.5 2h-8A1.5 1.5 0 000 3.5v9A1.5 1.5 0 001.5 14h8a1.5 1.5 0 001.5-1.5v-2a.5.5 0 00-1 0v2z" />
                                        <path fill-rule="evenodd"
                                            d="M15.854 8.354a.5.5 0 000-.708l-3-3a.5.5 0 00-.708.708L14.293 7.5H5.5a.5.5 0 000 1h8.793l-2.147 2.146a.5.5 0 00.708.708l3-3z" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}"
                class="flex justify-center items-center text-black rounded w-28 h-9 bg-lime-300 hover:bg-lime-400">
                Iniciar sesión
            </a>
        @endauth

    </nav>
</header>

{{-- Scrript para abrir y cerrar el menu desplegable y de perfil --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {

        //Menu lateral
        var sidebarButton = document.getElementById('sidebar-menu-button');
        var sidebar = document.getElementById('sidebar');

        //Función para alternar la visibilidad del menú lateral
        function toggleSidebar() {
            // Esta clase maneja la traducción de la barra lateral desde el lado izquierdo
            sidebar.classList.toggle('translate-x-0'); // Muestra el menú
            sidebar.classList.toggle('-translate-x-full'); // Oculta el menú
        }

        // Evento de clic en el botón para alternar la barra lateral
        sidebarButton.addEventListener('click', function(event) {
            event
                .stopPropagation(); // Previene la propagación para no disparar otros eventos de clic del documento
            toggleSidebar();
        });

        // Evento para ocultar la barra lateral si se hace clic fuera de ella
        document.addEventListener('click', function(event) {
            if (!sidebar.contains(event.target) && sidebar.classList.contains('translate-x-0')) {
                // Si se hace clic fuera y la barra está visible, ocúltala
                toggleSidebar();
            }
        });

        // Asegura que los clics dentro de la barra no la cierren
        sidebar.addEventListener('click', function(event) {
            event.stopPropagation();
        });

        //Menu de perfil de usuario
        var profile = document.getElementById('profile-picture');
        var menu = document.getElementById(
            'menu'); // Cambia por la clase si los ID no son únicos o son dinámicos

        function toggleMenu() {
            if (menu) {
                menu.classList.toggle('hidden');
            }
        }

        if (profile) {
            profile.addEventListener('click', function(event) {
                event.stopPropagation();
                toggleMenu();
            });
        }

        document.addEventListener('click', function(event) {
            if (menu && !menu.contains(event.target) && !menu.classList.contains(
                    'hidden')) {
                menu.classList.add('hidden');
            }
        });
    });
</script>
