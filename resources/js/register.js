import $ from "jquery";

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
    // FORÇA DA SENHA
    // ==========================================================

    function updatePasswordRequirement($element, isValid) {
        const $icon = $element.find("i");

        if (isValid) {
            $element.addClass("valid").removeClass("invalid");

            $icon.removeClass("fa-circle-xmark").addClass("fa-circle-check");
        } else {
            $element.addClass("invalid").removeClass("valid");

            $icon.removeClass("fa-circle-check").addClass("fa-circle-xmark");
        }
    }

    function validatePasswordStrength() {
        const val = $password.val();

        const minLength = val.length >= 8;
        const hasUppercase = /[A-Z]/.test(val);
        const hasLowercase = /[a-z]/.test(val);
        const hasNumber = /[0-9]/.test(val);
        const hasSpecial = /[!@#$%^&*(),.?":{}|/<>]/.test(val);

        updatePasswordRequirement($("#idDivLength"), minLength);

        updatePasswordRequirement($("#idDivUpper"), hasUppercase);

        updatePasswordRequirement($("#idDivLower"), hasLowercase);

        updatePasswordRequirement($("#idDivNumber"), hasNumber);

        updatePasswordRequirement($("#idDivSpecial"), hasSpecial);

        const passwordIsValid =
            minLength &&
            hasUppercase &&
            hasLowercase &&
            hasNumber &&
            hasSpecial;

        if (!passwordIsValid) {
            $password.addClass("is-invalid").removeClass("is-valid");

            return false;
        }

        $password.addClass("is-valid").removeClass("is-invalid");

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

    $password.on("keyup", function () {
        passwordBlurred = true;

        validatePasswordStrength();

        if (confirmBlurred) {
            validatePasswordMatch();
        }
    });

    $password.on("blur", function () {
        if ($password.val().trim().length == 0) {
            $password.removeClass("is-valid is-invalid");
            return false;
        }
    });

    $confirmPassword.on("blur", function () {
        if ($confirmPassword.val().length > 0) {
            confirmBlurred = true;

            validatePasswordMatch();
        }
    });

    $password.on("input", function () {
        if (passwordBlurred) {
            validatePasswordStrength();
        }

        if (confirmBlurred) {
            validatePasswordMatch();
        }
    });

    $confirmPassword.on("input", function () {
        if (confirmBlurred) {
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

        const isPasswordOk = validatePasswordStrength();

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

        // Pequeno atraso para permitir o submit
        setTimeout(function () {
            $btnSubmit.prop("disabled", true);
        }, 50);
    });
});
