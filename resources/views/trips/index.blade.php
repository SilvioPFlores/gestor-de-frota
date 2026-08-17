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

        <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
            <div class="card-body p-0 table-responsive">
                <table class="table table-striped align-middle mb-0">
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
                                <td>{{ $trip->vehicle->plate ?? 'Não definido' }}
                                    <span class="text-muted small">({{ $trip->vehicle->model ?? '' }})</span>
                                </td>
                                <td>{{ $trip->driver->name ?? 'Não definido' }}</td>
                                <td>
                                    <span
                                        class="badge rounded-pill px-3 py-2 fw-medium
                                        @if ($trip->status == 'Agendada') bg-success 
                                        @elseif($trip->status == 'Solicitada') bg-warning 
                                        @elseif($trip->status == 'Cancelada') bg-danger 
                                        @elseif($trip->status == 'Em andamento') bg-primary 
                                        @else bg-secondary @endif">
                                        {{ $trip->status }}
                                    </span>
                                </td>
                                <td class="pe-4">
                                    <div class="d-flex justify-content-end align-items-center flex-nowrap gap-2">

                                        <button type="button" class="btn btn-sm btn-outline-primary btn-edit"
                                            data-trip-id="{{ $trip->id }}" 
                                            data-trip="{{ json_encode($trip) }}"
                                            title="Editar">
                                            
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>

                                        <!-- Removemos as margens padrão do form com m-0 -->
                                        <form action="{{ route('trips.cancel', $trip) }}" method="POST"
                                            class="m-0 form-delete">
                                            @csrf
                                            @method('PATCH')
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

        @include('trips.modal-trip')
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
