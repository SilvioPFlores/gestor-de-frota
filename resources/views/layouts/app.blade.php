<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <div class="flex h-screen overflow-hidden">

        <aside
            class="w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 flex flex-col justify-between">
            <div class="p-5">
                <div class="flex items-center space-x-2 font-bold text-xl mb-10 text-indigo-600 dark:text-indigo-400">
                    <span>🚚 Gestor de Frota</span>
                </div>

                <nav class="space-y-2">
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center space-x-2 px-4 py-2.5 rounded-lg transition {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/50 dark:text-indigo-400 font-semibold' : 'hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                        <span>📊 Dashboard</span>
                    </a>

                    @can('gerenciar usuarios')
                        <a href="{{ route('users.index') }}"
                            class="flex items-center space-x-2 px-4 py-2.5 rounded-lg transition {{ request()->routeIs('users.index') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/50 dark:text-indigo-400 font-semibold' : 'hover:bg-gray-50 dark:hover:bg-gray-700/50 text-gray-600 dark:text-gray-400' }}">
                            <span>👥 Usuários</span>
                        </a>
                    @endcan

                    <a href="#"
                        class="flex items-center space-x-2 px-4 py-2.5 rounded-lg transition hover:bg-gray-50 dark:hover:bg-gray-700/50 text-gray-600 dark:text-gray-400">
                        <span>🚗 Veículos</span>
                    </a>
                </nav>
            </div>

            <div class="p-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div class="truncate mr-2">
                    <p class="text-sm font-semibold truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ Auth::user()->roles->first()?->name ?? 'Sem Nível' }}
                    </p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-400 hover:text-red-500 transition">
                        🚪
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-y-auto">
            @if (isset($header))
                <header
                    class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 py-4 px-6">
                    <div class="max-w-7xl mx-auto">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main class="py-6 px-6 max-w-7xl w-full mx-auto">
                {{ $slot }}
            </main>
        </div>

    </div>
</body>

</html>
