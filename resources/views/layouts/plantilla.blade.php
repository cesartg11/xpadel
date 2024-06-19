<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>X-padel - @yield('titulo')</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/imagenes/logo.png') }}">
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100">
    @include('layouts.partials.header')

    <main class="mb-10 bg-gray-100">

        @if ($errors->any())
            <div id="errorMessage"
                class="flex flex-row gap-4 bg-red-500 text-white p-4 rounded-md shadow mt-28 absolute right-0 z-20">
                <div class="flex justify-center items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                </div>
                <div class="flex justify-center items-center flex-col">
                    <p>Hubo los siguientes errores de validación:</p>
                    <ul class="text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div id="successMessage"
                class="flex flex-row gap-4 bg-blue-500 text-white p-4 rounded-md shadow mt-28 absolute right-0 z-20">
                <div class="flex justify-center items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <div class="flexjustify-center items-center">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div id="errorMessage2"
                class="flex flex-row gap-4 bg-red-500 text-white p-4 rounded-md shadow mt-28 absolute right-0 z-20">
                <div class="flex justify-center items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                </div>
                <div class="flexjustify-center items-center">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('contenido')
    </main>
</body>

<script>
    window.addEventListener('DOMContentLoaded', (event) => {
        let successTimeout, errorTimeout; // Almacenar los ID de los temporizadores
        var successMessage = document.getElementById('successMessage');
        var errorMessage = document.getElementById('errorMessage');
        var errorMessage2 = document.getElementById('errorMessage2');

        function hideElement(element) {
            if (element) {
                element.style.display = 'none';
            }
        }

        if (successMessage) {
            clearTimeout(successTimeout); // Limpiar el temporizador anterior si existe
            successTimeout = setTimeout(() => hideElement(successMessage), 10000);
        }

        if (errorMessage) {
            clearTimeout(errorTimeout); // Limpiar el temporizador anterior si existe
            errorTimeout = setTimeout(() => hideElement(errorMessage), 10000);
        }

        if (errorMessage2) {
            clearTimeout(errorTimeout); // Limpiar el temporizador anterior si existe
            errorTimeout = setTimeout(() => hideElement(errorMessage2), 10000);
        }
    });
</script>
