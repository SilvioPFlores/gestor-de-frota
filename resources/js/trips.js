import $ from 'jquery';
import { Modal } from 'bootstrap';
import { confirmDelete } from './sweetalert';

$(function () {

    // Instancia o modal usando a instância global do Bootstrap compilada pelo Vite
    const modalViagem = new Modal(document.getElementById('modal-viagem'));

    // Função para formatar data do Laravel (Y-m-d H:i:s) para o input datetime-local (Y-m-dTH:i)
    function formatForInput(dateString) {
        if (!dateString) return '';
        return dateString.replace(' ', 'T').substring(0, 16);
    }

    // Abrir modal NOVA
    $('#btn-nova-viagem').on('click', function () {
        $('#modal-title').text('Nova viagem');
        $('#form-viagem').attr('action', window.app.routes.trips.store);
        $('#method-container').empty();
        $('#form-viagem')[0].reset();
        modalViagem.show();
    });

    // Abrir modal EDITAR
    $('.btn-edit').on('click', function () {
        let trip = $(this).data('trip');

        $('#modal-title').text('Editar viagem');
        $('#form-viagem').attr('action', `${window.app.routes.trips.base}/${trip.id}`);
        $('#method-container').html('<input type="hidden" name="_method" value="PUT">');

        $('#input-vehicle').val(trip.vehicle_id);
        $('#input-driver').val(trip.driver_id);
        $('#input-purpose').val(trip.purpose);
        $('#input-origin').val(trip.origin);
        $('#input-destination').val(trip.destination);
        $('#input-departure').val(formatForInput(trip.departure_time));
        $('#input-arrival').val(formatForInput(trip.arrival_time));
        $('#input-status').val(trip.status);
        $('#input-observations').val(trip.observations);

        modalViagem.show();
    });

    // Confirmar Exclusão
    $('.form-delete').on('submit', function (e) {
        e.preventDefault();
        confirmDelete(this, `Deseja realmente excluir esta viagem?`);
    });
});