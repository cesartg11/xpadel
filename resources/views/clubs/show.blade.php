@extends('layouts.plantilla')
@section('titulo', $club->name)
@section('contenido')
    <div class="w-full mt-24">

        <!-- Cabecera del club -->
        @php
            $backPhoto = $club->photos->where('photo_type', 'cabecera')->first();
        @endphp
        @if ($backPhoto)
            <div class="w-full h-52 flex justify-between items-center text-white"
                style="background-image: url({{ asset('assets/clubs/' . $backPhoto->photo_url) }}); background-size: cover; background-position: center; background-repeat: no-repeat;">
                <div class="ml-20 flex flex-col justify-center">
                    <h1 class="text-3xl font-semibold">{{ $club->name }}</h1>
                    <p>{{ $club->address }}</p>
                </div>
                @role('club')
                    @if (auth()->user()->clubProfile && $club->id == auth()->user()->clubProfile->id)
                        <div class="mr-20">
                            <button id="openModalButton"
                                class="flex flex-row items-center justify-center gap-2 h-10 bg-lime-300 hover:bg-lime-400 text-black py-2 px-4 rounded"
                                data-club="{{ $club->id }}">
                                Editar foto
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </button>
                        </div>
                    @endif
                @endrole
            </div>
        @else
            <div class="w-full h-52 flex justify-between items-center bg-gradient-to-t from-lime-300 to-lime-100">
                <div class="ml-20 flex flex-col justify-center">
                    <h1 class="text-3xl font-semibold">{{ $club->name }}</h1>
                    <p>{{ $club->address }}</p>
                </div>
                @role('club')
                    @if (auth()->user()->clubProfile && $club->id == auth()->user()->clubProfile->id)
                        <div class="mr-20">
                            <button id="openModalButton"
                                class="flex flex-row items-center justify-center gap-2 h-10 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                data-club="{{ $club->id }}">
                                Editar foto
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>

                            </button>
                        </div>
                    @endif
                @endrole
            </div>
        @endif

        <div class="mx-20">

            <!-- Horarios de las pistas -->
            <div class="my-10">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">Pistas</h2>
                    @role('club')
                        @if (auth()->user()->clubProfile && $club->id == auth()->user()->clubProfile->id)
                            <button id="createCourtButton"
                                class="flex flex-row items-center justify-center gap-2 px-4 py-2 text-black rounded bg-lime-300 hover:bg-lime-400"
                                data-club="{{ $club->id }}">
                                Crear pista
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </button>
                        @endif
                    @endrole
                </div>

                <div class="flex flex-col bg-white shadow overflow-hidden rounded-lg">
                    <!-- Encabezado de horas -->
                    @if (count($club->hours) > 0)
                        <div class="flex">
                            <div class="w-32 text-center font-semibold"></div>
                            @php
                                $days = [
                                    'Monday' => 'Lunes',
                                    'Tuesday' => 'Martes',
                                    'Wednesday' => 'Miércoles',
                                    'Thursday' => 'Jueves',
                                    'Friday' => 'Viernes',
                                    'Saturday' => 'Sábado',
                                    'Sunday' => 'Domingo',
                                ];

                                $todayEnglish = \Carbon\Carbon::now()->format('l'); // Obtiene el día de la semana en inglés directamente
                                $today = $days[$todayEnglish] ?? 'Día no definido';

                                $todayHours = $club->hours->where('day_of_week', $today)->first();

                                $openingHour = $todayHours
                                    ? \Carbon\Carbon::parse($todayHours->opening_time)->format('H')
                                    : 'No hay horario disponible';

                                $closingHour = $todayHours
                                    ? \Carbon\Carbon::parse($todayHours->closing_time)->format('H')
                                    : 'No hay horario disponible';

                            @endphp
                            @for ($hour = $openingHour; $hour <= $closingHour; $hour++)
                                <div class="flex-1 text-center border border-t border-b">{{ $hour }}</div>
                            @endfor
                        </div>
                        <!-- Listado de pistas y disponibilidad por hora -->
                        @forelse ($club->courts as $court)
                            <div class="flex">
                                <div class="w-32 flex items-center justify-center border h-12">Pista {{ $court->number }}
                                </div>
                                @for ($hour = $openingHour; $hour <= $closingHour; $hour++)
                                    @php
                                        // Ajusta la hora actual al inicio de la hora específica
                                        $hourDateTime = \Carbon\Carbon::now()->setTime($hour, 0);
                                        $now = \Carbon\Carbon::now()->addHours(2);
                                        $slotClass = $now->greaterThan($hourDateTime)
                                            ? 'bg-gray-300'
                                            : 'clickable hover:bg-lime-200 cursor-pointer';
                                        $slotClass .=
                                            $slotClass === 'clickable hover:bg-lime-200 cursor-pointer'
                                                ? ' bg-white'
                                                : '';
                                        $currentHourFormatted = $hourDateTime->format('Y-m-d H:i:s');
                                        $slotStatus = $pistas[$court->id][$currentHourFormatted] ?? 'available'; // Defaults to available
                                    @endphp
                                    <div id="slot-{{ $court->id }}-{{ $court->number }}-{{ $hour }}-{{ $club->id }}"
                                        class="flex-1 {{ $slotStatus === 'user' ? 'bg-lime-300' : ($slotStatus === 'occupied' ? 'bg-gray-300' : $slotClass) }} border">
                                        <!-- Puedes agregar contenido adicional aquí si es necesario -->
                                    </div>
                                @endfor
                            </div>
                        @empty
                            <p class="text-center py-4">Este club no tiene pistas disponibles.</p>
                        @endforelse
                    @else
                        <p class="text-center py-4">Este club no tiene pistas disponibles.</p>
                    @endif
                    @if (count($club->hours) > 0)
                        <div class="px-6 py-4 bg-gray-50">
                            <div class="flex justify-center space-x-4">
                                <div class="flex items-center space-x-2">
                                    <div class="w-6 h-6 bg-lime-300 rounded"></div>
                                    <span>Tu reserva</span>
                                </div>
                                <div class="flex items-center space-x-2 ">
                                    <div class="w-6 h-6 bg-gray-300 rounded"></div>
                                    <span>Ocupado</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-6 h-6 bg-white border border-gray-300 rounded"></div>
                                    <span>Libre</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Clases disponibles -->
            <div class="my-10 ">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold mb-4">Clases</h2>
                    @role('club')
                        @if (auth()->user()->clubProfile && $club->id == auth()->user()->clubProfile->id)
                            <button id="createClassButton"
                                class="flex flex-row items-center justify-center gap-2 px-4 py-2  text-black rounded bg-lime-300 hover:bg-lime-400"
                                data-club="{{ $club->id }}" data-club-courts="{{ json_encode($club->courts) }}">
                                Crear clase
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </button>
                        @endif
                    @endrole
                </div>
                <div class="shadow">
                    <!-- Encabezados -->
                    <div class="flex justify-between bg-gray-200 p-4 p-lg font-bold rounded-t">
                        <div class="flex-1 text-center">Nivel</div>
                        <div class="flex-1 text-center">Pista</div>
                        <div class="flex-1 text-center">Fecha</div>
                        <div class="flex-1 text-center">Hora</div>
                        <div class="flex-1 text-center">Acción</div>
                    </div>

                    <!-- Datos de las clases -->
                    @forelse ($club->classes as $class)
                        <div class="flex justify-between items-center border p-4 p-lg text-center">
                            <div class="flex-1">{{ $class->level }}</div>
                            <div class="flex-1">Pista {{ $class->court->number }}</div>
                            <div class="flex-1">{{ \Carbon\Carbon::parse($class->start_time)->format('d/m/Y') }}</div>
                            <div class="flex-1">{{ \Carbon\Carbon::parse($class->start_time)->format('H:i') }} a
                                {{ \Carbon\Carbon::parse($class->end_time)->format('H:i') }}</div>
                            <div class="flex-1">
                                @if (auth()->check() && auth()->user()->clubProfile && $club->id == auth()->user()->clubProfile->id)
                                    <button class="classEditButton rounded-full p-2 hover:bg-lime-300"
                                        data-class-id='{{ $class->id }}'
                                        data-club-courts="{{ json_encode($club->courts) }}"
                                        data-class-court="{{ $class->court->id }}" data-class-level='{{ $class->level }}'
                                        data-class-start='{{ $class->start_time }}'
                                        data-class-end='{{ $class->end_time }}' data-club='{{ $club->id }}'>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </button>
                                    <button class="classDeleteButton rounded-full p-2 hover:bg-red-500"
                                        data-class-id='{{ $class->id }}' data-club='{{ $club->id }}'
                                        data-class-level='{{ $class->level }}'
                                        data-class-court='{{ $class->court->number }}'
                                        data-class-date='{{ \Carbon\Carbon::parse($class->start_time)->format('d/m/Y') }}'
                                        data-class-start='{{ \Carbon\Carbon::parse($class->start_time)->format('H:i') }}'
                                        data-class-end='{{ \Carbon\Carbon::parse($class->end_time)->format('H:i') }}'>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                @else
                                    <button
                                        class="openclassRegisterButton px-4 py-2 text-black rounded bg-lime-300 hover:bg-lime-400"
                                        data-class='{{ $class->id }}' data-class-level='{{ $class->level }}'
                                        data-class-court='{{ $class->court->number }}'
                                        data-class-date='{{ \Carbon\Carbon::parse($class->start_time)->format('d/m/Y') }}'
                                        data-class-start='{{ \Carbon\Carbon::parse($class->start_time)->format('H:i') }}'
                                        data-class-end='{{ \Carbon\Carbon::parse($class->end_time)->format('H:i') }}'
                                        data-club='{{ $club->id }}'>
                                        Apuntarse
                                    </button>
                                @endif

                            </div>
                        </div>
                    @empty
                        <p class="text-center py-4">Este club no tiene clases disponibles.</p>
                    @endforelse
                </div>
            </div>

            <!-- Torneos -->
            <div class="my-10">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold mb-4">Torneos</h2>
                    @role('club')
                        @if (auth()->user()->clubProfile && $club->id == auth()->user()->clubProfile->id)
                            <button id="createTournamentButton"
                                class="flex flex-row items-center justify-center gap-2 px-4 py-2 text-black rounded bg-lime-300 hover:bg-lime-400"
                                data-club="{{ $club->id }}">
                                Crear Torneo
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </button>
                        @endif
                    @endrole
                </div>
                <div class="shadow">
                    <!-- Encabezados -->
                    <div class="flex justify-between bg-gray-200 p-4 p-lg font-bold rounded-t">
                        <div class="flex-1 text-center">Torneo</div>
                        <div class="flex-1 text-center">Estado</div>
                        <div class="flex-1 text-center">Fecha inicio</div>
                        <div class="flex-1 text-center">Fecha fin</div>
                        <div class="flex-1 text-center">Acción</div>
                    </div>

                    <!-- Datos de los torneos -->
                    @forelse ($club->tournaments as $tournament)
                        <div class="flex justify-between items-center border p-4 p-lg text-center">
                            <div class="flex-1">{{ $tournament->name }}</div>
                            <div class="flex-1">{{ $tournament->status }}</div>
                            <div class="flex-1">{{ \Carbon\Carbon::parse($tournament->start_date)->format('d/m/Y') }}
                            </div>
                            <div class="flex-1">{{ \Carbon\Carbon::parse($tournament->end_date)->format('d/m/Y') }}</div>
                            <div class="flex-1">
                                @if (auth()->check() && auth()->user()->clubProfile && $club->id == auth()->user()->clubProfile->id)
                                    <button class="tournamentEditButton rounded-full p-2 hover:bg-lime-300"
                                        data-tournament-id='{{ $tournament->id }}'
                                        data-tournament-name="{{ $tournament->name }}"
                                        data-tournament-status="{{ $tournament->status }}"
                                        data-tournament-photo="{{ $tournament->photo_url }}"
                                        data-tournament-description="{{ $tournament->description }}"
                                        data-tournament-start='{{ \Carbon\Carbon::parse($tournament->start_date)->format('Y/m/d') }}'
                                        data-tournament-end='{{ \Carbon\Carbon::parse($tournament->end_date)->format('Y/m/d') }}'
                                        data-club='{{ $club->id }}'>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </button>
                                    <button class="tournamentDeleteButton rounded-full p-2 hover:bg-red-500"
                                        data-tournament-id='{{ $tournament->id }}'
                                        data-tournament-name="{{ $tournament->name }}"
                                        data-tournament-start='{{ \Carbon\Carbon::parse($tournament->start_date)->format('Y/m/d') }}'
                                        data-tournament-end='{{ \Carbon\Carbon::parse($tournament->end_date)->format('Y/m/d') }}'
                                        data-club='{{ $club->id }}'>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                @else
                                    <a href="{{ route('tournaments.show', $tournament) }}"
                                        class="inline-block px-4 py-2 text-black rounded bg-lime-300 hover:bg-lime-400 text-center">
                                        Ver
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-center py-4">Este club no tiene torneos disponibles.</p>
                    @endforelse
                </div>
            </div>

            <!-- Información adicional del club -->
            <div class="my-10">
                <h2 class="text-xl font-semibold mb-4">Información del club</h2>
                <div class="rounded border-2 flex flex-col p-5 shadow-lg">
                    @if ($club->description)
                        <p class="mx-5">{{ $club->description }}</p>
                    @endif
                    @php
                        $generalPhoto = $club->photos->where('photo_type', 'general')->first();
                    @endphp
                    @if ($generalPhoto)
                        <img src="{{ asset('assets/clubs/' . $generalPhoto->photo_url) }}" alt="Imagen del club"
                            class="max-w-full h-auto rounded-lg shadow-md mt-10 w-1/2 self-center">
                    @endif
                    @role('club')
                        @if (auth()->user()->clubProfile && $club->id == auth()->user()->clubProfile->id)
                            <div class="flex justify-center mt-10">
                                <button id="openModalInfoButton"
                                    class="flex flex-row items-center justify-center gap-2 h-9 bg-lime-300 hover:bg-lime-400 py-2 px-4 rounded text-black"
                                    data-club="{{ $club->id }}">
                                    Editar información
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </button>
                            </div>
                        @endif
                    @endrole
                </div>
            </div>
        </div>

        <!-- Modal genérico -->
        <div id="modal" class="hidden relative z-50" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div id="divPrincipal"
                        class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 md:w-1/2 sm:1/4">
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left overflow-y-auto"">
                                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title"></h3>
                                <div id="contenedorDatos" class="flex flex-col justify-center items-center gap-2">
                                </div>
                                <div>
                                    <form id="form" action="#" method="#">
                                        @csrf

                                        <div id="divButtons"
                                            class="justify-center mt-4 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                            <button type="button" id="closeModal"
                                                class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">
                                                Cancelar
                                            </button>
                                            <button type="submit" id="closeModal2"
                                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                                Confirmar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {

        var modal = document.getElementById('modal');
        var divPrincipal = document.getElementById('divPrincipal');
        var modaltitle = document.getElementById('modal-title');
        var contenedorDatos = document.getElementById('contenedorDatos');
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        var form = document.getElementById('form')
        var buttonContainer = document.getElementById('divButtons');
        var closeModal = document.getElementById('closeModal');
        var closeModal2 = document.getElementById('closeModal2');

        /**
         * Limpia el formulario
         */
        function cleanForm() {
            while (contenedorDatos.firstChild) {
                contenedorDatos.removeChild(contenedorDatos.firstChild);
            }

            contenedorDatos.className = "flex flex-col justify-center items-center gap-2";

            // Elimina todos los inputs y labels excepto los botones
            var inputsAndLabels = form.querySelectorAll('input:not([type="submit"]), label, textarea, select');
            inputsAndLabels.forEach(function(element) {
                element.remove();
            });

            modaltitle.textContent = "";
        }

        /**
         * Modal de editar backgroundImage si eres club
         */
        var openModalButton = document.getElementById('openModalButton');
        if (openModalButton) {
            // Abre el modal
            openModalButton.addEventListener('click', function() {
                cleanForm(); // Limpia el formulario cada vez que cualquier modal se abre

                modaltitle.textContent = 'Cambiar foto de fondo';

                var fileInput = document.createElement('input');
                fileInput.setAttribute('type', 'file');
                fileInput.setAttribute('name', 'photo_url');
                fileInput.className =
                    'block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-black hover:file:bg-lime-300 my-10';

                let csrfInput = form.querySelector('input[name="_token"]');
                if (!csrfInput) {
                    csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    form.appendChild(csrfInput);
                }
                // Agrega el div de descripción y el input de archivo al formulario
                form.insertBefore(fileInput, buttonContainer);

                var clubId = this.getAttribute('data-club');
                var formAction = `/clubs/${clubId}/cabecera`;

                form.method = "POST";

                form.setAttribute('enctype', 'multipart/form-data');

                form.action = formAction;

                modal.classList.remove('hidden');
            });
        }

        /**
         * Modal de alquiler de pista
         */
        var slots = document.querySelectorAll('.clickable');
        if (slots) {
            slots.forEach(slot => {
                slot.addEventListener('click', function() {
                    // Obténer detalles como la hora y la pista desde el ID del div
                    var details = this.id.split('-');
                    var courtId = details[1];
                    var courtNumber = details[2];
                    var hour = details[3];
                    var clubId = details[4];

                    cleanForm();

                    modaltitle.textContent = 'Alquilar pista';

                    let csrfInput = form.querySelector('input[name="_token"]');
                    if (!csrfInput) {
                        csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = csrfToken;
                        form.appendChild(csrfInput);
                    }

                    var today = new Date();
                    var dateString = today.getFullYear() + '-' + ('0' + (today.getMonth() + 1))
                        .slice(-2) + '-' + ('0' + today.getDate()).slice(-2);

                    var hourStart = `${dateString} ${hour}:00`;
                    var hourEnd = `${dateString} ${parseInt(hour) + 1}:00`;

                    var startTimeInput = document.createElement('input');
                    startTimeInput.type = 'hidden';
                    startTimeInput.name = 'start_time';
                    startTimeInput.value = hourStart;

                    var endTimeInput = document.createElement('input');
                    endTimeInput.type = 'hidden';
                    endTimeInput.name = 'end_time';
                    endTimeInput.value = hourEnd;

                    form.insertBefore(startTimeInput, buttonContainer);
                    form.insertBefore(endTimeInput, buttonContainer);

                    // Crear y añadir un div para Hora
                    var divStart = document.createElement('div');
                    divStart.textContent =
                        `${hour}:00 a ${parseInt(hour)+1}:00`; // Usa una función para formatear la hora
                    divStart.classList.add('mt-4');
                    contenedorDatos.appendChild(divStart);

                    // Crear el elemento SVG
                    var svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
                    svg.setAttributeNS(null, "viewBox", "0 0 24 24");
                    svg.setAttributeNS(null, "fill", "none");
                    svg.setAttributeNS(null, "stroke-width", "1.5");
                    svg.setAttributeNS(null, "stroke", "currentColor");
                    svg.classList.add(
                        'size-6'
                    ); // Asumiendo que tienes definido este estilo en algún lugar de tu CSS

                    // Crear el path del SVG
                    var path = document.createElementNS("http://www.w3.org/2000/svg", "path");
                    path.setAttributeNS(null, "stroke-linecap", "round");
                    path.setAttributeNS(null, "stroke-linejoin", "round");
                    path.setAttributeNS(null, "d",
                        "M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z");

                    // Añadir el path al SVG
                    svg.appendChild(path);

                    divStart.classList.add('flex', 'flex-row', 'text-md', 'align-items-center',
                        'gap-3');
                    // Añadir el SVG al div de la hora
                    divStart.appendChild(svg);
                    // Finalmente, añadir divStart al contenedor
                    contenedorDatos.appendChild(divStart);

                    // Crear y añadir un div para Pista
                    var divCourt = document.createElement('div');
                    divCourt.textContent = 'Pista ' + courtNumber;
                    divCourt.classList.add('text-md');
                    contenedorDatos.appendChild(divCourt);

                    form.method = "POST"

                    var formAction = `/courts/${clubId}/courts/${courtId}/rent`;

                    form.action = formAction;
                    modal.classList.remove('hidden');
                });
            });
        }


        var createCourtButton = document.getElementById('createCourtButton');
        if (createCourtButton) {
            /*
             * Modal de creación de pista
             */
            createCourtButton.addEventListener('click', function() {

                cleanForm(); // Limpia el formulario cada vez que cualquier modal se abre

                contenedorDatos.classList.add('mt-4')

                modaltitle.textContent = 'Crear una nueva pista';

                // Crear y añadir input para número de pista
                var numberLabel = document.createElement('label');
                numberLabel.textContent = 'Número de pista';
                numberLabel.setAttribute('for', 'number');
                numberLabel.className = 'block text-sm font-medium text-gray-700';

                var numberInput = document.createElement('input');
                numberInput.type = 'number';
                numberInput.name = 'number';
                numberInput.id = 'number';
                numberInput.placeholder = 'Número de pista';
                numberInput.className =
                    'mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:border-lime-300 focus:ring-lime-300';

                // Crear y añadir select para tipo de pista
                var typeLabel = document.createElement('label');
                typeLabel.textContent = 'Tipo de pista';
                typeLabel.setAttribute('for', 'type');
                typeLabel.className = 'block text-sm font-medium text-gray-700 mt-4';

                var select = document.createElement('select');
                select.name = 'type';
                select.id = 'type';
                select.className =
                    'mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm focus:border-lime-300 focus:ring-lime-300';

                var option1 = document.createElement('option');
                option1.value = 'Muro';
                option1.textContent = 'Muro';
                select.appendChild(option1);

                var option2 = document.createElement('option');
                option2.value = 'Cristal';
                option2.textContent = 'Cristal';
                select.appendChild(option2);

                let csrfInput = form.querySelector('input[name="_token"]');
                if (!csrfInput) {
                    csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    form.appendChild(csrfInput);
                }

                buttonContainer.classList.add('mt-4');

                form.insertBefore(numberLabel, buttonContainer);
                form.insertBefore(numberInput, buttonContainer);
                form.insertBefore(typeLabel, buttonContainer);
                form.insertBefore(select, buttonContainer);

                var clubId = this.getAttribute('data-club');
                var formAction = `/courts/${clubId}/store`;

                form.method = "POST"

                form.action = formAction;

                modal.classList.remove('hidden');
            });
        }

        var createClassButton = document.getElementById('createClassButton');
        if (createClassButton) {
            /**
             * Modal de creación de clase
             */
            createClassButton.addEventListener('click', function() {
                cleanForm(); // Suponiendo que esta función limpia adecuadamente el formulario

                contenedorDatos.classList.add('mt-4')
                modaltitle.textContent = 'Crear una nueva clase';

                let csrfInput = form.querySelector('input[name="_token"]');
                if (!csrfInput) {
                    csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content');
                    form.appendChild(csrfInput);
                }

                var courts = JSON.parse(this.getAttribute('data-club-courts'));

                // Crear y añadir select para la pista
                var courtLabel = document.createElement('label');
                courtLabel.textContent = 'Pista';
                courtLabel.className = 'block text-sm font-medium text-gray-700 mt-3';

                var courtSelect = document.createElement('select');
                courtSelect.name = 'court_id';
                courtSelect.id = 'court';
                courtSelect.className =
                    'block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm focus:border-lime-300 focus:ring-lime-300';
                courts.forEach(function(court) {
                    var option = document.createElement('option');
                    option.value = court.id;
                    option.textContent = 'Pista ' + court.number;
                    courtSelect.appendChild(option);
                });

                // Crear y añadir select para el nivel
                var levelLabel = document.createElement('label');
                levelLabel.textContent = 'Nivel';
                levelLabel.className = 'block text-sm font-medium text-gray-700 mt-3';


                var levelSelect = document.createElement('select');
                levelSelect.name = 'level';
                levelSelect.id = 'level';
                levelSelect.className =
                    'block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm focus:border-lime-300 focus:ring-lime-300';
                ['Pro', 'Medio', 'Principiante'].forEach(function(level) {
                    var option = document.createElement('option');
                    option.value = level;
                    option.textContent = level;
                    levelSelect.appendChild(option);
                });

                // Crear y añadir input para la fecha
                var dateLabel = document.createElement('label');
                dateLabel.textContent = 'Fecha';
                dateLabel.className = 'block text-sm font-medium text-gray-700 mt-3';

                var dateInput = document.createElement('input');
                dateInput.type = 'date';
                dateInput.name = 'date';
                dateInput.id = 'date';
                dateInput.className =
                    'block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm focus:border-lime-300 focus:ring-lime-300';

                // Crear y añadir input para la hora de inicio
                var startTimeLabel = document.createElement('label');
                startTimeLabel.textContent = 'Hora de inicio';
                startTimeLabel.className = 'block text-sm font-medium text-gray-700 mt-3';

                var startTimeInput = document.createElement('input');
                startTimeInput.type = 'time';
                startTimeInput.name = 'start';
                startTimeInput.id = 'start';
                startTimeInput.className =
                    'block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm focus:border-lime-300 focus:ring-lime-300';

                // Crear y añadir input para la hora de fin
                var endTimeLabel = document.createElement('label');
                endTimeLabel.textContent = 'Hora de fin';
                endTimeLabel.className = 'block text-sm font-medium text-gray-700 mt-3';

                var endTimeInput = document.createElement('input');
                endTimeInput.type = 'time';
                endTimeInput.name = 'end';
                endTimeInput.id = 'end';
                endTimeInput.className =
                    'block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm focus:border-lime-300 focus:ring-lime-300';

                // Crear y añadir input hidden para la hora de inicio formateada
                var startTFormatimeInput = document.createElement('input');
                startTFormatimeInput.type = 'hidden';
                startTFormatimeInput.name = 'start_time';
                startTFormatimeInput.id = 'formatted_start_time';

                // Crear y añadir input hidden para la hora de fin formateada
                var endFormatTimeInput = document.createElement('input');
                endFormatTimeInput.type = 'hidden';
                endFormatTimeInput.name = 'end_time';
                endFormatTimeInput.id = 'formatted_end_time';

                form.insertBefore(startTFormatimeInput, buttonContainer);
                form.insertBefore(endFormatTimeInput, buttonContainer);

                form.insertBefore(courtLabel, buttonContainer);
                form.insertBefore(courtSelect, buttonContainer);

                form.insertBefore(levelLabel, buttonContainer);
                form.insertBefore(levelSelect, buttonContainer);
                form.insertBefore(dateLabel, buttonContainer);
                form.insertBefore(dateInput, buttonContainer);

                form.insertBefore(startTimeLabel, buttonContainer);
                form.insertBefore(startTimeInput, buttonContainer);
                form.insertBefore(endTimeLabel, buttonContainer);
                form.insertBefore(endTimeInput, buttonContainer);

                form.onsubmit = function(event) {
                    event.preventDefault(); // Prevenir el envío automático

                    var date = document.getElementById('date').value;
                    var startTime = document.getElementById('start').value;
                    var endTime = document.getElementById('end').value;

                    document.getElementById('formatted_start_time').value = date + ' ' + startTime;
                    document.getElementById('formatted_end_time').value = date + ' ' + endTime;

                    form.submit(); // Envía el formulario después de la configuración
                };

                // Configurar acción del formulario y mostrar el modal
                var clubId = this.getAttribute('data-club');
                var formAction = `/club-profile/${clubId}/classes`;

                form.method = "POST"

                form.action = formAction;
                modal.classList.remove('hidden');
            });
        }

        /**
         * Modal de modificación de clase
         */
        var classEditButton = document.querySelectorAll('.classEditButton');
        if (classEditButton.length > 0) {


            classEditButton.forEach(button => {
                button.addEventListener('click', function() {
                    cleanForm();
                    contenedorDatos.classList.add('mt-4');
                    modaltitle.textContent = 'Editar clase';

                    let csrfInput = form.querySelector('input[name="_token"]');
                    if (!csrfInput) {
                        csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content');
                        form.appendChild(csrfInput);
                    }

                    let methodInput = form.querySelector('input[name="_method"]');
                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'PUT';
                        form.appendChild(methodInput);
                    }

                    var courts = JSON.parse(this.getAttribute('data-club-courts'));
                    var currentCourtId = this.getAttribute('data-class-court');
                    var currentLevel = this.getAttribute('data-class-level');
                    var currentDate = this.getAttribute('data-class-start').split(' ')[0];
                    var currentStartTime = this.getAttribute('data-class-start').split(' ')[1];
                    var currentEndTime = this.getAttribute('data-class-end').split(' ')[1];

                    // Setup court select
                    var courtLabel = document.createElement('label');
                    courtLabel.textContent = 'Pista';
                    courtLabel.className = 'block text-sm font-medium text-gray-700 mt-3';
                    var courtSelect = document.createElement('select');
                    courtSelect.name = 'court_id';
                    courtSelect.id = 'courtEdit';
                    courtSelect.className =
                        'block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm focus:border-lime-300 focus:ring-lime-300';
                    courts.forEach(function(court) {
                        var option = document.createElement('option');
                        option.value = court.id;
                        option.textContent = 'Pista ' + court.number;
                        if (court.id == currentCourtId) option.selected = true;
                        courtSelect.appendChild(option);
                    });

                    // Setup level select
                    var levelLabel = document.createElement('label');
                    levelLabel.textContent = 'Nivel';
                    levelLabel.className = 'block text-sm font-medium text-gray-700 mt-3';
                    var levelSelect = document.createElement('select');
                    levelSelect.name = 'level';
                    levelSelect.id = 'levelEdit';
                    levelSelect.className =
                        'block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm focus:border-lime-300 focus:ring-lime-300';
                    ['Pro', 'Medio', 'Principiante'].forEach(function(level) {
                        var option = document.createElement('option');
                        option.value = level;
                        option.textContent = level;
                        if (level === currentLevel) option.selected = true;
                        levelSelect.appendChild(option);
                    });

                    // Setup date input
                    var dateLabel = document.createElement('label');
                    dateLabel.textContent = 'Fecha';
                    dateLabel.className = 'block text-sm font-medium text-gray-700 mt-3';
                    var dateInput = document.createElement('input');
                    dateInput.type = 'date';
                    dateInput.name = 'date';
                    dateInput.id = 'dateEdit';
                    dateInput.className =
                        'block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm focus:border-lime-300 focus:ring-lime-300';
                    dateInput.value = currentDate;

                    // Setup start time input
                    var startTimeLabel = document.createElement('label');
                    startTimeLabel.textContent = 'Hora de inicio';
                    startTimeLabel.className = 'block text-sm font-medium text-gray-700 mt-3';
                    var startTimeInput = document.createElement('input');
                    startTimeInput.type = 'time';
                    startTimeInput.name = 'start';
                    startTimeInput.id = 'startEdit';
                    startTimeInput.className =
                        'block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm focus:border-lime-300 focus:ring-lime-300';
                    startTimeInput.value = currentStartTime;

                    // Setup end time input
                    var endTimeLabel = document.createElement('label');
                    endTimeLabel.textContent = 'Hora de fin';
                    endTimeLabel.className = 'block text-sm font-medium text-gray-700 mt-3';
                    var endTimeInput = document.createElement('input');
                    endTimeInput.type = 'time';
                    endTimeInput.name = 'end';
                    endTimeInput.id = 'endEdit';
                    endTimeInput.className =
                        'block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm focus:border-lime-300 focus:ring-lime-300';
                    endTimeInput.value = currentEndTime;

                    // Crear y añadir input hidden para la hora de inicio formateada
                    var startTFormatimeInput = document.createElement('input');
                    startTFormatimeInput.type = 'hidden';
                    startTFormatimeInput.name = 'start_time';
                    startTFormatimeInput.id = 'formatted_start_time';

                    // Crear y añadir input hidden para la hora de fin formateada
                    var endFormatTimeInput = document.createElement('input');
                    endFormatTimeInput.type = 'hidden';
                    endFormatTimeInput.name = 'end_time';
                    endFormatTimeInput.id = 'formatted_end_time';

                    form.insertBefore(startTFormatimeInput, buttonContainer);
                    form.insertBefore(endFormatTimeInput, buttonContainer);
                    form.insertBefore(courtLabel, buttonContainer);
                    form.insertBefore(courtSelect, buttonContainer);
                    form.insertBefore(levelLabel, buttonContainer);
                    form.insertBefore(levelSelect, buttonContainer);
                    form.insertBefore(dateLabel, buttonContainer);
                    form.insertBefore(dateInput, buttonContainer);
                    form.insertBefore(startTimeLabel, buttonContainer);
                    form.insertBefore(startTimeInput, buttonContainer);
                    form.insertBefore(endTimeLabel, buttonContainer);
                    form.insertBefore(endTimeInput, buttonContainer);

                    form.onsubmit = function(event) {
                        event.preventDefault(); // Evitar el envío automático
                        var date = document.getElementById('dateEdit').value;
                        var startTime = document.getElementById('startEdit').value.split(
                            ':');
                        var realStartTime = startTime[0] + ":" + startTime[1];
                        var endTime = document.getElementById('endEdit').value.split(':');
                        var realEndTime = endTime[0] + ":" + endTime[1];

                        document.getElementById('formatted_start_time').value = date + ' ' +
                            realStartTime;
                        document.getElementById('formatted_end_time').value = date + ' ' +
                            realEndTime;

                        form.submit();
                    };

                    var classId = this.getAttribute('data-class-id');
                    var clubId = this.getAttribute('data-club');
                    var formAction = `/club-profile/${clubId}/classes/${classId}`;
                    form.method = "POST";
                    form.action = formAction;

                    modal.classList.remove('hidden');
                });
            });
        }

        /**
         * Modal de eliminación de clase
         */
        var classDeleteButton = document.querySelectorAll('.classDeleteButton');
        if (classDeleteButton.length > 0) {


            classDeleteButton.forEach(button => {
                button.addEventListener('click', function() {
                    cleanForm();

                    contenedorDatos.classList.add('mt-4');
                    modaltitle.textContent = '¿Desea eliminar la clase?';

                    let csrfInput = form.querySelector('input[name="_token"]');
                    if (!csrfInput) {
                        csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content');
                        form.appendChild(csrfInput);
                    }

                    var classLevel = this.getAttribute('data-class-level');
                    var classCourt = this.getAttribute('data-class-court');
                    var classDate = this.getAttribute('data-class-date');
                    var classStart = this.getAttribute('data-class-start');
                    var classEnd = this.getAttribute('data-class-end');

                    // Crear y añadir un div para Fecha
                    var divDate = document.createElement('div');
                    divDate.textContent = classDate;
                    divDate.setAttribute('data-class-date', classDate);
                    divDate.classList.add('text-md');
                    contenedorDatos.appendChild(divDate);

                    // Crear y añadir un div para Hora
                    var divStart = document.createElement('div');
                    divStart.textContent = classStart + " a " +
                        classEnd; // Asumiendo que classStart y classEnd están definidos
                    divStart.setAttribute('data-class-start', classStart);

                    // Crear el elemento SVG
                    var svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
                    svg.setAttributeNS(null, "viewBox", "0 0 24 24");
                    svg.setAttributeNS(null, "fill", "none");
                    svg.setAttributeNS(null, "stroke-width", "1.5");
                    svg.setAttributeNS(null, "stroke", "currentColor");
                    svg.classList.add(
                        'size-6'
                    ); // Asumiendo que tienes definido este estilo en algún lugar de tu CSS

                    // Crear el path del SVG
                    var path = document.createElementNS("http://www.w3.org/2000/svg", "path");
                    path.setAttributeNS(null, "stroke-linecap", "round");
                    path.setAttributeNS(null, "stroke-linejoin", "round");
                    path.setAttributeNS(null, "d",
                        "M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z");

                    // Añadir el path al SVG
                    svg.appendChild(path);

                    divStart.classList.add('flex', 'flex-row', 'text-md', 'align-items-center',
                        'gap-3');
                    // Añadir el SVG al div de la hora
                    divStart.appendChild(svg);
                    // Finalmente, añadir divStart al contenedor
                    contenedorDatos.appendChild(divStart);

                    // Crear y añadir un div para Pista
                    var divCourt = document.createElement('div');
                    divCourt.textContent = 'Pista ' + classCourt;
                    divCourt.setAttribute('data-class-court', classCourt);
                    divCourt.classList.add('text-md');
                    contenedorDatos.appendChild(divCourt);

                    let methodInput = form.querySelector('input[name="_method"]');
                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'DELETE';
                        form.appendChild(methodInput);
                    }

                    var classId = this.getAttribute('data-class-id');
                    var clubId = this.getAttribute('data-club');
                    var formAction = `/club-profile/${clubId}/classes/${classId}`;
                    form.method = "POST";
                    form.action = formAction;

                    modal.classList.remove('hidden');
                });
            });
        }

        /**
         * Modal de creación de torneo
         */
        var createTournamentButton = document.getElementById('createTournamentButton');
        if (createTournamentButton) {
            createTournamentButton.addEventListener('click', function() {

                cleanForm();

                contenedorDatos.classList.add('mt-4')
                modaltitle.textContent = 'Crear un nuevo torneo';

                let csrfInput = form.querySelector('input[name="_token"]');
                if (!csrfInput) {
                    csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content');
                    form.appendChild(csrfInput);
                }

                // Crear y añadir label e input para el nombre del torneo
                var nameLabel = document.createElement('label');
                nameLabel.textContent = 'Nombre del torneo';
                nameLabel.className = 'block text-sm font-medium text-gray-700 mt-4';

                var nameInput = document.createElement('input');
                nameInput.type = 'text';
                nameInput.name = 'name';
                nameInput.placeholder = 'Nombre del torneo';
                nameInput.className =
                    'block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm focus:border-lime-300 focus:ring-lime-300';

                // Crear y añadir label y select para el estado del torneo
                var statusLabel = document.createElement('label');
                statusLabel.textContent = 'Estado del torneo';
                statusLabel.className = 'block text-sm font-medium text-gray-700 mt-4';

                var statusSelect = document.createElement('select');
                statusSelect.name = 'status';
                statusSelect.className =
                    'block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm focus:border-lime-300 focus:ring-lime-300';
                ['Abierto', 'En progreso', 'Cerrado'].forEach(function(status) {
                    var option = document.createElement('option');
                    option.value = status;
                    option.textContent = status;
                    statusSelect.appendChild(option);
                });

                // Crear y añadir label y textarea para la descripción
                var descriptionLabel = document.createElement('label');
                descriptionLabel.textContent = 'Descripción del torneo';
                descriptionLabel.className = 'block text-sm font-medium text-gray-700 mt-4';

                var descriptionTextarea = document.createElement('textarea');
                descriptionTextarea.name = 'description';
                descriptionTextarea.placeholder = 'Descripción del torneo';
                descriptionTextarea.className =
                    'block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm focus:border-lime-300 focus:ring-lime-300';

                // Crear y añadir label e input para la URL de la foto
                var photoLabel = document.createElement('label');
                photoLabel.textContent = 'Foto del torneo';
                photoLabel.className = 'block text-sm font-medium text-gray-700 mt-4';

                var fileInput = document.createElement('input');
                fileInput.type = 'file';
                fileInput.name = 'photo_url';
                fileInput.className =
                    'block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-black hover:file:bg-lime-300 focus:border-lime-300 focus:ring-lime-300';

                // Crear y añadir label e input para la fecha de inicio
                var startDateLabel = document.createElement('label');
                startDateLabel.textContent = 'Fecha de inicio';
                startDateLabel.className = 'block text-sm font-medium text-gray-700 mt-4';

                var startDateInput = document.createElement('input');
                startDateInput.type = 'date';
                startDateInput.name = 'start_date';
                startDateInput.className =
                    'block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm focus:border-lime-300 focus:ring-lime-300';

                // Crear y añadir label e input para la fecha de fin
                var endDateLabel = document.createElement('label');
                endDateLabel.textContent = 'Fecha de fin';
                endDateLabel.className = 'block text-sm font-medium text-gray-700 mt-4';

                var endDateInput = document.createElement('input');
                endDateInput.type = 'date';
                endDateInput.name = 'end_date';
                endDateInput.className =
                    'block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm focus:border-lime-300 focus:ring-lime-300';

                form.insertBefore(nameLabel, buttonContainer);
                form.insertBefore(nameInput, buttonContainer);
                form.insertBefore(statusLabel, buttonContainer);
                form.insertBefore(statusSelect, buttonContainer);
                form.insertBefore(descriptionLabel, buttonContainer);
                form.insertBefore(descriptionTextarea, buttonContainer);
                form.insertBefore(photoLabel, buttonContainer);
                form.insertBefore(fileInput, buttonContainer);
                form.insertBefore(startDateLabel, buttonContainer);
                form.insertBefore(startDateInput, buttonContainer);
                form.insertBefore(endDateLabel, buttonContainer);
                form.insertBefore(endDateInput, buttonContainer);

                form.setAttribute('enctype', 'multipart/form-data');

                var clubId = this.getAttribute('data-club');
                var formAction = `/tournaments/${clubId}/store`;

                form.method = "POST"

                form.action = formAction;

                modal.classList.remove('hidden');
            });
        }

        /**
         * Modal para editar torneo
         */
        var tournamentEditButton = document.querySelectorAll('.tournamentEditButton');
        if (tournamentEditButton.length > 0) {

            tournamentEditButton.forEach(button => {
                button.addEventListener('click', function() {
                    cleanForm();
                    contenedorDatos.classList.add('mt-4');
                    modaltitle.textContent = 'Editar clase';

                    let csrfInput = form.querySelector('input[name="_token"]');
                    if (!csrfInput) {
                        csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content');
                        form.appendChild(csrfInput);
                    }

                    let methodInput = form.querySelector('input[name="_method"]');
                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'PUT';
                        form.appendChild(methodInput);
                    }

                    // Crear y añadir label e input para el nombre del torneo
                    var nameLabel = document.createElement('label');
                    nameLabel.textContent = 'Nombre del torneo';
                    nameLabel.className = 'block text-sm font-medium text-gray-700 mt-4';

                    var nameInput = document.createElement('input');
                    nameInput.type = 'text';
                    nameInput.name = 'name';
                    nameInput.placeholder = 'Nombre del torneo';
                    nameInput.className =
                        'block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm focus:border-lime-300 focus:ring-lime-300';

                    // Crear y añadir label y select para el estado del torneo
                    var statusLabel = document.createElement('label');
                    statusLabel.textContent = 'Estado del torneo';
                    statusLabel.className = 'block text-sm font-medium text-gray-700 mt-4';

                    var statusSelect = document.createElement('select');
                    statusSelect.name = 'status';
                    statusSelect.className =
                        'block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm focus:border-lime-300 focus:ring-lime-300';
                    ['Abierto', 'En progreso', 'Cerrado'].forEach(function(status) {
                        var option = document.createElement('option');
                        option.value = status;
                        option.textContent = status;
                        statusSelect.appendChild(option);
                    });

                    // Crear y añadir label y textarea para la descripción
                    var descriptionLabel = document.createElement('label');
                    descriptionLabel.textContent = 'Descripción del torneo';
                    descriptionLabel.className = 'block text-sm font-medium text-gray-700 mt-4';

                    var descriptionTextarea = document.createElement('textarea');
                    descriptionTextarea.name = 'description';
                    descriptionTextarea.placeholder = 'Descripción del torneo';
                    descriptionTextarea.className =
                        'block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm focus:border-lime-300 focus:ring-lime-300';

                    // Crear y añadir label e input para la URL de la foto
                    var photoLabel = document.createElement('label');
                    photoLabel.textContent = 'Foto del torneo';
                    photoLabel.className = 'block text-sm font-medium text-gray-700 mt-4';

                    var fileInput = document.createElement('input');
                    fileInput.type = 'file';
                    fileInput.name = 'photo_url';
                    fileInput.className =
                        'block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-black hover:file:bg-lime-300';

                    // Crear y añadir label e input para la fecha de inicio
                    var startDateLabel = document.createElement('label');
                    startDateLabel.textContent = 'Fecha de inicio';
                    startDateLabel.className = 'block text-sm font-medium text-gray-700 mt-4';

                    var startDateInput = document.createElement('input');
                    startDateInput.type = 'date';
                    startDateInput.name = 'start_date';
                    startDateInput.className =
                        'block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm focus:border-lime-300 focus:ring-lime-300';

                    // Crear y añadir label e input para la fecha de fin
                    var endDateLabel = document.createElement('label');
                    endDateLabel.textContent = 'Fecha de fin';
                    endDateLabel.className = 'block text-sm font-medium text-gray-700 mt-4';

                    var endDateInput = document.createElement('input');
                    endDateInput.type = 'date';
                    endDateInput.name = 'end_date';
                    endDateInput.className =
                        'block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm focus:border-lime-300 focus:ring-lime-300';

                    // Obtener valores actuales del torneo desde el botón
                    var currentName = button.getAttribute('data-tournament-name');
                    var currentStatus = button.getAttribute('data-tournament-status');
                    var currentDescription = button.getAttribute('data-tournament-description');
                    var currentPhoto = button.getAttribute('data-tournament-photo');

                    var currentStartDate = button.getAttribute('data-tournament-start').split(
                            '/')
                        .join('-');
                    var currentEndDate = button.getAttribute('data-tournament-end').split('/')
                        .join(
                            '-');

                    // Establecer los valores de los campos del formulario
                    nameInput.value = currentName;
                    descriptionTextarea.value = currentDescription;
                    startDateInput.value = currentStartDate;
                    endDateInput.value = currentEndDate;

                    // Seleccionar el estado actual en el select de estado
                    Array.from(statusSelect.options).forEach(option => {
                        if (option.value === currentStatus) option.selected = true;
                    });

                    form.insertBefore(nameLabel, buttonContainer);
                    form.insertBefore(nameInput, buttonContainer);
                    form.insertBefore(statusLabel, buttonContainer);
                    form.insertBefore(statusSelect, buttonContainer);
                    form.insertBefore(descriptionLabel, buttonContainer);
                    form.insertBefore(descriptionTextarea, buttonContainer);
                    form.insertBefore(photoLabel, buttonContainer);
                    form.insertBefore(fileInput, buttonContainer);
                    form.insertBefore(startDateLabel, buttonContainer);
                    form.insertBefore(startDateInput, buttonContainer);
                    form.insertBefore(endDateLabel, buttonContainer);
                    form.insertBefore(endDateInput, buttonContainer);

                    var tournamentId = this.getAttribute('data-tournament-id');
                    var clubId = this.getAttribute('data-club');
                    var formAction = `/tournaments/${clubId}/tournament/${tournamentId}`;
                    form.method = "POST";
                    form.action = formAction;

                    modal.classList.remove('hidden');
                });
            });
        }

        /**
         * Modal para eliminar un torneo
         */
        var tournamentDeleteButton = document.querySelectorAll('.tournamentDeleteButton');
        if (tournamentDeleteButton.length > 0) {

            tournamentDeleteButton.forEach(button => {
                button.addEventListener('click', function() {
                    cleanForm();

                    contenedorDatos.classList.add('mt-4');
                    modaltitle.textContent = '¿Desea eliminar el torneo?';

                    let csrfInput = form.querySelector('input[name="_token"]');
                    if (!csrfInput) {
                        csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content');
                        form.appendChild(csrfInput);
                    }

                    let methodInput = form.querySelector('input[name="_method"]');
                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'DELETE';
                        form.appendChild(methodInput);
                    }

                    var currentName = button.getAttribute('data-tournament-name');
                    var currentStartDate = button.getAttribute('data-tournament-start');
                    var currentEndDate = button.getAttribute('data-tournament-end');

                    var divName = document.createElement('div');
                    divName.textContent = currentName;
                    divName.classList.add('text-md');
                    contenedorDatos.appendChild(divName);

                    var divDate = document.createElement('div');
                    divDate.textContent = currentStartDate + ' al ' + currentEndDate;
                    divDate.classList.add('text-md');
                    contenedorDatos.appendChild(divDate);

                    var tournamentId = this.getAttribute('data-tournament-id');
                    var clubId = this.getAttribute('data-club');
                    var formAction = `/tournaments/${clubId}/tournament/${tournamentId}`;
                    form.method = "POST";
                    form.action = formAction;

                    modal.classList.remove('hidden');
                });
            });
        }

        /**
         * Modal de registro en clase
         */
        var openclassRegisterButton = document.querySelectorAll('.openclassRegisterButton');
        if (openclassRegisterButton.length > 0) {

            openclassRegisterButton.forEach(button => {
                button.addEventListener('click', function() {

                    cleanForm(); // Limpia el formulario cada vez que cualquier modal se abre

                    divPrincipal.classList.add('sm:max-w-xs');
                    contenedorDatos.classList.add('h-36');

                    var classLevel = this.getAttribute('data-class-level');
                    var classCourt = this.getAttribute('data-class-court');
                    var classDate = this.getAttribute('data-class-date');
                    var classStart = this.getAttribute('data-class-start');
                    var classEnd = this.getAttribute('data-class-end');

                    //Nombre nombre de club
                    modaltitle.textContent = 'Clase de nivel ' + classLevel;

                    // Crear y añadir un div para Fecha
                    var divDate = document.createElement('div');
                    divDate.textContent = classDate;
                    divDate.setAttribute('data-class-date', classDate);
                    divDate.classList.add('text-md');
                    contenedorDatos.appendChild(divDate);

                    // Crear y añadir un div para Hora
                    var divStart = document.createElement('div');
                    divStart.textContent = classStart + " a " +
                        classEnd; // Asumiendo que classStart y classEnd están definidos
                    divStart.setAttribute('data-class-start', classStart);

                    // Crear el elemento SVG
                    var svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
                    svg.setAttributeNS(null, "viewBox", "0 0 24 24");
                    svg.setAttributeNS(null, "fill", "none");
                    svg.setAttributeNS(null, "stroke-width", "1.5");
                    svg.setAttributeNS(null, "stroke", "currentColor");
                    svg.classList.add(
                        'size-6'
                    ); // Asumiendo que tienes definido este estilo en algún lugar de tu CSS

                    // Crear el path del SVG
                    var path = document.createElementNS("http://www.w3.org/2000/svg", "path");
                    path.setAttributeNS(null, "stroke-linecap", "round");
                    path.setAttributeNS(null, "stroke-linejoin", "round");
                    path.setAttributeNS(null, "d",
                        "M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z");

                    // Añadir el path al SVG
                    svg.appendChild(path);

                    divStart.classList.add('flex', 'flex-row', 'text-md', 'align-items-center',
                        'gap-3');
                    // Añadir el SVG al div de la hora
                    divStart.appendChild(svg);
                    // Finalmente, añadir divStart al contenedor
                    contenedorDatos.appendChild(divStart);

                    // Crear y añadir un div para Pista
                    var divCourt = document.createElement('div');
                    divCourt.textContent = 'Pista ' + classCourt;
                    divCourt.setAttribute('data-class-court', classCourt);
                    divCourt.classList.add('text-md');
                    contenedorDatos.appendChild(divCourt);

                    let csrfInput = form.querySelector('input[name="_token"]');
                    if (!csrfInput) {
                        csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = csrfToken;
                        form.appendChild(csrfInput);
                    }

                    var classId = this.getAttribute('data-class');
                    var clubId = this.getAttribute('data-club');
                    var formAction = `/club-profile/${clubId}/classes/${classId}/register`;

                    form.method = "POST"

                    form.action = formAction;
                    modal.classList.remove('hidden');
                });
            });
        }

        /**
         * Modal de editar información del club
         */
        var openModalInfoButton = document.getElementById('openModalInfoButton');
        if (openModalInfoButton) {
            openModalInfoButton.addEventListener('click', function() {

                cleanForm();

                modaltitle.textContent = 'Editar información';

                // Crea un contenedor div para el grupo de la descripción
                var descriptionDiv = document.createElement('div');
                descriptionDiv.className = 'flex flex-col gap-1 mt-4';

                // Crea la etiqueta label para el área de texto
                var label = document.createElement('label');
                label.setAttribute('for', 'description');
                label.textContent = 'Descripción';

                // Crea el área de texto para la descripción
                var textarea = document.createElement('textarea');
                textarea.setAttribute('name', 'description');
                textarea.setAttribute('id', 'description');
                textarea.setAttribute('cols', '40');
                textarea.setAttribute('rows', '3');
                textarea.className =
                    'block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm focus:border-lime-300 focus:ring-lime-300';

                // Agrega la etiqueta y el área de texto al div de descripción
                descriptionDiv.appendChild(label);
                descriptionDiv.appendChild(textarea);

                // Crea el input para cargar archivos
                var fileInput = document.createElement('input');
                fileInput.setAttribute('type', 'file');
                fileInput.setAttribute('name', 'photo_url');
                fileInput.className =
                    'block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-black hover:file:bg-lime-300 my-10';


                let csrfInput = form.querySelector('input[name="_token"]');
                if (!csrfInput) {
                    csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    form.appendChild(csrfInput);
                }

                // Agrega el div de descripción y el input de archivo al formulario
                form.insertBefore(descriptionDiv, buttonContainer);
                form.insertBefore(fileInput, buttonContainer);

                var clubId = this.getAttribute('data-club');
                var formAction = `/clubs/${clubId}/edit-details`;

                form.method = "POST"

                form.setAttribute('enctype', 'multipart/form-data');

                form.action = formAction;

                modal.classList.remove('hidden');
            });
        }

        // Cierra el modal
        closeModal.addEventListener('click', function() {
            modal.classList.add('hidden');
        });

        // Cierra el modal tras darle a confirmar
        closeModal2.addEventListener('click', function() {
            modal.classList.add('hidden');
        });

    });
</script>
