<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="auto">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- FONTAWESOME -->
    <script src="https://kit.fontawesome.com/c1e5b3a1f7.js" crossorigin="anonymous"></script>

    <title>@yield('title', 'Gestor de Frota')</title>
</head>

<body>

    <div class="d-flex wrapper">

        @include('partials.sidebar')
        <div class="flex-grow-1 d-flex flex-column">

            @include('partials.header')
            <main class="flex-grow-1 p-4">
                @yield('content')
            </main>
        </div>
    </div>
    <!-- Permite que telas filhas injetem JavaScript específico caso precise -->
    @stack('scripts')
</body>

</html>
