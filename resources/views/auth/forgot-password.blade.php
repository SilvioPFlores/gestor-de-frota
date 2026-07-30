
<x-guest-layout title="Recuperar Senha">

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="text-center mb-4">
        <h4 class="fw-bold mb-2">Recuperar Senha</h4>

        <p class="text-body-secondary mb-0">
            Esqueceu a senha? Sem problemas. Informe-nos seu e-mail e enviaremos um link para redefinir a senha que permitirá escolher uma nova.
        </p>
    </div>

    <form method="POST" action="{{ route('password.email') }}" id="forgotPasswordForm" novalidate>
        @csrf
        
        {{-- E-mail --}}
        <div class="mb-3">
            <x-input-label for="email" :value="__('E-mail')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" :error="$errors->has('email')" required/>

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