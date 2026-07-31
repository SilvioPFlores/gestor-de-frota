import $ from "jquery";
import { createPasswordValidator } from "./password-validation";

$(function () {
    const $name = $("#name");
    const $nameLength = $("#nameLength");

    const $email = $("#email");
    const $emailStrengthError = $("#emailStrengthError");

    const $password = $("#password");
    const $confirmPassword = $("#password_confirmation");
    const $matchError = $("#passwordMatchError");

    const $form = $("#registerForm");
    const $btnSubmit = $("#btnSubmit");

    const passwordValidator = createPasswordValidator();

    let passwordBlurred = false;
    let confirmBlurred = false;

    // ==========================================================
    // NOME
    // ==========================================================

    function validateName() {
        const name = $name.val().trim();

        if (name.length < 5) {
            $name.addClass("is-invalid").removeClass("is-valid");

            $nameLength.show();

            return false;
        }

        $name.addClass("is-valid").removeClass("is-invalid");

        $nameLength.hide();

        return true;
    }

    // ==========================================================
    // E-MAIL
    // ==========================================================

    function validateEmail() {
        const email = $email.val().trim();

        const dominioPermitido = "unifesp.br";

        const hasEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

        const hasDominio = email.split("@")[1];

        let missingDetails = [];

        if (!hasEmail) {
            missingDetails.push("ter um formato válido");
        }

        if (hasDominio !== dominioPermitido) {
            missingDetails.push("ser um endereço " + dominioPermitido);
        }

        if (missingDetails.length > 0) {
            $email.addClass("is-invalid").removeClass("is-valid");

            $emailStrengthError
                .text("O e-mail precisa " + missingDetails.join(" e ") + ".")
                .show();

            return false;
        }

        $email.addClass("is-valid").removeClass("is-invalid");

        $emailStrengthError.hide();

        return true;
    }

    // ==========================================================
    // CONFIRMAÇÃO DE SENHA
    // ==========================================================

    function validatePasswordMatch() {
        const passVal = $password.val();
        const confirmVal = $confirmPassword.val();

        if (confirmVal.length === 0) {
            $confirmPassword.removeClass("is-valid is-invalid");

            $matchError.hide();
            return false;
        }

        if (passVal !== confirmVal) {
            $confirmPassword.addClass("is-invalid").removeClass("is-valid");

            $matchError.show();
            return false;
        }

        $confirmPassword.addClass("is-valid").removeClass("is-invalid");
        $matchError.hide();
        return true;
    }

    // ==========================================================
    // EVENTOS
    // ==========================================================

    $name.on("blur", function () {
        if ($name.val().trim().length > 0) {
            validateName();
        } else {
            $name.removeClass("is-valid is-invalid");
            $nameLength.hide();
            return false;
        }
    });

    $email.on("blur", function () {
        if ($email.val().trim().length > 0) {
            validateEmail();
        } else {
            $email.removeClass("is-valid is-invalid");
            $emailStrengthError.text("").show();
            return false;
        }
    });

    $password.on("input", function () {
        passwordBlurred = true;
        passwordValidator.validate();
        if (confirmBlurred) {
            validatePasswordMatch();
        }
    });

    $password.on("blur", function () {
        if ($password.val().trim().length == 0) {
            $password.removeClass("is-valid is-invalid");
            passwordValidator.reset();
            return false;
        }
    });

    $confirmPassword.on("input", function () {
        if (confirmBlurred) {
            validatePasswordMatch();
        }
    });

    $confirmPassword.on("blur", function () {
        if ($confirmPassword.val().length > 0) {
            confirmBlurred = true;

            validatePasswordMatch();
        }
    });

    // ==========================================================
    // SUBMIT
    // ==========================================================

    $form.on("submit", function (e) {
        passwordBlurred = true;
        confirmBlurred = true;

        const isNameOk = validateName();

        const isEmailOk = validateEmail();

        const isPasswordOk = passwordValidator.validate();

        const isMatchOk = validatePasswordMatch();

        if (!isNameOk || !isEmailOk || !isPasswordOk || !isMatchOk) {
            e.preventDefault();

            if (!isNameOk) {
                $name.focus();
            } else if (!isEmailOk) {
                $email.focus();
            } else if (!isPasswordOk) {
                $password.focus();
            } else if (!isMatchOk) {
                $confirmPassword.focus();
            }

            return false;
        }

        $btnSubmit
            .html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Cadastrando...'
            );
    });
});
