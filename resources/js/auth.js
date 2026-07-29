import $ from 'jquery';

$(function () {
    $('#loginForm').on('submit', function () {
        const $btn = $('#btnSubmit');
        $btn
            .prop('disabled', true)
            .html(`
                <span class="spinner-border spinner-border-sm me-2"></span>
                Entrando...
            `);
    });
});