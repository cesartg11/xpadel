@extends('layouts.plantilla')
@section('titulo', 'Clubs')
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
                            <button id="openModalButton" class="h-10 bg-lime-300 hover:bg-lime-400 text-black py-2 px-4 rounded"
                                data-club="{{ $club->id }}">
                                Editar foto
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
                                class="h-10 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                data-club="{{ $club->id }}">
                                Editar foto
                            </button>
                        </div>
                    @endif
                @endrole
            </div>
        @endif

        <div class="mx-20">

            <!-- Horarios de las pistas -->
            <div class="my-10">
                <h2 class="text-xl font-semibold mb-4">Pistas</h2>
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
                                        $currentHour = \Carbon\Carbon::now()
                                            ->setTime($hour, 0, 0)
                                            ->format('Y-m-d H:i:s');

                                        // Verifica si esta hora específica está disponible según las pistas
                                        $available = $pistas[$court->id][$currentHour] ?? false;
                                    @endphp
                                    <div
                                        class="flex-1 {{ $available ? 'bg-white' : 'bg-gray-300' }} border hover:bg-lime-200">
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
                                    <div class="w-6 h-6 bg-lime-200 rounded"></div>
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
                <h2 class="text-xl font-semibold mb-4">Clases</h2>
                <div class="shadow">
                    <!-- Encabezados -->
                    <div class="flex justify-between bg-gray-200 p-4 p-lg font-bold rounded-t">
                        <div class="flex-1 text-center">Nivel</div>
                        <div class="flex-1 text-center">Pista</div>
                        <div class="flex-1 text-center">Fecha</div>
                        <div class="flex-1 text-center">Hora</div>
                        @auth
                            <div class="flex-1 text-center">Acción</div>
                        @endauth
                    </div>

                    <!-- Datos de las clases -->
                    @forelse ($club->classes as $class)
                        <div class="flex justify-between items-center border p-4 p-lg text-center">
                            <div class="flex-1">{{ $class->level }}</div>
                            <div class="flex-1">Pista {{ $class->court->number }}</div>
                            <div class="flex-1">{{ \Carbon\Carbon::parse($class->start_time)->format('d/m/Y') }}</div>
                            <div class="flex-1">{{ \Carbon\Carbon::parse($class->start_time)->format('H:i') }} a
                                {{ \Carbon\Carbon::parse($class->end_time)->format('H:i') }}</div>
                            @auth
                                <div class="flex-1">
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
                                </div>
                            @endauth
                        </div>
                    @empty
                        <p class="text-center py-4">Este club no tiene clases disponibles.</p>
                    @endforelse
                </div>
            </div>

            <!-- Torneos -->
            <div class="my-10">
                <h2 class="text-xl font-semibold mb-4">Torneos</h2>
                <div class="shadow">
                    <!-- Encabezados -->
                    <div class="flex justify-between bg-gray-200 p-4 p-lg font-bold rounded-t">
                        <div class="flex-1 text-center">Torneo</div>
                        <div class="flex-1 text-center">Estado</div>
                        <div class="flex-1 text-center">Fecha inicio</div>
                        <div class="flex-1 text-center">Fecha fin</div>
                        @auth
                            <div class="flex-1 text-center">Acción</div>
                        @endauth
                    </div>

                    <!-- Datos de los torneos -->
                    @forelse ($club->tournaments as $tournament)
                        <div class="flex justify-between items-center border p-4 p-lg text-center">
                            <div class="flex-1">{{ $tournament->name }}</div>
                            <div class="flex-1">{{ $tournament->status }}</div>
                            <div class="flex-1">{{ \Carbon\Carbon::parse($tournament->start_time)->format('d/m/Y') }}</div>
                            <div class="flex-1">{{ \Carbon\Carbon::parse($tournament->end_time)->format('d/m/Y') }}</div>
                            @auth
                                <div class="flex-1">
                                    <a href="{{ route('clubs.index') }}"
                                        class="inline-block px-4 py-2 text-black rounded bg-lime-300 hover:bg-lime-400 text-center">
                                        Ver
                                    </a>
                                </div>
                            @endauth
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
                                    class="h-9 bg-lime-300 hover:bg-lime-400 py-2 px-4 rounded text-black"
                                    data-club="{{ $club->id }}">
                                    Editar información
                                </button>
                            </div>
                        @endif
                    @endrole
                </div>
            </div>
        </div>

        {{-- Modal genérico --}}
        <div id="modal" class="hidden relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div id="divPrincipal"
                        class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full">
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title"></h3>
                                <div id="contenedorDatos" class="flex flex-col justify-center items-center gap-2">
                                </div>
                                <div class="mt-4">
                                    <form id="form" action="#" method="post" enctype="multipart/form-data">
                                        @csrf

                                        <div id="divButtons" class="px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
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
        var form = document.getElementById('form')
        var buttonContainer = document.getElementById('divButtons');
        var closeModal = document.getElementById('closeModal');
        var closeModal2 = document.getElementById('closeModal2');

        /**
         * Modal de editar backgroundImage si eres club
         */
        var openModalButton = document.getElementById('openModalButton');
        if (openModalButton) {
            // Abre el modal
            openModalButton.addEventListener('click', function() {

                modaltitle.textContent = "";

                var existingInputs = form.querySelectorAll('input:not([type="submit"]), textarea');
                existingInputs.forEach(function(input) {
                    input.remove(); // Elimina cada campo que no sea un botón de submit
                });

                divPrincipal.className = 'relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8'; // Resetear clases aquí
                divPrincipal.classList.add('sm:max-w-md');

                modaltitle.textContent = 'Cambiar foto de fondo';

                var fileInput = document.createElement('input');
                fileInput.setAttribute('type', 'file');
                fileInput.setAttribute('name', 'photo_url');
                fileInput.className =
                    'block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-black hover:file:bg-lime-300 my-10';

                // Agrega el div de descripción y el input de archivo al formulario
                form.insertBefore(fileInput, buttonContainer);

                var clubId = this.getAttribute('data-club');
                var formAction = `/clubs/${clubId}/cabecera`;

                form.action = formAction;

                modal.classList.remove('hidden');

                modal.classList.remove('hidden');
            });
        }

        /**
         * Modal de registro en clase
         */
        document.querySelectorAll('.openclassRegisterButton').forEach(button => {
            button.addEventListener('click', function() {

                divPrincipal.classList.add('sm:max-w-xs');
                contenedorDatos.classList.add('h-36');

                modaltitle.textContent = "";

                while (contenedorDatos.firstChild) {
                    contenedorDatos.removeChild(contenedorDatos.firstChild);
                }

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

                var classId = this.getAttribute('data-class');
                var clubId = this.getAttribute('data-club');
                var formAction = `/club-profile/${clubId}/classes/${classId}/register`;

                form.action = formAction;
                modal.classList.remove('hidden');
            });
        });

        /**
         * Modal de editar información del club
         */
        var openModalInfoButton = document.getElementById('openModalInfoButton');
        if (openModalInfoButton) {
            // Abre el modal
            openModalInfoButton.addEventListener('click', function() {

                modaltitle.textContent = "";

                while (contenedorDatos.firstChild) {
                    contenedorDatos.removeChild(contenedorDatos.firstChild);
                }

                var existingInputs = form.querySelectorAll(
                'input:not([type="submit"]),label, textarea');
                existingInputs.forEach(function(input) {
                    input.remove(); // Elimina cada campo que no sea un botón de submit
                });

                divPrincipal.className = 'relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8'; // Resetear clases aquí
                divPrincipal.classList.add('sm:max-w-md');

                modaltitle.textContent = 'Editar información';

                // Crea un contenedor div para el grupo de la descripción
                var descriptionDiv = document.createElement('div');
                descriptionDiv.className = 'flex flex-col gap-1';

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
                textarea.className = 'rounded';

                // Agrega la etiqueta y el área de texto al div de descripción
                descriptionDiv.appendChild(label);
                descriptionDiv.appendChild(textarea);

                // Crea el input para cargar archivos
                var fileInput = document.createElement('input');
                fileInput.setAttribute('type', 'file');
                fileInput.setAttribute('name', 'photo_url');
                fileInput.className =
                    'block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-black hover:file:bg-lime-300 my-10';

                // Agrega el div de descripción y el input de archivo al formulario
                form.insertBefore(descriptionDiv, buttonContainer);
                form.insertBefore(fileInput, buttonContainer);

                var clubId = this.getAttribute('data-club');
                var formAction = `/clubs/${clubId}/edit-details`;

                form.action = formAction;

                modal.classList.remove('hidden');
            });
        }

        // Cierra el modal
        closeModal.addEventListener('click', function() {

            modal.classList.add('hidden');
            divPrincipal.classList.add('sm:max-w-xs');
            divPrincipal.classList.add('sm:max-w-md');
            contenedorDatos.classList.remove('h-36');
            contenedorDatos.classList.remove('w-36');
        });

        // Cierra el modal tras darle a confirmar
        closeModal2.addEventListener('click', function() {
            modal.classList.add('hidden');
            divPrincipal.classList.remove('sm:max-w-xs');
            divPrincipal.classList.remove('sm:max-w-md');
            contenedorDatos.classList.remove('h-36');
            contenedorDatos.classList.remove('w-36');
        });

    });
</script>
