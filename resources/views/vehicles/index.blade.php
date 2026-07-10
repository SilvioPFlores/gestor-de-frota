<x-app-layout>
    
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    Veículos
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Cadastro e situação da frota.</p>
            </div>
            <button onclick="document.getElementById('modal-veiculo').classList.remove('hidden')" class="inline-flex items-center space-x-2 bg-slate-900 hover:bg-slate-800 text-white font-medium px-4 py-2.5 rounded-xl transition shadow-sm text-sm cursor-pointer">
                <span>+ Novo veículo</span>
            </button>
        </div>
    </x-slot>

    <div class="py-6">
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-400 rounded-xl font-medium text-sm border border-green-200 dark:border-green-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl border border-gray-100 dark:border-gray-700/60">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-gray-700 bg-gray-50/70 dark:bg-gray-700/40 text-gray-500 dark:text-gray-400 font-medium">
                            <th class="px-6 py-4">Placa</th>
                            <th class="px-6 py-4">Veículo</th>
                            <th class="px-6 py-4">Ano</th>
                            <th class="px-6 py-4">KM</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-gray-700 dark:text-gray-300">
                        @forelse($vehicles as $vehicle)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition">
                                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white uppercase">{{ $vehicle->plate }}</td>
                                <td class="px-6 py-4 capitalize">{{ $vehicle->brand }} {{ $vehicle->model }}</td>
                                <td class="px-6 py-4">{{ $vehicle->year }}</td>
                                <td class="px-6 py-4">{{ number_format($vehicle->current_km, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">
                                        {{ $vehicle->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <button class="text-gray-400 hover:text-gray-600 transition">✏️</button>
                                    <button class="text-gray-400 hover:text-red-500 transition">🗑️</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                                    Nenhum veículo cadastrado no momento.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="modal-veiculo" class="hidden fixed inset-0 z-[9999] overflow-y-auto flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
        
        <div class="bg-white dark:bg-gray-800 w-full max-w-xl rounded-2xl p-6 shadow-2xl relative">
            
            <button onclick="document.getElementById('modal-veiculo').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-white text-2xl cursor-pointer">
                &times;
            </button>

            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Novo veículo</h3>

            <form action="{{ route('vehicles.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Placa</label>
                        <input type="text" name="plate" required placeholder="ABC-1234" class="w-full rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 px-3 py-2.5 focus:ring-slate-500 focus:border-slate-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Ano</label>
                        <input type="number" name="year" required placeholder="2024" class="w-full rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 px-3 py-2.5 focus:ring-slate-500 focus:border-slate-500 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Marca</label>
                        <input type="text" name="brand" required class="w-full rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 px-3 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Modelo</label>
                        <input type="text" name="model" required class="w-full rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 px-3 py-2.5 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Cor</label>
                        <input type="text" name="color" required class="w-full rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 px-3 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Combustível</label>
                        <input type="text" name="fuel" required class="w-full rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 px-3 py-2.5 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">KM atual</label>
                        <input type="number" name="current_km" required value="0" class="w-full rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 px-3 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select name="status" class="w-full rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 px-3 py-2.5 text-sm">
                            <option value="Disponível">Disponível</option>
                            <option value="Em Uso">Em Uso</option>
                            <option value="Manutenção">Manutenção</option>
                            <option value="Inativo">Inativo</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-medium px-6 py-2.5 rounded-xl transition shadow-sm text-sm cursor-pointer">
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>