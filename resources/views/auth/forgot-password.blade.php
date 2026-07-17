<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-sm p-4" style="width: 100%; max-width: 400px; border-radius: 10px; background: #fff;">

            <div class="text-center mb-4">
                <h5 class="fw-bold text-dark mb-2">Recuperar Senha</h5>
                <p class="text-secondary small mb-0">
                    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                </p>
            </div>

            @if (session('status'))
                <div class="alert alert-success mb-3 small text-center" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" id="forgotPasswordForm" novalidate>
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label text-secondary small fw-bold">E-mail</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="form-control @error('email') is-invalid @enderror">
                    
                    <div class="invalid-feedback" id="emailError">
                        Por favor, insira um endereço de e-mail válido.
                    </div>

                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary w-100 px-4 shadow-sm" id="btnSubmit">
                        {{ __('Email Password Reset Link') }}
                    </button>
                </div>

                <div class="text-center mt-3">
                    <a href="{{ route('login') }}" class="text-muted small text-decoration-none">
                        &larr; Voltar para o Login
                    </a>
                </div>
            </form>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            const $email = $('#email');
            const $emailError = $('#emailError');
            const $form = $('#forgotPasswordForm');
            const $btnSubmit = $('#btnSubmit');

            let emailBlurred = false;

            // Função para validar o formato do e-mail usando Regex
            function validateEmail() {
                const emailVal = $email.val().trim();
                const dominioPermitido = "unifesp.br";
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                const hasDominio = emailVal.split('@')[1];

                if (emailVal.length === 0) {
                    $email.addClass('is-invalid').removeClass('is-valid');
                    $emailError.text('O campo de e-mail é obrigatório.').show();
                    return false;
                } else if (!emailPattern.test(emailVal)) {
                    $email.addClass('is-invalid').removeClass('is-valid');
                    $emailError.text('Por favor, insira um endereço de e-mail válido.').show();
                    return false;
                } else if (hasDominio !== dominioPermitido){
                    $email.addClass('is-invalid').removeClass('is-valid');
                    $emailError.text('O email precisa ser @unifesp.br').show();
                    return false;
                } else {
                    $email.addClass('is-valid').removeClass('is-invalid');
                    $emailError.hide();
                    return true;
                }
            }

            // Valida ao perder o foco (blur)
            $email.on('blur', function() {
                emailBlurred = true;
                validateEmail();
            });

            // Valida em tempo real enquanto digita (apenas se já tiver perdido o foco uma vez)
            $email.on('input', function() {
                if (emailBlurred) {
                    validateEmail();
                }
            });

            // Envio do Formulário
            $form.on('submit', function(e) {
                emailBlurred = true;
                
                // Executa a validação antes do envio
                if (!validateEmail()) {
                    e.preventDefault();
                    $email.focus();
                    return false;
                }

                // Efeito visual no botão
                $btnSubmit.html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enviando...'
                );

                // Delay de segurança para permitir que o navegador dispare a requisição POST
                setTimeout(function() {
                    $btnSubmit.prop('disabled', true);
                }, 50);
            });
        });
    </script>
</body>

</html>