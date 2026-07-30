import $ from "jquery";

$(function () {
    const $email = $("#email");
    const $emailError = $("#emailError");
    const $form = $("#forgotPasswordForm");
    const $btnSubmit = $("#btnSubmit");

    let emailBlurred = false;

    // Função para validar o formato do e-mail usando Regex
    function validateEmail() {
        const emailVal = $email.val().trim();
        const dominioPermitido = "unifesp.br";
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const hasDominio = emailVal.split("@")[1];

        if (emailVal.length === 0) {
            $email.addClass("is-invalid").removeClass("is-valid");
            $emailError.text("O campo de e-mail é obrigatório.").show();
            return false;
        } else if (!emailPattern.test(emailVal)) {
            $email.addClass("is-invalid").removeClass("is-valid");
            $emailError
                .text("Por favor, insira um endereço de e-mail válido.")
                .show();
            return false;
        } else if (hasDominio !== dominioPermitido) {
            $email.addClass("is-invalid").removeClass("is-valid");
            $emailError.text("O email precisa ser @unifesp.br").show();
            return false;
        } else {
            $email.addClass("is-valid").removeClass("is-invalid");
            $emailError.hide();
            return true;
        }
    }

    // Valida ao perder o foco (blur)
    $email.on("blur", function () {
        if ($email.val().trim().length > 0) {
            emailBlurred = true;
            validateEmail();
        }
    });

    // Valida em tempo real enquanto digita (apenas se já tiver perdido o foco uma vez)
    $email.on("input", function () {
        if (emailBlurred) {
            validateEmail();
        }
    });

    // Envio do Formulário
    $form.on("submit", function (e) {
        emailBlurred = true;

        // Executa a validação antes do envio
        if (!validateEmail()) {
            e.preventDefault();
            $email.focus();
            return false;
        }

        // Delay de segurança para permitir que o navegador dispare a requisição POST
        setTimeout(function () {
            $btnSubmit.prop("disabled", true);
        }, 50);
    });
});
