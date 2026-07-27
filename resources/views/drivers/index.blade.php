@extends('layouts.app')

@section('title', 'Motoristas')

@section('content')
    <div class="container-fluid">

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <p class="text-muted small mb-0">Cadastro de condutores e validade de CNH.</p>
            </div>
            <button type="button" class="btn btn-outline-secondary" id="btn-novo-motorista">
                <i class="fa-solid fa-plus me-1 icon"></i>Novo motorista
            </button>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger rounded-3" role="alert">
                <h6 class="fw-bold">Por favor, corrija os erros abaixo:</h6>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-sm">
                    <thead>
                        <tr>
                            <th class="px-4 py-3">Nome</th>
                            <th class="py-3">CPF</th>
                            <th class="py-3">CNH</th>
                            <th class="py-3">Cat.</th>
                            <th class="py-3">Validade</th>
                            <th class="py-3">Status</th>
                            <th class="px-4 py-3 text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($drivers as $driver)
                            <tr>
                                <td class="px-4 py-3 fw-bold">{{ $driver->name }}</td>
                                <td>{{ $driver->cpf }}</td>
                                <td>{{ $driver->cnh }}</td>
                                <td class="text-uppercase">{{ $driver->cnh_category }}</td>
                                <td>{{ \Carbon\Carbon::parse($driver->cnh_expiration)->format('d/m/Y') }}</td>
                                <td>
                                    @if ($driver->is_active)
                                        <span
                                            class="badge bg-success-subtle text-success px-2.5 py-1.5 rounded-pill">Ativo</span>
                                    @else
                                        <span
                                            class="badge bg-danger-subtle text-danger px-2.5 py-1.5 rounded-pill">Inativo</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-end">
                                    <div class="d-flex justify-content-end align-items-center flex-nowrap gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary btn-editar"
                                            data-driver="{{ $driver->toJson() }}" title="Editar motorista">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <form action="{{ route('drivers.destroy', $driver) }}" method="POST"
                                            class="m-0 form-delete" data-name="{{ $driver->name }}">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                title="Excluir motorista">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    Nenhum motorista cadastrado no momento.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-motorista" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="modal-title">Novo motorista</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form-motorista" action="{{ route('drivers.store') }}" method="POST">
                        @csrf
                        <div id="method-container"></div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small">Nome completo</label>
                            <input type="text" id="input-name" name="name" required class="form-control rounded-3">
                            <div class="invalid-feedback">O Nome informado é inválido.</div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold text-secondary small">CPF</label>
                                <input type="text" id="input-cpf" name="cpf" required placeholder="000.000.000-00"
                                    class="form-control rounded-3">
                                <div class="invalid-feedback">O CPF informado é inválido.</div>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold text-secondary small">Telefone</label>
                                <input type="text" id="input-phone" name="phone" placeholder="(00) 00000-0000"
                                    class="form-control rounded-3">
                                <div class="invalid-feedback">O telefone informado é inválido.</div>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold text-secondary small">CNH</label>
                                <input type="text" id="input-cnh" name="cnh" required placeholder="Apenas números"
                                    class="form-control rounded-3">
                                <div class="invalid-feedback">A CNH informada é inválida.</div>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold text-secondary small">Categoria</label>
                                <input type="text" id="input-cnh_category" name="cnh_category" required
                                    placeholder="B, D, AE..." class="form-control rounded-3 text-uppercase">
                                <div class="invalid-feedback">A categoria informada é inválida.</div>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold text-secondary small">Validade CNH</label>
                                <input type="date" id="input-cnh_expiration" name="cnh_expiration" required
                                    class="form-control rounded-3">
                                <div class="invalid-feedback">A validade é menor que 30 dias.</div>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold text-secondary small">Email</label>
                                <input type="email" id="input-email" name="email" class="form-control rounded-3">
                                <div class="invalid-feedback">O Email informado é invalido.</div>
                            </div>
                        </div>

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" role="switch" id="input-is_active"
                                name="is_active" value="1" checked>
                            <label class="form-check-label fw-semibold text-secondary small"
                                for="input-is_active">Motorista Ativo</label>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-outline-secondary px-4 rounded-3">Salvar
                                Cadastro</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/drivers.js')

        <script>
            window.app = {
                routes: {
                    drivers: {
                        store: "{{ route('drivers.store') }}",
                        base: "{{ url('motoristas') }}"
                    }
                }
            };
        </script>
    @endpush
@endsection
