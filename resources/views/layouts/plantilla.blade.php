<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>X-padel - @yield('titulo')</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100">
    @include('layouts.partials.header')

    <main class="mb-10 bg-gray-100">

        @if ($errors->any())
            <div class="bg-red-500 text-white p-4 rounded-md shadow">
                Hubo los siguientes errores de validación:
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-500 text-white p-4 rounded-md shadow" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-500 text-white p-4 rounded-md shadow" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @yield('contenido')
    </main>
</body>
