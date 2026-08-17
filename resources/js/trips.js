import $ from "jquery";
import { Modal } from "bootstrap";
import { confirmDelete } from "./sweetalert";

$(function () {
    const modalElement = document.getElementById("modal-viagem");
    const modalViagem = new Modal(modalElement);

    let originalTrip = null;

    // =========================================================
    // FORMATA DATA PARA datetime-local
    // =========================================================

    function formatForInput(dateString) {
        if (!dateString) {
            return "";
        }

        return dateString.replace(" ", "T").substring(0, 16);
    }

    // =========================================================
    // ABRIR MODAL - NOVA VIAGEM
    // =========================================================

    $("#btn-nova-viagem").on("click", function () {
        originalTrip = null;

        $("#modal-title").text("Nova viagem");

        $("#form-viagem").attr("action", window.app.routes.trips.store);

        $("#method-container").empty();

        $("#form-viagem")[0].reset();

        $("#input-vehicle-display").val("");
        $("#input-driver-display").val("");
        $("#input-status-display").val("");

        modalViagem.show();
    });

    // =========================================================
    // ABRIR MODAL - EDITAR VIAGEM
    // =========================================================

    $(".btn-edit").on("click", function () {
        let trip = $(this).data("trip");

        // Guarda a viagem original para comparar as alterações
        originalTrip = {
            ...trip,

            vehicle_id: trip.vehicle_id ?? "",
            driver_id: trip.driver_id ?? "",
            purpose: trip.purpose ?? "",
            origin: trip.origin ?? "",
            destination: trip.destination ?? "",
            departure_time: formatForInput(trip.departure_time),
            arrival_time: formatForInput(trip.arrival_time),
            status: trip.status ?? "",
            observations: trip.observations ?? "",
        };

        console.log("Viagem original:", originalTrip);

        $("#modal-title").text("Editar viagem");

        $("#form-viagem").attr(
            "action",
            `${window.app.routes.trips.base}/${trip.id}`,
        );

        $("#method-container").html(
            '<input type="hidden" name="_method" value="PUT">',
        );

        // ==========================================================
        // DADOS GERAIS
        // ==========================================================

        $("#input-purpose").val(trip.purpose);
        $("#input-origin").val(trip.origin);
        $("#input-destination").val(trip.destination);

        $("#input-departure").val(formatForInput(trip.departure_time));

        $("#input-arrival").val(formatForInput(trip.arrival_time));

        $("#input-observations").val(trip.observations || "");

        // ==========================================================
        // VEÍCULO
        // ==========================================================

        $("#input-vehicle").val(trip.vehicle_id || "");

        $("#input-vehicle-display").val(
            trip.vehicle
                ? `${trip.vehicle.plate} - ${trip.vehicle.model}`
                : "Não definido",
        );

        // ==========================================================
        // MOTORISTA
        // ==========================================================

        $("#input-driver").val(trip.driver_id || "");

        $("#input-driver-display").val(
            trip.driver ? trip.driver.name : "Não definido",
        );

        // ==========================================================
        // STATUS
        // ==========================================================

        $("#input-status").val(trip.status || "");

        $("#input-status-display").val(trip.status || "Não definido");

        modalViagem.show();
    });

    // =========================================================
    // PEGAR DADOS ATUAIS DO FORMULÁRIO
    // =========================================================

    function getCurrentData() {
        return {
            vehicle_id: $("#input-vehicle").val() || "",

            driver_id: $("#input-driver").val() || "",

            purpose: $("#input-purpose").val() || "",

            origin: $("#input-origin").val() || "",

            destination: $("#input-destination").val() || "",

            departure_time: $("#input-departure").val() || "",

            arrival_time: $("#input-arrival").val() || "",

            status: $("#input-status").val() || "",

            observations: $("#input-observations").val() || "",
        };
    }

    // =========================================================
    // SALVAR ALTERAÇÕES
    // =========================================================

    $("#form-viagem").on("submit", async function (e) {
        /*
         * Se originalTrip é null, significa que estamos
         * cadastrando uma nova viagem.
         *
         * Nesse caso deixamos o formulário fazer o POST
         * normalmente.
         */

        if (!originalTrip) {
            return;
        }

        e.preventDefault();

        const current = getCurrentData();

        console.log("Viagem original:", originalTrip);

        console.log("Dados atuais:", current);

        const csrfToken = $('input[name="_token"]', this).val();

        const requests = [];

        // =====================================================
        // DADOS GERAIS DA VIAGEM
        // =====================================================

        let hasGeneralChanges = false;

        if (
            current.purpose !== originalTrip.purpose ||
            current.origin !== originalTrip.origin ||
            current.destination !== originalTrip.destination ||
            current.departure_time !== originalTrip.departure_time ||
            current.arrival_time !== originalTrip.arrival_time ||
            current.observations !== originalTrip.observations
        ) {
            hasGeneralChanges = true;
        }

        if (hasGeneralChanges) {
            console.log("Alterando dados gerais...");

            requests.push(
                $.ajax({
                    url: `${window.app.routes.trips.base}/${originalTrip.id}`,
                    type: "PUT",
                    data: {
                        purpose: current.purpose,
                        origin: current.origin,
                        destination: current.destination,
                        departure_time: current.departure_time,
                        arrival_time: current.arrival_time,
                        observations: current.observations,
                        _token: csrfToken,
                    },
                }),
            );
        }

        // =====================================================
        // VEÍCULO
        // =====================================================

        if (
            $("#input-vehicle").data("editable") === true &&
            String(current.vehicle_id) !== String(originalTrip.vehicle_id)
        ) {
            const vehicleUrl = `${window.app.routes.trips.base}/${originalTrip.id}/veiculo`;

            console.log("Alterando veículo:", current.vehicle_id);
            console.log("URL DO VEÍCULO:", vehicleUrl);
            requests.push(
                $.ajax({
                    url: `${window.app.routes.trips.base}/${originalTrip.id}/veiculo`,

                    type: "PATCH",

                    data: {
                        vehicle_id: current.vehicle_id,

                        _token: csrfToken,
                    },
                }),
            );
        }

        // =====================================================
        // MOTORISTA
        // =====================================================

        if (
            $("#input-driver").data("editable") === true &&
            String(current.driver_id) !== String(originalTrip.driver_id)
        ) {
            const driverUrl = `${window.app.routes.trips.base}/${originalTrip.id}/motorista`;

            console.log("URL DO MOTORISTA:", driverUrl);

            requests.push(
                $.ajax({
                    url: `${window.app.routes.trips.base}/${originalTrip.id}/motorista`,

                    type: "PATCH",

                    data: {
                        driver_id: current.driver_id,

                        _token: csrfToken,
                    },
                }),
            );
        }

        // =====================================================
        // STATUS
        // =====================================================

        if (
            $("#input-status").data("editable") === true &&
            String(current.status) !== String(originalTrip.status)
        ) {
            console.log("Alterando status:", current.status);
            const statusUrl = `${window.app.routes.trips.base}/${originalTrip.id}/status`;

            console.log("URL DO STATUS:", statusUrl);

            requests.push(
                $.ajax({
                    url: `${window.app.routes.trips.base}/${originalTrip.id}/status`,

                    type: "PATCH",

                    data: {
                        status: current.status,

                        _token: csrfToken,
                    },
                }),
            );
        }

        // =====================================================
        // NENHUMA ALTERAÇÃO
        // =====================================================

        if (requests.length === 0) {
            modalViagem.hide();

            return;
        }

        // =====================================================
        // EXECUTAR ALTERAÇÕES
        // =====================================================

        try {
            await Promise.all(requests);

            /*
             * Todas as requisições foram concluídas.
             *
             * Recarregamos a página para que os dados
             * e as mensagens do Laravel sejam atualizados.
             */

            window.location.reload();
        } catch (error) {
            console.error("Erro ao atualizar viagem:", error);

            if (error.status === 403) {
                alert(
                    "Você não possui permissão para realizar uma ou mais alterações.",
                );
            } else {
                alert("Não foi possível atualizar a viagem.");
            }
        }
    });

    // =========================================================
    // CANCELAMENTO
    // =========================================================

    $(".form-delete").on("submit", function (e) {
        e.preventDefault();

        confirmDelete(this, "Deseja realmente cancelar esta viagem?");
    });

    // =========================================================
    // ALERTAS
    // =========================================================

    setTimeout(function () {
        $(".alert").fadeOut("slow");
    }, 4000);
});
