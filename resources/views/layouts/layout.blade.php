<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="auto">>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- FAVICON -->
    <!--link rel="icon" type="image/png" Tamanhos="16x16" href="{{ asset('img/favicon.ico') }}"-->
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- FONTAWESOME -->
    <script src="https://kit.fontawesome.com/c1e5b3a1f7.js" crossorigin="anonymous"></script>


    <!-- Título -->
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />


    <title>@yield('titulo')</title>
</head>

<body @yield('dataPage')>
    
    @if (!isset($ocultarMenu) || !$ocultarMenu)
        @include('menu', ['nivel' => \Auth::user()->nivel_id ?? 0])
    @endif
    <div class='divConteudo mb-3'>
        @yield('conteudo')
    </div>
</body>
</html>