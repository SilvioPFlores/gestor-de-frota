<x-guest-layout title="Entrar">
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="text-center mb-4">
        <h4 class="fw-bold mb-2">Acessar o sistema</h4>
        <p class="text-body-secondary mb-0">
            Informe seu e-mail e senha para entrar no Gestor de Frota.
        </p>
    </div>

    <form method="POST" action="{{ route('login') }}" id="loginForm">
        @csrf

        <div class="mb-3">
            <x-input-label for="email" :value="__('E-mail')" />

            <x-text-input id="email" type="email" name="email" :value="old('email')" :error="$errors->has('email')" required
                autofocus autocomplete="username" />

            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <x-input-label for="password" :value="__('Senha')" />

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="small text-decoration-none">
                        Esqueceu a senha?
                    </a>
                @endif
            </div>

            <x-text-input id="password" type="password" name="password" :error="$errors->has('password')" required
                autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" id="remember_me" name="remember">

            <label class="form-check-label" for="remember_me">
                Lembrar de mim
            </label>
        </div>

        <div class="d-grid">
            <x-primary-button id="btnSubmit">
                Entrar
            </x-primary-button>
        </div>

        @if (Route::has('register'))
            <div class="text-center mt-4">
                <span class="text-body-secondary">
                    Ainda não possui uma conta?
                </span>

                <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">
                    Cadastre-se
                </a>
            </div>
        @endif

    </form>
</x-guest-layout>
