@extends('layouts.app')

@section('title', 'Veiculos')

@section('content')
    <div class="container-fluid">

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="h4 mb-1 fw-bold text-dark">Veículos</h2>
                <p class="text-muted small mb-0">Cadastro e situação da frota.</p>
            </div>
            <button type="button" class="btn btn-dark" id="btn-novo-veiculo">
                <i class="bi bi-plus-lg"></i> + Novo veículo
            </button>
        </div>

        <!-- Alerta de Sucesso -->
        @if (session('success'))
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
                                    <div class="d-flex justify-content-end align-items-center flex-nowrap gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary btn-edit"
                                            data-vehicle="{{ json_encode($vehicle) }}">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>

                                        <form action="{{ route('vehicles.destroy', $vehicle) }}" method="POST"
                                            class="m-0 form-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
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
                                <input type="text" id="input-plate" name="plate" required
                                    placeholder="ABC-1234 ou ABC1D23" class="form-control" maxlength="8">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Ano</label>
                                <input type="number" id="input-year" name="year" required placeholder="2024"
                                    class="form-control">
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
                                <input type="number" id="input-current_km" name="current_km" required value="0"
                                    class="form-control">
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
                $('#form-veiculo').attr('action',
                    `/veiculos/${vehicle.id}`); // Ajuste para a sua rota exata
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
                if (!confirm(
                        'Tem certeza que deseja excluir este veículo? Esta ação não pode ser desfeita.')) {
                    e.preventDefault();
                }
            });

            // 4. Máscara Inteligente para Placa (Mercosul e Antiga)
            $('#input-plate').on('input', function() {
                // Pega o valor, converte para maiúsculo e remove caracteres especiais
                let val = $(this).val().toUpperCase().replace(/[^A-Z0-9]/g, '');

                let formatada = '';

                for (let i = 0; i < val.length; i++) {
                    let char = val[i];

                    // Posições 1, 2 e 3: Apenas letras
                    if (i < 3) {
                        if (/[A-Z]/.test(char)) {
                            formatada += char;
                        } else {
                            break; // Bloqueia a continuação se for número/símbolo
                        }
                    }
                    // Posição 4: Acrescenta o hífen e aceita apenas número
                    else if (i === 3) {
                        if (/[0-9]/.test(char)) {
                            formatada += '-' + char;
                        } else {
                            break;
                        }
                    }
                    // Posição 5: Aceita Letra ou Número (Mercosul ou Antiga)
                    else if (i === 4) {
                        if (/[A-Z0-9]/.test(char)) {
                            formatada += char;
                        } else {
                            break;
                        }
                    }
                    // Posições 6 e 7: Apenas números
                    else if (i < 7) {
                        if (/[0-9]/.test(char)) {
                            formatada += char;
                        } else {
                            break;
                        }
                    }
                }

                // Atualiza o campo com a string tratada
                $(this).val(formatada);
            });
        });
    </script>
@endsection
