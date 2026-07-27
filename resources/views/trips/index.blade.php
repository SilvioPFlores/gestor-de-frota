@extends('layouts.app')

@section('title', 'Viagens')

@section('content')
    <div class="container-fluid">

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <p class="text-muted small mb-0">Programação operacional da frota: origem, destino e horários.</p>
            </div>
            <button type="button" class="btn btn-outline-secondary" id="btn-nova-viagem">
                <i class="fa-solid fa-plus me-1 icon"></i>Nova viagem
            </button>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <span class="fw-medium">{{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="custom-table-header">
                        <tr>
                            <th class="ps-4">Saída</th>
                            <th>Trajeto</th>
                            <th>Veículo</th>
                            <th>Motorista</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($trips as $trip)
                            <tr>
                                <td class="ps-4">{{ $trip->departure_time->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="fw-bold">{{ $trip->origin }}</div>
                                    <div class="text-muted small">➔ {{ $trip->destination }}</div>
                                </td>
                                <td>{{ $trip->vehicle->plate }} <span
                                        class="text-muted small">({{ $trip->vehicle->model }})</span></td>
                                <td>{{ $trip->driver->name }}</td>
                                <td>
                                    <span
                                        class="badge rounded-pill px-3 py-2 fw-medium
                                        @if ($trip->status == 'Concluída') bg-success 
                                        @elseif($trip->status == 'Cancelada') bg-danger 
                                        @elseif($trip->status == 'Em andamento') bg-primary 
                                        @else bg-secondary @endif">
                                        {{ $trip->status }}
                                    </span>
                                </td>
                                <td class="pe-4">
                                    <div class="d-flex justify-content-end align-items-center flex-nowrap gap-2">

                                        <button type="button" class="btn btn-sm btn-outline-primary btn-edit"
                                            data-trip="{{ json_encode($trip) }}" title="Editar">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>

                                        <!-- Removemos as margens padrão do form com m-0 -->
                                        <form action="{{ route('trips.destroy', $trip) }}" method="POST" class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Excluir">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    Nenhuma viagem.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Nova Viagem -->
        <div class="modal fade" id="modal-viagem" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header border-bottom-0 pb-0">
                        <h5 class="modal-title fw-bold" id="modal-title">Nova viagem</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="form-viagem" action="{{ route('trips.store') }}" method="POST">
                            @csrf
                            <div id="method-container"></div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Veículo</label>
                                    <select id="input-vehicle" name="vehicle_id" class="form-select" required>
                                        <option value="" disabled selected>Selecione</option>
                                        @foreach ($vehicles as $vehicle)
                                            <option value="{{ $vehicle->id }}">{{ $vehicle->plate }} -
                                                {{ $vehicle->model }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Motorista</label>
                                    <select id="input-driver" name="driver_id" class="form-select" required>
                                        <option value="" disabled selected>Selecione</option>
                                        @foreach ($drivers as $driver)
                                            <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Finalidade</label>
                                    <input type="text" id="input-purpose" name="purpose" required class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Origem</label>
                                    <input type="text" id="input-origin" name="origin" required class="form-control">
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Destino</label>
                                    <input type="text" id="input-destination" name="destination" required
                                        class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Saída prevista</label>
                                    <input type="datetime-local" id="input-departure" name="departure_time" required
                                        class="form-control">
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Chegada prevista</label>
                                    <input type="datetime-local" id="input-arrival" name="arrival_time"
                                        class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Status</label>
                                    <select id="input-status" name="status" class="form-select" required>
                                        <option value="Agendada">Agendada</option>
                                        <option value="Em andamento">Em andamento</option>
                                        <option value="Concluída">Concluída</option>
                                        <option value="Cancelada">Cancelada</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Observações</label>
                                <input type="text" id="input-observations" name="observations" class="form-control">
                            </div>

                            <div class="d-flex justify-content-end border-top pt-3">
                                <button type="submit" class="btn btn-dark px-4 w-100">Salvar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
            @vite('resources/js/trips.js')

        <script>
                window.app = {
                    routes: {
                        trips: {
                            store: "{{ route('trips.store') }}",
                            base: "{{ url('viagens') }}"
                        }
                    }
                };
        </script>
    @endpush
@endsection
