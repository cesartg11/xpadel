@extends('layouts.plantilla')
@section('titulo', 'Clubs')
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
                        placeholder="Nombre del club..." type="text" name="search" id="searchInput" />
                </label>

                <select name="provincia"
                    class="rounded w-32 h-9 text-sm border-slate-300 focus:border-lime-300 focus:ring-lime-300 cursor-pointer">
                    <option value="">Provincia</option>
                </select>

                <button class="rounded w-28 h-9 text-sm border-2 border-slate-300 bg-lime-300 hover:bg-lime-400" type="submit"
                    id="searchButton">Buscar</button>
            </div>

            <div class="mt-14 gap-8 grid xl:grid-cols-5 lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1" id="clubsContainer">
                @foreach ($clubs as $club)
                    <div class="h-60 w-64 rounded-3xl shadow-lg justify-self-center bg-white">
                        <a href="{{ route('clubs.show', $club) }}">
                            @php
                                $backPhoto = $club->photos->where('photo_type', 'cabecera')->first();
                            @endphp
                            @if ($backPhoto)
                                <div class="relative top-2 p-5 h-1/2 text-lg font-bold rounded-t-3xl"
                                    style="background-image: url({{ asset('assets/clubs/' . $backPhoto->photo_url) }}); background-size: cover; background-position: center; background-repeat: no-repeat; top:0;">
                                    {{ $club->name }}
                                </div>
                            @else
                                <div class="relative top-2 p-5 h-1/2 text-lg font-bold rounded-t-3xl"
                                    style="background-image: url({{ asset('assets/imagenes/defaultBackground.png') }}); background-size: cover; background-position: center; background-repeat: no-repeat; top:0;">
                                    {{ $club->name }}
                                </div>
                            @endif
                            <hr class="w-full bg-black-400 bg-black">
                            <div class="pl-5 pt-3 pr-5 flex justify-start align-items-center gap-3">
                                <svg class="max-h-5 max-w-5 min-h-5 min-w-5 text-black" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <p class="text-xs">{{ $club->address }}</p>
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

        var selectProvincias = document.querySelector('select[name="provincia"]');
        var searchButton = document.getElementById('searchButton');
        var searchInput = document.getElementById('searchInput');
        var container = document.getElementById('clubsContainer');
        var pagination = document.getElementById('pagination');
        var firstPage = document.getElementById('firstPage');
        var prevPage = document.getElementById('prevPage');
        var nextPage = document.getElementById('nextPage');
        var lastPage = document.getElementById('lastPage');
        var currentPage = 1;
        var totalPages = 1;

        var provincias = [
            "Álava", "Albacete", "Alicante", "Almería", "Asturias", "Ávila",
            "Badajoz", "Baleares", "Barcelona", "Burgos", "Cáceres", "Cádiz",
            "Cantabria", "Castellón", "Ciudad Real", "Córdoba", "La Coruña",
            "Cuenca", "Girona", "Granada", "Guadalajara", "Guipúzcoa", "Huelva",
            "Huesca", "Jaén", "León", "Lleida", "Lugo", "Madrid", "Málaga",
            "Murcia", "Navarra", "Ourense", "Palencia", "Las Palmas", "Pontevedra",
            "La Rioja", "Salamanca", "Segovia", "Sevilla", "Soria", "Tarragona",
            "Santa Cruz de Tenerife", "Teruel", "Toledo", "Valencia", "Valladolid",
            "Vizcaya", "Zamora", "Zaragoza"
        ];

        provincias.forEach(provincia => {
            var option = document.createElement('option');
            option.value = provincia;
            option.textContent = provincia;
            selectProvincias.appendChild(option);
        });

        /**
         * Busca entre los clubs por nombre y muestra los que coincidan
         */
        searchButton.addEventListener('click', async function() {
            var searchQuery = searchInput.value.toLowerCase();
            var provincia = selectProvincias.value;
            if (searchQuery || provincia) {
                try {
                    container.innerHTML = ''; // Clear container immediately
                    pagination.classList.add('hidden'); // Hide pagination immediately
                    fetchData(1, searchQuery, provincia);
                } catch (error) {
                    console.error('Error fetching clubs:', error);
                }
            }
        });

        /**
         * Trae los datos de clubs en formato JSON
         */
        function fetchData(page, searchQuery = '', provincia = '') {
            var params = new URLSearchParams({
                search: searchQuery,
                page: page
            });
            if (provincia) params.append('provincia', provincia);

            var url = new URL(`/api/clubs?${params.toString()}`, window.location.origin);

            console.log('URL being fetched:', url.toString()); // Check the complete URL

            return fetch(url)
                .then(response => response.json())
                .then(data => {
                    console.log(`Response for page ${page}:`, data); // Log the response data
                    totalPages = data.last_page;
                    currentPage = data.current_page;
                    displayClubs(data.data);
                    updatePagination(data);
                    return data;
                })
                .catch(error => {
                    console.error('Error fetching clubs:', error);
                    displayNoResultsMessage();
                });
        }

        /**
         * Crea el botón de reseteo
         */
        function createResetButton() {
            var resetButton = document.createElement('button');
            resetButton.textContent = 'Mostrar todos los clubs';
            resetButton.classList.add('col-span-5', 'justify-self-center', 'rounded', 'w-44', 'h-9', 'text-sm',
                'border-2', 'border-slate-300', 'bg-lime-300');
            resetButton.onclick = () => fetchData();
            return resetButton;
        }

        /**
         * Muestra emnsaje de error si no se encuentra ningun club, y ademas muestra botón para mostrar todos los clubs otra vez
         */
        function displayNoResultsMessage() {
            container.innerHTML =
                '<p class="col-span-5 text-center">No se encontraron clubes con los criterios de búsqueda.</p>';
            var resetButton = createResetButton();
            container.appendChild(resetButton);
            document.getElementById('pagination').classList.add('hidden');
        }

        /**
         * Muestra y maqueta cada club
         */
        function displayClubs(clubs) {

            container.innerHTML = "";

            if (clubs.length == 0) {
                displayNoResultsMessage()
            } else {

                clubs.forEach(club => {
                    try {

                        var clubElement = document.createElement('div');
                        clubElement.classList.add('h-60', 'w-64', 'rounded-3xl',
                            'shadow-lg', 'justify-self-center', 'bg-white');

                        var clubLink = document.createElement('a');
                        clubLink.setAttribute('href', `/clubs/${club.id}`);

                        var clubHeader = document.createElement('div');
                        clubHeader.classList.add('relative', 'top-2', 'p-5', 'h-1/2',
                            'text-lg', 'font-bold', 'rounded-t-3xl');

                        var backPhoto = backPhoto = club.photos.find(photo => photo.photo_type === 'cabecera');
                        console.log(club)

                        if (backPhoto) {
                            clubHeader.style.backgroundImage =
                                `url('assets/clubs/${backPhoto.photo_url}')`;
                            console.log(backPhoto)
                        } else {
                            clubHeader.style.backgroundImage =
                                `url('assets/imagenes/defaultBackground.png')`;
                        }

                        clubHeader.style.backgroundSize = 'cover';
                        clubHeader.style.backgroundPosition = 'center';
                        clubHeader.style.backgroundRepeat = 'no-repeat';
                        clubHeader.style.top = '0';
                        clubHeader.textContent = club.name;

                        var hr = document.createElement('hr');

                        var clubInfo = document.createElement('div');
                        clubInfo.classList.add('pl-5', 'pt-3', 'pr-5', 'flex',
                            'justify-start', 'align-items-center', 'gap-3');

                        var locationIcon = document.createElementNS('http://www.w3.org/2000/svg',
                            'svg');
                        locationIcon.classList.add('max-h-5', 'max-w-5', 'min-h-5',
                            'min-w-5', 'text-black');
                        locationIcon.setAttribute('fill', 'none');
                        locationIcon.setAttribute('viewBox', '0 0 24 24');
                        locationIcon.setAttribute('stroke', 'currentColor');

                        var locationPath1 = document.createElementNS(
                            'http://www.w3.org/2000/svg',
                            'path',
                        );
                        locationPath1.setAttribute('stroke-linecap', 'round');
                        locationPath1.setAttribute('stroke-linejoin', 'round');
                        locationPath1.setAttribute('stroke-width', '2');
                        locationPath1.setAttribute('d',
                            'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z'
                        );

                        var locationPath2 = document.createElementNS(
                            'http://www.w3.org/2000/svg',
                            'path',
                        );
                        locationPath2.setAttribute('stroke-linecap', 'round');
                        locationPath2.setAttribute('stroke-linejoin', 'round');
                        locationPath2.setAttribute('stroke-width', '2');
                        locationPath2.setAttribute('d',
                            'M15 11a3 3 0 11-6 0 3 3 0 016 0z');

                        var locationText = document.createElement('p');
                        locationText.classList.add('text-xs');
                        locationText.textContent = club.address;

                        locationIcon.appendChild(locationPath1);
                        locationIcon.appendChild(locationPath2);

                        clubInfo.appendChild(locationIcon);
                        clubInfo.appendChild(locationText);

                        clubLink.appendChild(clubHeader);
                        clubLink.appendChild(hr);
                        clubLink.appendChild(clubInfo);
                        clubElement.appendChild(clubLink);

                        container.appendChild(clubElement);

                        document.getElementById('pagination').classList.remove('hidden');
                    } catch (error) {
                        container.innerHTML =
                            '<p class="col-span-5 text-center">Error al cargar los clubs. Porfavor intentelo más tarde.</p>';
                    }
                })
            }
        }

        function updatePagination(data) {
            prevPage.disabled = !data.prev_page_url;
            nextPage.disabled = !data.next_page_url;
            firstPage.disabled = data.current_page === 1;
            lastPage.disabled = data.current_page === data.last_page;
            document.getElementById('lastPage').querySelector('p').innerText = data.last_page;
        }

        function changePage(page) {
            console.log(`Changing to page: ${page}`);
            currentPage = page; // Set the current page
            fetchData(currentPage, searchInput.value.toLowerCase(), selectProvincias.value)
                .then(data => {
                    console.log(`Data fetched for page ${page}: `, data);
                    displayClubs(data.data); // Make sure this function is handling data properly
                    updatePagination(data); // Update pagination controls
                })
                .catch(error => {
                    console.error('Error fetching data for page: ', page, error);
                });
        }

        firstPage.addEventListener('click', () => !firstPage.disabled && changePage(1));
        prevPage.addEventListener('click', () => !prevPage.disabled && changePage(currentPage - 1));
        nextPage.addEventListener('click', () => !nextPage.disabled && changePage(currentPage + 1));
        lastPage.addEventListener('click', () => !lastPage.disabled && changePage(totalPages));

    });
</script>
