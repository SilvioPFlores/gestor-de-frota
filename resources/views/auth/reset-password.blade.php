<x-guest-layout title="Redefinir Senha">

    <div class="text-center mb-4">
        <div class="mb-3">
            <i class="fa-solid fa-key fa-3x text-primary"></i>
        </div>
        <h4 class="fw-bold mb-2">
            Redefinir senha
        </h4>
        <p class="text-body-secondary small mb-0">
            Crie uma nova senha para acessar sua conta.
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" id="resetPasswordForm" novalidate>
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email', $request->email)" :error="$errors->has('email')"
                placeholder="seu_email@unifesp.br" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-3">
            <x-input-label for="password" :value="__('Nova senha')" />

            <x-text-input id="password" type="password" name="password" :error="$errors->has('password')" required
                autocomplete="new-password" />

            <x-password-requirements />

            <x-input-error :messages="$errors->get('password')" />
        </div>

        <!-- Confirm Password -->
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
                <i class="fa-solid fa-key me-2"></i>
                Redefinir senha
            </x-primary-button>
        </div>
    </form>

    @push('scripts') 
        @vite('resources/js/reset-password.js') 
    @endpush

</x-guest-layout>
