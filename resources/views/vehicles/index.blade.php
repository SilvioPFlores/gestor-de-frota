@extends('layouts.app')

@section('titulo', 'Veiculos')

@section('content')
    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h4 mb-1 text-dark fw-bold">
                    Veículos
                </h2>
                <p class="text-muted small mb-0">Cadastro e situação da frota.</p>
            </div>
            <button type="button" class="btn btn-dark" id="btn-novo-veiculo">
                <i class="bi bi-plus-lg"></i> + Novo veículo
            </button>
        </div>
        
        <!-- Alerta de Sucesso -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <span class="fw-medium">{{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Tabela de Veículos -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted">
                        <tr>
                            <th class="ps-4">Placa</th>
                            <th>Veículo</th>
                            <th>Ano</th>
                            <th>KM</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($vehicles as $vehicle)
                            <tr>
                                <td class="ps-4 fw-bold text-uppercase">{{ $vehicle->plate }}</td>
                                <td class="text-capitalize">{{ $vehicle->brand }} {{ $vehicle->model }}</td>
                                <td>{{ $vehicle->year }}</td>
                                <td>{{ number_format($vehicle->current_km, 0, ',', '.') }}</td>
                                <td>
                                    <!-- Badges dinâmicas baseadas no status (Opcional: você pode customizar as cores) -->
                                    <span class="badge bg-secondary rounded-pill px-3 py-2 fw-medium">
                                        {{ $vehicle->status }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <button type="button" class="btn btn-sm btn-outline-primary me-1 btn-edit" data-vehicle="{{ json_encode($vehicle) }}">
                                        ✏️ Editar
                                    </button>
                                    
                                    <form action="{{ route('vehicles.destroy', $vehicle) }}" method="POST" class="d-inline-block form-delete">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            🗑️
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    Nenhum veículo cadastrado no momento.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal de Veículo -->
    <div class="modal fade" id="modalVeiculo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold" id="modal-title">Novo veículo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form-veiculo" action="{{ route('vehicles.store') }}" method="POST">
                        @csrf
                        <div id="method-container"></div>
                        
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Placa</label>
                                <input type="text" id="input-plate" name="plate" required placeholder="ABC-1234 ou ABC1D23" class="form-control" maxlength="8">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Ano</label>
                                <input type="number" id="input-year" name="year" required placeholder="2024" class="form-control">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Marca</label>
                                <input type="text" id="input-brand" name="brand" required class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Modelo</label>
                                <input type="text" id="input-model" name="model" required class="form-control">
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Cor</label>
                                <input type="text" id="input-color" name="color" required class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Combustível</label>
                                <input type="text" id="input-fuel" name="fuel" required class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">KM atual</label>
                                <input type="number" id="input-current_km" name="current_km" required value="0" class="form-control">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Status</label>
                            <select id="input-status" name="status" class="form-select">
                                <option value="Disponível">Disponível</option>
                                <option value="Em Uso">Em Uso</option>
                                <option value="Manutenção">Manutenção</option>
                                <option value="Inativo">Inativo</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-end border-top pt-3">
                            <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-dark px-4">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts jQuery e Máscaras -->
    <script type="module">
        $(document).ready(function() {
            // Inicializa a instância do Modal do Bootstrap
            const modalVeiculo = new bootstrap.Modal(document.getElementById('modalVeiculo'));

            // 1. Abrir modal para NOVO VEÍCULO
            $('#btn-novo-veiculo').on('click', function() {
                $('#modal-title').text('Novo veículo');
                $('#form-veiculo').attr('action', "{{ route('vehicles.store') }}");
                $('#method-container').empty(); // Remove o PUT
                $('#form-veiculo')[0].reset(); // Limpa os campos
                modalVeiculo.show();
            });

            // 2. Abrir modal para EDITAR VEÍCULO
            $('.btn-edit').on('click', function() {
                // Recupera os dados do objeto JSON injetado no botão
                let vehicle = $(this).data('vehicle');
                
                $('#modal-title').text('Editar veículo: ' + vehicle.plate);
                $('#form-veiculo').attr('action', `/veiculos/${vehicle.id}`); // Ajuste para a sua rota exata
                $('#method-container').html('<input type="hidden" name="_method" value="PUT">');
                
                // Preenche os inputs
                $('#input-plate').val(vehicle.plate);
                $('#input-year').val(vehicle.year);
                $('#input-brand').val(vehicle.brand);
                $('#input-model').val(vehicle.model);
                $('#input-color').val(vehicle.color);
                $('#input-fuel').val(vehicle.fuel);
                $('#input-current_km').val(vehicle.current_km);
                $('#input-status').val(vehicle.status);

                modalVeiculo.show();
            });

            // 3. Validação de Exclusão
            $('.form-delete').on('submit', function(e) {
                if (!confirm('Tem certeza que deseja excluir este veículo? Esta ação não pode ser desfeita.')) {
                    e.preventDefault();
                }
            });

            // 4. Máscara Inteligente para Placa (Mercosul e Antiga)
            $('#input-plate').on('input', function() {
                let value = $(this).val().toUpperCase().replace(/[^A-Z0-9]/g, ''); // Apenas letras e números
                
                if (value.length > 7) {
                    value = value.substring(0, 7);
                }

                // Se já digitou 5 caracteres ou mais, verifica o formato
                if (value.length >= 5) {
                    // Verifica se o 5º caractere (índice 4) é uma letra (Padrão Mercosul: ABC1D23)
                    if (/[A-Z]/.test(value.charAt(4))) {
                        // Deixa sem hífen (Mercosul)
                    } else {
                        // Padrão antigo: adiciona o hífen (ABC-1234)
                        value = value.replace(/^([A-Z]{3})([0-9]{1,4})$/, "$1-$2");
                    }
                } else if (value.length > 3 && !/[0-9]/.test(value.charAt(3))) {
                    // Impede de digitar letra na 4ª posição (sempre será número em ambos os padrões)
                    value = value.substring(0, 3);
                }

                $(this).val(value);
            });
        });
    </script>
@endsection