<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Gerenciamento de Usuários e Níveis de Acesso
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-400 rounded-lg font-medium text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-400 rounded-lg font-medium text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                                    <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Nome</th>
                                    <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">E-mail</th>
                                    <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Nível Atual</th>
                                    <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 text-right">Alterar Nível</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($users as $user)
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition">
                                        <td class="px-6 py-4 font-medium">{{ $user->name }}</td>
                                        <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $user->email }}</td>
                                        <td class="px-6 py-4">
                                            @php
                                                $roleName = $user->roles->first()?->name ?? 'Sem Nível';
                                                $badgeColor = match($roleName) {
                                                    'Admin' => 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400 border-red-200',
                                                    'Gestor' => 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 border-blue-200',
                                                    default => 'bg-gray-50 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400 border-gray-200',
                                                };
                                            @endphp
                                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border {{ $badgeColor }}">
                                                {{ $roleName }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <form action="{{ route('users.update', $user) }}" method="POST" class="inline-flex items-center space-x-2">
                                                @csrf
                                                @method('PUT')
                                                <select name="role" class="text-sm rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:ring-indigo-500 focus:border-indigo-500 py-1 pl-2 pr-8">
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                                            {{ $role->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs px-3 py-1.5 rounded-lg transition font-medium shadow-sm">
                                                    Salvar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>