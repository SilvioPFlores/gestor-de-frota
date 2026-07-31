<x-guest-layout title="Recuperar Senha">

    <div class="text-center mb-4">
        <div class="mb-3">
            <i class="fa-solid fa-key fa-3x text-primary"></i> 
        </div>
        <h4 class="fw-bold mb-2"> Recuperar senha </h4>
        <p class="text-body-secondary small mb-0">
            Informe seu endereço de e-mail e enviaremos um link para você criar uma nova senha.
        </p>
    </div>

    @if (session('status'))
        <div class="alert alert-success small d-flex align-items-center gap-2" role="alert">
            <i class="fa-solid fa-circle-check"></i>
            <div> {{ session('status') }} </div>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" id="forgotPasswordForm" novalidate>
        @csrf

        {{-- E-mail --}}
        <div class="mb-3">
            <x-input-label for="email" :value="__('E-mail')" />

            <x-text-input id="email" type="email" name="email" :value="old('email')" :error="$errors->has('email')" required z />

            <div class="invalid-feedback" id="emailStrengthError">
                Por favor, insira um endereço de e-mail válido.
            </div>

            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="d-grid mt-4">
            <x-primary-button id="btnSubmit">
                E-mail para redefinir a senha
            </x-primary-button>
        </div>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="text-muted small text-decoration-none">
                &larr; Voltar para o Login
            </a>
        </div>
    </form>

    @push('scripts')
        @vite('resources/js/forgot-password.js')
    @endpush

</x-guest-layout>
