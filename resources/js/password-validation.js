import $ from "jquery";

export function createPasswordValidator(passwordSelector = "#password") {
    const $password = $(passwordSelector);

    const requirements = {
        length: {
            selector: '[data-requirement="length"]',
            test: (value) => value.length >= 8,
        },

        uppercase: {
            selector: '[data-requirement="uppercase"]',
            test: (value) => /[A-Z]/.test(value),
        },

        lowercase: {
            selector: '[data-requirement="lowercase"]',
            test: (value) => /[a-z]/.test(value),
        },

        number: {
            selector: '[data-requirement="number"]',
            test: (value) => /[0-9]/.test(value),
        },

        special: {
            selector: '[data-requirement="special"]',
            test: (value) => /[!@#$%^&*(),.?":{}|/<>]/.test(value),
        },
    };

    function updateRequirement(requirement, isValid) {
        const $element = $(requirement.selector);
        if (!$element.length) {
            console.warn("Requisito não encontrado:", requirement.selector);
            return;
        }
        const $icon = $element.find("i");
        if (isValid) {
            $element.removeClass("is-invalid").addClass("is-valid");
            $icon.removeClass("fa-circle-xmark").addClass("fa-circle-check");
        } else {
            $element.removeClass("is-valid").addClass("is-invalid");
            $icon.removeClass("fa-circle-check").addClass("fa-circle-xmark");
        }
    }

    function validate() {
        const value = $password.val() || "";
        let isValid = true;

        Object.values(requirements).forEach((requirement) => {
            const requirementIsValid = requirement.test(value);

            updateRequirement(requirement, requirementIsValid);

            if (!requirementIsValid) {
                isValid = false;
            }
        });

        if (isValid) {
            $password.removeClass("is-invalid").addClass("is-valid");
        } else {
            $password.removeClass("is-valid").addClass("is-invalid");
        }

        return isValid;
    }

    function reset() {
        $password.removeClass("is-valid is-invalid");

        $(".password-requirement").each(function () {
            const $element = $(this);

            $element.removeClass("is-valid is-invalid");

            $element
                .find("i")
                .removeClass("fa-circle-check")
                .addClass("fa-circle-xmark");
        });
    }

    return {
        validate,
        reset,
    };
}
