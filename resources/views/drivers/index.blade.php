@extends('layouts.app')

@section('titulo', 'Motoristas')

@section('content')
    <div class="container-fluid"> 
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-0">Motoristas</h2>
                <small class="text-muted">Cadastro de condutores e validade de CNH.</small>
            </div>
            <button id="btn-novo-motorista" class="btn btn-dark px-4 py-2 rounded-3 fw-medium">
                + Novo motorista
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger rounded-3" role="alert">
                <h6 class="fw-bold">Por favor, corrija os erros abaixo:</h6>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-sm">
                    <thead class="table-light text-secondary">
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
                                <td class="px-4 py-3 fw-bold text-dark">{{ $driver->name }}</td>
                                <td>{{ $driver->cpf }}</td>
                                <td>{{ $driver->cnh }}</td>
                                <td class="text-uppercase">{{ $driver->cnh_category }}</td>
                                <td>{{ \Carbon\Carbon::parse($driver->cnh_expiration)->format('d/m/Y') }}</td>
                                <td>
                                    @if($driver->is_active)
                                        <span class="badge bg-success-subtle text-success px-2.5 py-1.5 rounded-pill">Ativo</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger px-2.5 py-1.5 rounded-pill">Inativo</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-end">
                                    <button class="btn btn-link text-secondary p-1 btn-editar" data-driver="{{ $driver->toJson() }}">✏️</button>
                                    
                                    <form action="{{ route('drivers.destroy', $driver) }}" method="POST" class="d-inline" onsubmit="return confirm('Deseja excluir {{ $driver->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-secondary p-1">🗑️</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
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
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold text-secondary small">CPF</label>
                                <input type="text" id="input-cpf" name="cpf" required placeholder="000.000.000-00" class="form-control rounded-3">
                                <div class="invalid-feedback">O CPF informado é inválido.</div>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold text-secondary small">Telefone</label>
                                <input type="text" id="input-phone" name="phone" placeholder="(00) 00000-0000" class="form-control rounded-3">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold text-secondary small">CNH</label>
                                <input type="text" id="input-cnh" name="cnh" required placeholder="Apenas números" class="form-control rounded-3">
                                <div class="invalid-feedback">A CNH informada é inválida.</div>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold text-secondary small">Categoria</label>
                                <input type="text" id="input-cnh_category" name="cnh_category" required placeholder="B, D..." class="form-control rounded-3 text-uppercase">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold text-secondary small">Validade CNH</label>
                                <input type="date" id="input-cnh_expiration" name="cnh_expiration" required class="form-control rounded-3">
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold text-secondary small">Email</label>
                                <input type="email" id="input-email" name="email" class="form-control rounded-3">
                            </div>
                        </div>

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" role="switch" id="input-is_active" name="is_active" value="1" checked>
                            <label class="form-check-label fw-semibold text-secondary small" for="input-is_active">Motorista Ativo</label>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-dark px-4 rounded-3">Salvar Cadastro</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // Instancia o modal usando a instância global do Bootstrap compilada pelo Vite
            const motoristaModal = new bootstrap.Modal('#modal-motorista');

            // Gatilho para Novo Motorista
            $('#btn-novo-motorista').on('click', function() {
                $('#modal-title').text('Novo motorista');
                $('#form-motorista').attr('action', "{{ route('drivers.store') }}");
                $('#method-container').empty();
                $('#form-motorista')[0].reset();
                $('#input-is_active').prop('checked', true);
                
                limparValidacao($('#input-cpf'));
                limparValidacao($('#input-cnh'));
                
                motoristaModal.show();
            });

            // Gatilho para Editar Motorista
            $(document).on('click', '.btn-editar', function() {
                const driver = $(this).data('driver');

                $('#modal-title').text('Editar motorista: ' + driver.name);
                $('#form-motorista').attr('action', `/motoristas/${driver.id}`);
                $('#method-container').html('<input type="hidden" name="_method" value="PUT">';

                $('#input-name').val(driver.name);
                $('#input-cpf').val(driver.cpf);
                $('#input-phone').val(driver.phone || '');
                $('#input-cnh').val(driver.cnh);
                $('#input-cnh_category').val(driver.cnh_category);
                
                if(driver.cnh_expiration) {
                    $('#input-cnh_expiration').val(driver.cnh_expiration.split('T')[0]);
                }
                $('#input-email').val(driver.email || '');
                $('#input-is_active').prop('checked', !!driver.is_active);

                limparValidacao($('#input-cpf'));
                limparValidacao($('#input-cnh'));

                motoristaModal.show();
            });

            function aplicarValido(elemento) {
                elemento.addClass('is-valid').removeClass('is-invalid');
            }
            function aplicarInvalido(elemento) {
                elemento.addClass('is-invalid').removeClass('is-valid');
            }
            function limparValidacao(elemento) {
                elemento.removeClass('is-valid is-invalid');
            }

            // ==========================================
            // MÁSCARAS E VALIDAÇÕES EM REAL-TIME
            // ==========================================

            $('#input-cpf').on('input', function() {
                let v = $(this).val().replace(/\D/g, '');
                if (v.length > 11) v = v.slice(0, 11);
                v = v.replace(/(\d{3})(\d)/, '$1.$2');
                v = v.replace(/(\d{3})(\d)/, '$1.$2');
                v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                $(this).val(v);
            }).on('blur', function() {
                const val = $(this).val();
                if (val === '') { limparValidacao($(this)); return; }
                checkCPF(val) ? aplicarValido($(this)) : aplicarInvalido($(this));
            });

            $('#input-cnh').on('input', function() {
                let v = $(this).val().replace(/\D/g, '');
                if (v.length > 11) v = v.slice(0, 11);
                $(this).val(v);
            }).on('blur', function() {
                const val = $(this).val();
                if (val === '') { limparValidacao($(this)); return; }
                checkCNH(val) ? aplicarValido($(this)) : aplicarInvalido($(this));
            });

            $('#input-phone').on('input', function() {
                let v = $(this).val().replace(/\D/g, '');
                if (v.length > 11) v = v.slice(0, 11);
                v = v.replace(/^(\d{2})(\d)/g, '($1) $2');
                v = v.replace(/(\d)(\d{4})$/, '$1-$2');
                $(this).val(v);
            });

            function checkCPF(cpf) {
                cpf = cpf.replace(/[^\d]+/g, '');
                if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) return false;
                let soma = 0, resto;
                for (let i = 1; i <= 9; i++) soma = soma + parseInt(cpf.substring(i - 1, i)) * (11 - i);
                resto = (soma * 10) % 11;
                if ((resto === 10) || (resto === 11)) resto = 0;
                if (resto !== parseInt(cpf.substring(9, 10))) return false;
                soma = 0;
                for (let i = 1; i <= 10; i++) soma = soma + parseInt(cpf.substring(i - 1, i)) * (12 - i);
                resto = (soma * 10) % 11;
                if ((resto === 10) || (resto === 11)) resto = 0;
                return resto === parseInt(cpf.substring(10, 11));
            }

            function checkCNH(cnh) {
                cnh = cnh.replace(/[^\d]+/g, '');
                if (cnh.length !== 11 || /^(\d)\1{10}$/.test(cnh)) return false;
                let sum1 = 0;
                for (let i = 0, j = 9; i < 9; i++, j--) sum1 += parseInt(cnh.charAt(i)) * j;
                let dv1 = sum1 % 11;
                let dsc = 0;
                if (dv1 > 9) { dv1 = 0; dsc = 2; }
                let sum2 = 0;
                for (let i = 0, j = 1; i < 9; i++, j++) sum2 += parseInt(cnh.charAt(i)) * j;
                let dv2 = sum2 % 11;
                if (dv2 > 9) { dv2 = 0; } else { dv2 = dv2 - dsc; if (dv2 < 0) dv2 += 11; }
                return parseInt(cnh.charAt(9)) === dv1 && parseInt(cnh.charAt(10)) === dv2;
            }
        });
    </script>
@endsection