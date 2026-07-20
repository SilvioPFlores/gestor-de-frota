<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- FONTAWESOME -->
    <script src="https://kit.fontawesome.com/c1e5b3a1f7.js" crossorigin="anonymous"></script>

    <title>@yield('title', 'Gestor de Frota')</title>
</head>

<body>

    <div class="d-flex wrapper">
        <!-- Inclui o Menu Lateral separado -->
        @include('partials.sidebar')

        <!-- Área Central onde as páginas específicas serão renderizadas -->
        <main id="main-content" class="flex-grow-1 p-4">
            @yield('content')
        </main>
    </div>

    <!-- 3. Scripts: jQuery e Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Script do Sidebar -->
    <script>
        $(document).ready(function() {
            // 1. Restaura o estado salvo no navegador ao carregar a página
            if (localStorage.getItem('sidebarState') === 'collapsed') {
                $('#sidebar').addClass('collapsed');
            }

            // 2. Alterna o estado ao clicar no botão
            $('#sidebarToggle').on('click', function() {
                $('#sidebar').toggleClass('collapsed');
                console.log('apertou');

                // Salva a preferência
                if ($('#sidebar').hasClass('collapsed')) {
                    localStorage.setItem('sidebarState', 'collapsed');
                } else {
                    localStorage.setItem('sidebarState', 'expanded');
                }
            });
        });
    </script>

    <!-- Permite que telas filhas injetem JavaScript específico caso precise -->
    @stack('scripts')
</body>

</html>
