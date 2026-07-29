<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .legend-size {
            font-size: .8em;
        }

        .normal {
            color: #444;
        }

        .valido {
            color: #169f31;
        }

        .invalido {
            color: #c72121;
        }
    </style>
</head>

<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-sm p-4" style="width: 100%; max-width: 400px; border-radius: 10px; background: #fff;">

            <div class="d-flex justify-content-center align-items-center gap-3 mb-4 fw-semibold text-uppercase small">
                <a href="{{ route('login') }}"
                    class="pb-1 text-decoration-none {{ request()->routeIs('login') ? 'text-primary border-bottom border-2 border-primary' : 'text-muted' }}">
                    Login
                </a>
                <span class="text-secondary opacity-50">|</span>
                <a href="{{ route('register') }}"
                    class="pb-1 text-decoration-none {{ request()->routeIs('register') ? 'text-primary border-bottom border-2 border-primary' : 'text-muted' }}">
                    Registro
                </a>
            </div>

            <form method="POST" action="{{ route('register') }}" id="registerForm" novalidate>
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label text-secondary small fw-bold">Nome</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                        autocomplete="name" class="form-control @error('name') is-invalid @enderror">

                    <div class="invalid-feedback" id="nameLength">
                        O nome deve ter ao menos 5 letras
                    </div>

                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label text-secondary small fw-bold">E-mail</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                        placeholder="seu_email@unifesp.br" autocomplete="username"
                        class="form-control @error('email') is-invalid @enderror">

                    <div class="invalid-feedback" id="emailStrengthError">
                        O email deve ter @ e ser unifesp.br.
                    </div>

                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label text-secondary small fw-bold">Senha</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                        class="form-control @error('password') is-invalid @enderror">


                    <div class="legend-size normal" id="idDivLegenda">A senha deve ter pelo menos</div>
                    <div class="legend-size normal" id="idDivLength">8 caracteres</div>
                    <div class="legend-size normal" id="idDivUpper">Uma letra maiúscula</div>
                    <div class="legend-size normal" id="idDivLower">Uma letra minúscula</div>
                    <div class="legend-size normal" id="idDivNumber">Um numero</div>
                    <div class="legend-size normal" id="idDivSpecial">Um caracter especial</div>

                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label text-secondary small fw-bold">Confirmar
                        Senha</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        autocomplete="new-password" class="form-control">

                    <div class="invalid-feedback" id="passwordMatchError">
                        As senhas não coincidem.
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary px-4 shadow-sm w-100" id="btnSubmit">
                        Cadastrar
                    </button>
                </div>
            </form>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {

            const $name = $('#name');
            const $nameLength = $('#nameLength');
            const $email = $('#email');
            const $strengthError = $('#emailStrengthError');
            const $password = $('#password');
            const $confirmPassword = $('#password_confirmation');
            const $matchError = $('#passwordMatchError');
            const $form = $('#registerForm');
            const $btnSubmit = $('#btnSubmit');

            let passwordBlurred = false;
            let confirmBlurred = false;

            // Valida nome
            function validateName() {
                const name = $name.val();
                if (name.length < 5) {
                    $name.addClass('is-invalid').removeClass('is-valid');
                    $nameLength.show();
                    return false;
                } else {
                    $name.addClass('is-valid').removeClass('is-invalid');
                    $nameLength.hide();
                    return true;
                }
            }

            // Valida email
            function validateEmail() {
                const email = $email.val().trim();
                const dominioPermitido = "unifesp.br";
                const hasEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
                const hasDominio = email.split('@')[1];

                let missingDetails = [];

                if (!hasEmail) missingDetails.push("ter @");
                if (hasDominio !== dominioPermitido) missingDetails.push("ser " + dominioPermitido);

                if (missingDetails.length > 0) {
                    $email.addClass('is-invalid').removeClass('is-valid');
                    $strengthError.text("O email precisa " + missingDetails.join(', ') + ".").show();
                    return false;
                } else {
                    $email.addClass('is-valid').removeClass('is-invalid');
                    $strengthError.hide();
                    return true;
                }

            }

            // 1. Valida força da senha
            function validatePasswordStrength() {
                const val = $password.val();
                const minLength = val.length >= 8;
                const hasUppercase = /[A-Z]/.test(val);
                const hasLowercase = /[a-z]/.test(val);
                const hasNumber = /[0-9]/.test(val);
                const hasSpecial = /[!@#$%^&*(),.?":{}|/<>]/.test(val);

                let missingDetails = [];
                if (!minLength) {
                    missingDetails.push("1");
                    $('#idDivLength').addClass('invalido').removeClass('valido normal');
                } else {
                    $('#idDivLength').addClass('valido').removeClass('invalido normal');
                }
                if (!hasUppercase) {
                    missingDetails.push("2");
                    $('#idDivUpper').addClass('invalido').removeClass('valido normal');
                } else {
                    $('#idDivUpper').addClass('valido').removeClass('invalido normal');
                }
                if (!hasLowercase) {
                    missingDetails.push("3");
                    $('#idDivLower').addClass('invalido').removeClass('valido normal');
                } else {
                    $('#idDivLower').addClass('valido').removeClass('invalido normal');
                }
                if (!hasNumber) {
                    missingDetails.push("4");
                    $('#idDivNumber').addClass('invalido').removeClass('valido normal');
                } else {
                    $('#idDivNumber').addClass('valido').removeClass('invalido normal');
                }
                if (!hasSpecial) {
                    missingDetails.push("5");
                    $('#idDivSpecial').addClass('invalido').removeClass('valido normal');
                } else {
                    $('#idDivSpecial').addClass('valido').removeClass('invalido normal');
                }

                if (missingDetails.length > 0) {
                    $password.addClass('is-invalid').removeClass('is-valid');
                    return false;
                } else {
                    $password.addClass('is-valid').removeClass('is-invalid');
                    return true;
                }
            }

            // 2. Valida igualdade
            function validatePasswordMatch() {
                const passVal = $password.val();
                const confirmVal = $confirmPassword.val();

                if (confirmVal.length === 0) {
                    $confirmPassword.removeClass('is-valid is-invalid');
                    $matchError.hide();
                    return false;
                }

                if (passVal !== confirmVal) {
                    $confirmPassword.addClass('is-invalid').removeClass('is-valid');
                    $matchError.show();
                    return false;
                } else {
                    $confirmPassword.addClass('is-valid').removeClass('is-invalid');
                    $matchError.hide();
                    return true;
                }
            }

            $name.on('blur', function() {
                if ($name.val().length > 0) {
                    validateName();
                }
            });

            $email.on('blur', function() {
                if ($email.val().length > 0) {
                    validateEmail();
                }
            })

            $password.on('keyup', function() {
                passwordBlurred = true;
                validatePasswordStrength();
                if (confirmBlurred) validatePasswordMatch();
            });

            $confirmPassword.on('blur', function() {
                if ($confirmPassword.val().length > 0) {
                    confirmBlurred = true;
                    validatePasswordMatch();
                }
            });

            // Validações em tempo real pós-blur
            $password.on('input', function() {
                if (passwordBlurred) validatePasswordStrength();
                if (confirmBlurred) validatePasswordMatch();
            });

            $confirmPassword.on('input', function() {
                if (confirmBlurred) validatePasswordMatch();
            });

            // Submit
            $form.on('submit', function(e) {
                passwordBlurred = true;
                confirmBlurred = true;
                console.log('submit');
                const isNameOk = validateName();
                const isStrengthOk = validateEmail();
                const isPasswordOk = validatePasswordStrength();
                const isMatchOk = validatePasswordMatch();

                if (!isNameOk || !isStrengthOk || !isPasswordOk || !isMatchOk) {
                    e.preventDefault();
                    if (!isNameOk) {
                        $name.focus();
                    } else if (!isStrengthOk) {
                        $email.focus();
                    } else if (!isPasswordOk) {
                        $password.focus();
                    } else if (!isMatchOk) {
                        $confirmPassword.focus();
                    }
                    return false;
                }

                $btnSubmit.html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Cadastrando...'
                );

                // 2. Desabilita o botão uma fração de segundo DEPOIS
                // Isso evita que o navegador cancele o envio do formulário!
                setTimeout(function() {
                    $btnSubmit.prop('disabled', true);
                }, 50);
            });
        });
    </script>
</body>

</html>
