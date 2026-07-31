import $ from "jquery";
import { createPasswordValidator } from "./password-validation";

$(function () {
    const $password = $("#password");
    const $confirmPassword = $("#password_confirmation");

    const $matchError = $("#passwordMatchError");

    const $form = $("#resetPasswordForm");
    const $btnSubmit = $("#btnSubmit");

    const passwordValidator = createPasswordValidator();

    let passwordBlurred = false;
    let confirmBlurred = false;

    // --------------------------------------------------
    // Confirmação da senha
    // --------------------------------------------------

    function validatePasswordMatch() {
        const password = $password.val();
        const confirmation = $confirmPassword.val();

        if (confirmation.length === 0) {
            $confirmPassword.removeClass("is-valid is-invalid");

            $matchError.hide();

            return false;
        }

        if (password !== confirmation) {
            $confirmPassword.addClass("is-invalid").removeClass("is-valid");

            $matchError.show();

            return false;
        }

        $confirmPassword.addClass("is-valid").removeClass("is-invalid");

        $matchError.hide();

        return true;
    }

    // --------------------------------------------------
    // Eventos da senha
    // --------------------------------------------------

    $password.on("blur", function () {

        if ($password.val().trim().length != 0) {
            passwordBlurred = true;

            passwordValidator.validate();

            if (confirmBlurred) {
                validatePasswordMatch();
            }
        }
        else{
            $password.removeClass("is-valid is-invalid");
            passwordValidator.reset();
            return false;
        }
    });

    $password.on("input", function () {
        if (passwordBlurred) {
            passwordValidator.validate();
        }

        if (confirmBlurred) {
            validatePasswordMatch();
        }
    });

    // --------------------------------------------------
    // Eventos da confirmação
    // --------------------------------------------------

    $confirmPassword.on("blur", function () {
        confirmBlurred = true;

        validatePasswordMatch();
    });

    $confirmPassword.on("input", function () {
        if (confirmBlurred) {
            validatePasswordMatch();
        }
    });

    // --------------------------------------------------
    // Submit
    // --------------------------------------------------

    $form.on("submit", function (e) {
        passwordBlurred = true;
        confirmBlurred = true;

        const isPasswordValid = passwordValidator.validate();

        const isMatchValid = validatePasswordMatch();

        if (!isPasswordValid || !isMatchValid) {
            e.preventDefault();

            if (!isPasswordValid) {
                $password.focus();
            } else {
                $confirmPassword.focus();
            }

            return false;
        }

        $btnSubmit
            .prop("disabled", true)
            .html(
                '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>' +
                    "Redefinindo...",
            );
    });
});
