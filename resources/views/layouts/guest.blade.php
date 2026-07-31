<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="auto">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Gestor de Frota' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/c1e5b3a1f7.js" crossorigin="anonymous"></script>

    @stack('head')
</head>

<body class="bg-body-tertiary">

    <main class="container min-vh-100 d-flex align-items-center justify-content-center py-4">

        <div class="row justify-content-center w-100">
            <div class="col-12 col-sm-10 col-md-8 col-lg-5 col-xl-4">

                @include('partials.logo')
                <div class="card shadow border-0">
                    <div class="card-body p-4">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </main>
    @stack('scripts')

</body>
</html>