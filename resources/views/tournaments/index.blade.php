@extends('layouts.plantilla')
@section('titulo', 'Torneos')
@section('contenido')
    <div class="w-full mt-32">
        <div class="mx-20">
            <div class="flex justify-start align-items-center gap-5">
                <label class="relative block">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-2 z-1">
                        <svg class="h-5 w-5 text-slate-500 z-1" width="24" height="24" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" />
                            <circle cx="10" cy="10" r="7" />
                            <line x1="21" y1="21" x2="15" y2="15" />
                        </svg>
                    </span>
                    <input
                        class="h-9 placeholder:italic placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-9 pr-3 shadow-sm focus:outline-none focus:border-lime-300 focus:ring-lime-300 sm:text-sm"
                        placeholder="Nombre del torneo..." type="text" name="search" id="searchInput" />
                </label>

                <button class="rounded w-28 h-9 text-sm border-2 border-slate-300 bg-lime-300 hover:bg-lime-400"
                    type="submit" id="searchButton">Buscar</button>
            </div>

            <div id="tournamentsContainer"
                class="mt-14 gap-8 grid xl:grid-cols-5 lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1" id="clubsContainer">
                @foreach ($tournaments as $tournament)
                    <div class="h-60 w-64 rounded-3xl shadow-lg justify-self-center bg-white">
                        <a href="{{ route('tournaments.show', $tournament) }}">
                            @if ($tournament->photo_url)
                                <div class="relative top-2 p-5 h-1/2 text-lg font-bold rounded-t-3xl"
                                    style="background-image: url({{ asset('assets/tournaments/' . $tournament->photo_url) }}); background-size: cover; background-position: center; background-repeat: no-repeat; top:0;">
                                    {{ $tournament->name }}
                                </div>
                            @else
                                <div class="relative top-2 p-5 h-1/2 text-lg font-bold rounded-t-3xl"
                                    style="background-image: url({{ asset('assets/imagenes/events-default.jpg') }}); background-size: cover; background-position: center; background-repeat: no-repeat; top:0;">
                                    {{ $tournament->name }}
                                </div>
                            @endif

                            <hr class="w-full bg-black-400 bg-black">
                            <div class="pl-5 pt-3 pr-5 flex justify-start align-items-center gap-3">
                                <p class="text-xs"
                                    style="overflow: hidden;  text-overflow: ellipsis; display: -webkit-box;  -webkit-line-clamp: 3; -webkit-box-orient: vertical; height: 100%; max-height: 4.5em;">
                                    {{ $tournament->description }}</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="w-full flex justify-center align-items-center" id="pagination">
                <div
                    class="mt-8 w-44 h-10 flex flex-row align-items-center justify-center rounded border-2 shadow-sm bg-white cursor-pointer">
                    <button class="w-1/4 h-full flex align-items-center justify-center hover:bg-lime-300" id="firstPage">
                        <p class="self-center">1</p>
                    </button>
                    <button class="w-1/4 border-x-2 flex align-items-center justify-center hover:bg-lime-300"
                        id="prevPage">
                        <svg class="h-full w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                    <button class="w-1/4 border-x-2 flex align-items-center justify-center hover:bg-lime-300"
                        id="nextPage">
                        <svg class="h-full w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                    <button class="w-1/4 h-full flex align-items-center justify-center hover:bg-lime-300" id="lastPage">
                        <p class="self-center">...</p>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {

        document.getElementById('searchButton').addEventListener('click', function() {
            var query = document.getElementById('searchInput').value;
            fetch(`/api/tournaments?search=${query}`)
                .then(response => response.json())
                .then(data => {
                    displayTournaments(data);

                })
                .catch(error => console.error('Error en la búsqueda de torneos:', error));
        });

        /**
         * Muestra y maqueta cada torneo
         */
        function displayTournaments(tournaments) {
            var container = document.getElementById('tournamentsContainer');
            container.innerHTML = "";

            if (tournaments.length === 0) {
                container.innerHTML = '<p class="text-center">No se encontraron torneos.</p>';
            } else {
                tournaments.forEach(tournament => {
                    var tournamentElement = document.createElement('div');
                    tournamentElement.className =
                        'h-60 w-64 rounded-3xl shadow-lg justify-self-center bg-white';

                    var tournamentLink = document.createElement('a');
                    tournamentLink.href = `/tournaments/${tournament.id}`;

                    var tournamentHeader = document.createElement('div');
                    tournamentHeader.className =
                        'relative top-2 p-5 h-1/2 text-lg font-bold rounded-t-3xl';
                    tournamentHeader.textContent = tournament.name;

                    var hr = document.createElement('hr');
                    hr.className = 'w-full bg-black-400 bg-black';

                    var tournamentInfo = document.createElement('div');
                    tournamentInfo.className =
                        'pl-5 pt-3 pr-5 flex justify-start align-items-center gap-3';

                    var description = document.createElement('p');
                    description.className = 'text-xs';
                    description.style.cssText =
                        'overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; height: 100%; max-height: 4.5em;';
                    description.textContent = tournament.description;

                    tournamentInfo.appendChild(description);

                    tournamentLink.appendChild(tournamentHeader);
                    tournamentLink.appendChild(hr);
                    tournamentLink.appendChild(tournamentInfo);

                    tournamentElement.appendChild(tournamentLink);

                    container.appendChild(tournamentElement);
                });
            }
        }

    });
</script>
