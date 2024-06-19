@extends('layouts.plantilla')
@section('titulo', 'Mis actividades')
@section('contenido')
    <div class="w-full mt-32">

        <div class="mx-20">
            <div class="my-10">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold mb-4">Tus alquileres</h2>
                </div>
                <div class="shadow">
                    <!-- Encabezados -->
                    <div class="flex justify-between bg-gray-200 p-4 p-lg font-bold rounded-t">
                        <div class="flex-1 text-center">Club</div>
                        <div class="flex-1 text-center">Pista</div>
                        <div class="flex-1 text-center">Tipo</div>
                        <div class="flex-1 text-center">Fecha</div>
                        <div class="flex-1 text-center">Hora</div>
                    </div>
                    @forelse ($alquileres as $alquiler)
                        <div class="flex justify-between items-center border p-4 p-lg text-center">
                            <div class="flex-1">{{ $alquiler->court->clubprofile->name }}</div>
                            <div class="flex-1">Pista {{ $alquiler->court->number }}</div>
                            <div class="flex-1">{{ $alquiler->court->type }}</div>
                            <div class="flex-1">{{\Carbon\Carbon::parse($alquiler->start_time)->format('d/m/Y') }}</div>
                            <div class="flex-1">{{\Carbon\Carbon::parse($alquiler->start_time)->format('H:i') }} a {{ \Carbon\Carbon::parse($alquiler->end_time)->format('H:i') }}</div>
                        </div>
                    @empty
                        <p class="text-center py-4">No tienes alquileres de pistas.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="mx-20">
            <div class="my-10">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold mb-4">Tus clases</h2>
                </div>
                <div class="shadow">
                    <!-- Encabezados -->
                    <div class="flex justify-between bg-gray-200 p-4 p-lg font-bold rounded-t">
                        <div class="flex-1 text-center">Club</div>
                        <div class="flex-1 text-center">Nivel</div>
                        <div class="flex-1 text-center">Pista</div>
                        <div class="flex-1 text-center">Fecha</div>
                        <div class="flex-1 text-center">Hora</div>
                    </div>
                    @forelse ($clases as $clase)
                        <div class="flex justify-between items-center border p-4 p-lg text-center">
                            <div class="flex-1">{{ $clase->clubClass->clubProfile->name }}</div>
                            <div class="flex-1">{{ $clase->clubClass->level }}</div>
                            <div class="flex-1">Pista {{ $clase->clubClass->court->number }}</div>
                            <div class="flex-1">{{\Carbon\Carbon::parse($clase->clubClass->start_time)->format('d/m/Y') }}</div>
                            <div class="flex-1">{{\Carbon\Carbon::parse($clase->clubClass->start_time)->format('H:i') }} a {{ \Carbon\Carbon::parse($clase->clubClass->end_time)->format('H:i') }}</div>
                        </div>
                    @empty
                        <p class="text-center py-4">No te has apuntado a ninguna clase.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="mx-20">
            <div class="my-10">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold mb-4">Tus torenos</h2>
                </div>
                <div class="shadow">
                    <!-- Encabezados -->
                    <div class="flex justify-between bg-gray-200 p-4 p-lg font-bold rounded-t">
                        <div class="flex-1 text-center">Club</div>
                        <div class="flex-1 text-center">Torneo</div>
                        <div class="flex-1 text-center">Fecha Inicio</div>
                        <div class="flex-1 text-center">Fecha Fin</div>
                        <div class="flex-1 text-center">Estado</div>
                    </div>
                    @forelse ($torneos as $torneo)
                        <div class="flex justify-between items-center border p-4 p-lg text-center">
                            <div class="flex-1">{{ $torneo->tournament->club->name }}</div>
                            <div class="flex-1">{{ $torneo->tournament->name }}</div>
                            <div class="flex-1">{{ $torneo->tournament->start_date->format('d/m/Y') }}</div>
                            <div class="flex-1">{{ $torneo->tournament->end_date->format('d/m/Y') }}</div>
                            <div class="flex-1">{{ $torneo->tournament->status }}</div>
                        </div>
                    @empty
                        <p class="text-center py-4">No te has apuntado a ninguna clase.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
@endsection
