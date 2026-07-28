import $ from 'jquery';
import { Modal } from 'bootstrap';
import { confirmDelete } from './sweetalert';

$(function () {
    // Inicializa a instância do Modal do Bootstrap
    const modalVeiculo = new Modal(document.getElementById("modal-veiculo"));

    // 1. Abrir modal para NOVO VEÍCULO
    $("#btn-novo-veiculo").on("click", function () {
        console.log('teste');
        $("#modal-title").text("Novo veículo");
        $("#form-veiculo").attr("action", window.app.routes.vehicles.store);
        $("#method-container").empty(); // Remove o PUT
        $("#form-veiculo")[0].reset(); // Limpa os campos
        modalVeiculo.show();
    });

    // 2. Abrir modal para EDITAR VEÍCULO
    $(".btn-edit").on("click", function () {
        // Recupera os dados do objeto JSON injetado no botão
        let vehicle = $(this).data("vehicle");

        $("#modal-title").text("Editar veículo: " + vehicle.plate);
        $("#form-veiculo").attr("action", `${window.app.routes.vehicles.base}/${vehicle.id}`); // Ajuste para a sua rota exata
        $("#method-container").html(
            '<input type="hidden" name="_method" value="PUT">',
        );

        // Preenche os inputs
        $("#input-plate").val(vehicle.plate);
        $("#input-year").val(vehicle.year);
        $("#input-brand").val(vehicle.brand);
        $("#input-model").val(vehicle.model);
        $("#input-color").val(vehicle.color);
        $("#input-fuel").val(vehicle.fuel);
        $("#input-current_km").val(vehicle.current_km);
        $("#input-status").val(vehicle.status);

        modalVeiculo.show();
    });

    // 3. Validação de Exclusão
    $('.form-delete').on('submit', function(e) {
        e.preventDefault();
        
        confirmDelete(this, 'Tem certeza que deseja excluir este veículo?<br>Todas as viagens dele também serão excluídas.');
    });

    // 4. Máscara Inteligente para Placa (Mercosul e Antiga)
    $("#input-plate").on("input", function () {
        // Pega o valor, converte para maiúsculo e remove caracteres especiais
        let val = $(this)
            .val()
            .toUpperCase()
            .replace(/[^A-Z0-9]/g, "");

        let formatada = "";

        for (let i = 0; i < val.length; i++) {
            let char = val[i];

            // Posições 1, 2 e 3: Apenas letras
            if (i < 3) {
                if (/[A-Z]/.test(char)) {
                    formatada += char;
                } else {
                    break; // Bloqueia a continuação se for número/símbolo
                }
            }
            // Posição 4: Acrescenta o hífen e aceita apenas número
            else if (i === 3) {
                if (/[0-9]/.test(char)) {
                    formatada += "-" + char;
                } else {
                    break;
                }
            }
            // Posição 5: Aceita Letra ou Número (Mercosul ou Antiga)
            else if (i === 4) {
                if (/[A-Z0-9]/.test(char)) {
                    formatada += char;
                } else {
                    break;
                }
            }
            // Posições 6 e 7: Apenas números
            else if (i < 7) {
                if (/[0-9]/.test(char)) {
                    formatada += char;
                } else {
                    break;
                }
            }
        }

        // Atualiza o campo com a string tratada
        $(this).val(formatada);
    });

    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 4000);
});
