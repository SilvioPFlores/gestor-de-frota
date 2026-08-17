@extends('layouts.app')

@section('title', 'Gerenciamento de Usuários')

@section('content')
    <div class="container-fluid">

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <p class="text-muted small mb-0">Gerencie os níveis e permissões de acesso dos colaboradores.</p>
            </div>
        </div>

        <!-- Alertas de Feedback -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        @endif

        <!-- Card Principal com Tabela -->
        <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead class="custom-table-header border-bottom">
                            <tr>
                                <th class="ps-4 py-3 text-uppercase fs-8 fw-semibold">Nome</th>
                                <th class="py-3 text-uppercase fs-8 fw-semibold">E-mail</th>
                                <th class="py-3 text-uppercase fs-8 fw-semibold">Nível Atual</th>
                                <th class="pe-4 py-3 text-uppercase fs-8 fw-semibold text-end">Alterar Nível</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td class="ps-4 fw-medium">
                                        {{ $user->name }}
                                    </td>
                                    <td class="text-muted">
                                        {{ $user->email }}
                                    </td>
                                    <td>
                                        @php
                                            $roleName = $user->roles->first()?->name ?? 'Sem Nível';

                                            // Estilização das Badges usando cores do Bootstrap 5
                                            $badgeClass = match ($roleName) {
                                                'Admin' => 'bg-danger-subtle text-danger border border-danger-subtle',
                                                'Gestor' => 'bg-success-subtle text-success border border-success-subtle',
                                                'Motorista' => 'bg-primary-subtle text-primary border border-primary-subtle',
                                                default
                                                    => 'bg-secondary-subtle text-secondary border border-secondary-subtle',
                                            };
                                        @endphp
                                        <span class="badge rounded-pill px-3 py-2 fw-semibold {{ $badgeClass }}">
                                            {{ $roleName }}
                                        </span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <form action="{{ route('users.update', $user) }}" method="POST"
                                            class="d-inline-flex align-items-center justify-content-end gap-2">
                                            @csrf
                                            @method('PUT')

                                            <select name="role" class="form-select form-select-sm w-auto">
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->name }}"
                                                        {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                                        {{ $role->name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <button type="submit"
                                                class="btn btn-primary btn-sm px-3 d-flex align-items-center gap-1 shadow-sm">
                                                <i class="fa-solid fa-floppy-disk"></i> Salvar
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

    @push('scripts')
        <script type="module">
            $(document).ready(function() {
                // Esconde os alertas de sucesso ou erro automaticamente após 4 segundos
                setTimeout(function() {
                    $('.alert').fadeOut('slow');
                }, 4000);
            });
        </script>
    @endpush
@endsection

