<x-guest-layout title="Cadastro">

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="text-center mb-4">
        <h4 class="fw-bold mb-2">Cadastro</h4>

        <p class="text-body-secondary mb-0">
            Informe seus dados para se registrar no Gestor de Frota.
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" id="registerForm" novalidate>
        @csrf

        {{-- Nome --}}
        <div class="mb-3">
            <x-input-label for="name" :value="__('Nome')" />

            <x-text-input id="name" type="text" name="name" :value="old('name')" :error="$errors->has('name')" required
                autofocus autocomplete="name" />

            <div class="invalid-feedback" id="nameLength">
                O nome deve ter ao menos 5 caracteres.
            </div>

            <x-input-error :messages="$errors->get('name')" />
        </div>

        {{-- E-mail --}}
        <div class="mb-3">
            <x-input-label for="email" :value="__('E-mail')" />

            <x-text-input id="email" type="email" name="email" :value="old('email')" :error="$errors->has('email')"
                placeholder="seu_email@unifesp.br" required autocomplete="username" />

            <div class="invalid-feedback" id="emailStrengthError">
                O e-mail deve ser um endereço @unifesp.br.
            </div>

            <x-input-error :messages="$errors->get('email')" />
        </div>

        {{-- Senha --}}
        <div class="mb-3">
            <x-input-label for="password" :value="__('Senha')" />

            <x-text-input id="password" type="password" name="password" :error="$errors->has('password')" required
                autocomplete="new-password" />

            <x-password-requirements />

            <x-input-error :messages="$errors->get('password')" />
        </div>

        {{-- Confirmação de senha --}}
        <div class="mb-3">
            <x-input-label for="password_confirmation" :value="__('Confirmar Senha')" />

            <x-text-input id="password_confirmation" type="password" name="password_confirmation" required
                autocomplete="new-password" />

            <div class="invalid-feedback" id="passwordMatchError">
                As senhas não coincidem.
            </div>

            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <div class="d-grid mt-4">
            <x-primary-button id="btnSubmit">
                Cadastrar
            </x-primary-button>
        </div>

        <div class="text-center mt-3">
            <span class="text-body-secondary small">
                Já possui uma conta?
            </span>

            <a href="{{ route('login') }}" class="text-decoration-none fw-semibold small">
                Entrar
            </a>
        </div>

    </form>

    @push('scripts')
        @vite('resources/js/register.js')
    @endpush

</x-guest-layout>
