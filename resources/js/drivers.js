import $ from 'jquery';
import { Modal } from 'bootstrap';
import { confirmDelete } from './sweetalert';

$(function () {

    // Instancia o modal usando a instância global do Bootstrap compilada pelo Vite
    const motoristaModal = new Modal(document.getElementById('modal-motorista'));
    
    // Gatilho para Novo Motorista
    $('#btn-novo-motorista').on('click', function() {
        $('#modal-title').text('Novo motorista');
        //$('#form-motorista').attr('action', "{{ route('drivers.store') }}");
        $('#form-motorista').attr('action', window.app.routes.drivers.store);
        $('#method-container').empty();
        $('#form-motorista')[0].reset();
        $('#input-is_active').prop('checked', true);

        limparValidacao($('#input-name'));
        limparValidacao($('#input-cpf'));
        limparValidacao($('#input-phone'));
        limparValidacao($('#input-cnh'));
        limparValidacao($('#input-cnh_category'));
        limparValidacao($('#input-cnh_expiration'));
        limparValidacao($('#input-email'));

        motoristaModal.show();
    });

    // Gatilho para Editar Motorista
    $(document).on('click', '.btn-editar', function() {
        const driver = $(this).data('driver');

        $('#modal-title').text('Editar motorista: ' + driver.name);
        $('#form-motorista').attr('action', `${window.app.routes.drivers.base}/${driver.id}`);
        $('#method-container').html('<input type="hidden" name="_method" value="PUT">');

        $('#input-name').val(driver.name);
        $('#input-cpf').val(driver.cpf);
        $('#input-phone').val(driver.phone || '');
        $('#input-cnh').val(driver.cnh);
        $('#input-cnh_category').val(driver.cnh_category);

        if (driver.cnh_expiration) {
            $('#input-cnh_expiration').val(driver.cnh_expiration.split('T')[0]);
        }
        $('#input-email').val(driver.email || '');
        $('#input-is_active').prop('checked', !!driver.is_active);

        limparValidacao($('#input-cpf'));
        limparValidacao($('#input-cnh'));

        motoristaModal.show();
    });

    //Gatilho para excluir motorista
    $(document).on('submit', '.form-delete', function(e) {
        e.preventDefault();

        const name = $(this).data('name');
        confirmDelete(this, `Deseja realmente excluir <strong>${name}</strong>?`);
    });

    function aplicarValido(elemento) {
        elemento.addClass('is-valid').removeClass('is-invalid');
    }

    function aplicarInvalido(elemento) {
        elemento.addClass('is-invalid').removeClass('is-valid');
    }

    function limparValidacao(elemento) {
        elemento.removeClass('is-valid is-invalid');
    }

    // ==========================================
    // MÁSCARAS E VALIDAÇÕES EM REAL-TIME
    // ==========================================
    $('#input-name').on('blur', function() {
        const val = $(this).val();
        if (val === '') {
            limparValidacao($(this));
            return;
        }
        checkName(val) ? aplicarValido($(this)) : aplicarInvalido($(this));
    });

    $('#input-cpf').on('input', function() {
        let v = $(this).val().replace(/\D/g, '');
        if (v.length > 11) v = v.slice(0, 11);
        v = v.replace(/(\d{3})(\d)/, '$1.$2');
        v = v.replace(/(\d{3})(\d)/, '$1.$2');
        v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        $(this).val(v);
    }).on('blur', function() {
        const val = $(this).val();
        if (val === '') {
            limparValidacao($(this));
            return;
        }
        checkCPF(val) ? aplicarValido($(this)) : aplicarInvalido($(this));
    });

    $('#input-cnh').on('input', function() {
        let v = $(this).val().replace(/\D/g, '');
        if (v.length > 11) v = v.slice(0, 11);
        $(this).val(v);
    }).on('blur', function() {
        const val = $(this).val();
        if (val === '') {
            limparValidacao($(this));
            return;
        }
        checkCNH(val) ? aplicarValido($(this)) : aplicarInvalido($(this));
    });

    $('#input-phone').on('input', function() {
        let v = $(this).val().replace(/\D/g, '');
        if (v.length > 11) v = v.slice(0, 11);
        v = v.replace(/^(\d{2})(\d)/g, '($1) $2');
        v = v.replace(/(\d)(\d{4})$/, '$1-$2');
        $(this).val(v);
    }).on('blur', function() {
        const val = $(this).val();
        if (val === '') {
            limparValidacao($(this));
            return;
        }
        checkPhone(val) ? aplicarValido($(this)) : aplicarInvalido($(this));
    });

    $('#input-cnh_category').on('input', function() {
        let v = $(this).val().replace(/^[A-Z]+$/, '');
        if (v.length > 3) v = v.slice(0, 3);
        $(this).val(v);
    }).on('blur', function() {
        const val = $(this).val();
        if (val === '') {
            limparValidacao($(this));
            return;
        }
        checkCategoryCNH(val) ? aplicarValido($(this)) : aplicarInvalido($(this));
    });

    $('#input-cnh_expiration').on('blur', function() {
        const val = $(this).val();
        if (val === '') {
            limparValidacao($(this));
            return;
        }
        checkExpirationCNH(val) ? aplicarValido($(this)) : aplicarInvalido($(this));
    });

    $('#input-email').on('blur', function() {
        const val = $(this).val();
        if (val === '') {
            limparValidacao($(this));
            return;
        }
        checkEmail(val) ? aplicarValido($(this)) : aplicarInvalido($(this));
    });

    function checkName(nome) {
        let regexNome = /^[a-zA-ZÀ-ÿ']+( [a-zA-ZÀ-ÿ']+)+$/;
        return regexNome.test(nome.trim());
    }

    function checkCPF(cpf) {
        cpf = cpf.replace(/[^\d]+/g, '');
        if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) return false;
        let soma = 0,
            resto;
        for (let i = 1; i <= 9; i++) soma = soma + parseInt(cpf.substring(i - 1, i)) * (11 - i);
        resto = (soma * 10) % 11;
        if ((resto === 10) || (resto === 11)) resto = 0;
        if (resto !== parseInt(cpf.substring(9, 10))) return false;
        soma = 0;
        for (let i = 1; i <= 10; i++) soma = soma + parseInt(cpf.substring(i - 1, i)) * (12 - i);
        resto = (soma * 10) % 11;
        if ((resto === 10) || (resto === 11)) resto = 0;
        return resto === parseInt(cpf.substring(10, 11));
    }

    function checkPhone(phone) {
        phone = phone.replace(/\D/g, '');
        let regexTelefone = /^(?:[14689][0-9]|2[12478]|3([1-5]|[7-8])|5([13-5])|7[193-7])(?:9\d{8}|\d{8})$/;
        if (phone.length > 9 && phone.length < 12) {
            return regexTelefone.test(phone);
        }
        return false;
    }

    function checkCNH(cnh) {
        cnh = cnh.replace(/[^\d]+/g, '');
        if (cnh.length !== 11 || /^(\d)\1{10}$/.test(cnh)) return false;
        let sum1 = 0;
        for (let i = 0, j = 9; i < 9; i++, j--) sum1 += parseInt(cnh.charAt(i)) * j;
        let dv1 = sum1 % 11;
        let dsc = 0;
        if (dv1 > 9) {
            dv1 = 0;
            dsc = 2;
        }
        let sum2 = 0;
        for (let i = 0, j = 1; i < 9; i++, j++) sum2 += parseInt(cnh.charAt(i)) * j;
        let dv2 = sum2 % 11;
        if (dv2 > 9) {
            dv2 = 0;
        } else {
            dv2 = dv2 - dsc;
            if (dv2 < 0) dv2 += 11;
        }
        return parseInt(cnh.charAt(9)) === dv1 && parseInt(cnh.charAt(10)) === dv2;
    }

    function checkCategoryCNH(cat) {
        var regexCNH = /^(A|B|C|D|E|AB|AC|AD|AE|ACC|C)$/i;
        if (cat.length <= 3) {
            return regexCNH.test(cat.toUpperCase());
        }
        return false;
    }

    function checkExpirationCNH(expiration) {
        let startDateValue = new Date().toISOString().split('T')[0];
        let fromParts = startDateValue.split('-');
        let toParts = expiration.split('-');
        let startDate = new Date(fromParts[0], fromParts[1] - 1, fromParts[2]);
        let endDate = new Date(toParts[0], toParts[1] - 1, toParts[2]);
        let diffTime = endDate - startDate;
        let diffDays = diffTime / (1000 * 60 * 60 * 24);
        return diffDays >= 30;
    }

    function checkEmail(email) {
        let regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regexEmail.test(email);
    }

    });