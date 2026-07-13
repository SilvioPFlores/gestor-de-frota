<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de Motoristas</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">

    <div class="container py-5">

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-sm p-4" style="width: 100%; max-width: 400px; border-radius: 10px;">
        
        <div class="d-flex justify-content-center align-items-center gap-3 mb-4 fw-semibold text-uppercase small">
            <a href="{{ route('login') }}" class="pb-1 text-decoration-none {{ request()->routeIs('login') ? 'text-primary border-bottom border-2 border-primary' : 'text-muted' }}">
                Login
            </a>
            <span class="text-secondary opacity-50">|</span>
            <a href="{{ route('register') }}" class="pb-1 text-decoration-none {{ request()->routeIs('register') ? 'text-primary border-bottom border-2 border-primary' : 'text-muted' }}">
                Registro
            </a>
        </div>

        @if (session('status'))
            <div class="alert alert-success mb-3 small" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label text-secondary small fw-bold">E-mail</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                       class="form-control @error('email') is-invalid @enderror">
                
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label text-secondary small fw-bold">Senha</label>
                <input id="password" type="password" name="password" required autocomplete="current-password" 
                       class="form-control @error('password') is-invalid @enderror">
                
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-check mb-3">
                <input id="remember_me" type="checkbox" name="remember" class="form-check-input">
                <label for="remember_me" class="form-check-label small text-muted">
                    Lembrar de mim
                </label>
            </div>

            <div class="d-flex align-items-center justify-content-between mt-4">
                @if (Route::has('password.request'))
                    <a class="text-muted small text-decoration-underline" href="{{ route('password.request') }}">
                        Esqueceu sua senha?
                    </a>
                @endif

                <button type="submit" class="btn btn-primary px-4 shadow-sm" id="btnSubmit">
                    Entrar
                </button>
            </div>
        </form>
        
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Exemplo de uso do jQuery: Efeito visual de loading ao enviar o formulário
        $('#loginForm').on('submit', function() {
            let $btn = $('#btnSubmit');
            
            // Desabilita o botão para evitar cliques duplos e adiciona um spinner do Bootstrap
            $btn.prop('disabled', true)
                .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Aguarde...');
        });
    });
</script>
@endpush
</body>
</html>