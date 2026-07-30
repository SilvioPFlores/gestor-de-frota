<x-guest-layout title="Verificar e-mail">

    <div class="text-center mb-4">
        <div class="mb-3">
            <i class="fa-solid fa-envelope-circle-check fa-3x text-primary"></i>
        </div>
        <h4 class="fw-bold mb-2">
            Verifique seu e-mail
        </h4>
        <p class="text-body-secondary mb-0">
            Enviamos um link de confirmação para o endereço de e-mail
            informado no cadastro.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success small d-flex align-items-center gap-2" role="alert">
            <i class="fa-solid fa-circle-check"></i>
            <div>
                Um novo link de verificação foi enviado para o seu
                endereço de e-mail.
            </div>
        </div>
    @endif

    <div class="alert alert-light border text-body-secondary small" role="alert">
        <div class="d-flex gap-2">
            <i class="fa-solid fa-circle-info mt-1"></i>
            <div>
                Acesse sua caixa de entrada e clique no link recebido
                para confirmar seu endereço de e-mail.
                <br>
                <span class="text-body-secondary">
                    Caso não encontre a mensagem, verifique também a
                    pasta de spam ou lixo eletrônico.
                </span>
            </div>
        </div>
    </div>

    <div class="d-grid gap-2 mt-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary w-100">
                <i class="fa-solid fa-paper-plane me-2"></i>
                Reenviar e-mail de verificação
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-secondary w-100">
                <i class="fa-solid fa-right-from-bracket me-2"></i>
                Sair
            </button>
        </form>
    </div>

</x-guest-layout>
