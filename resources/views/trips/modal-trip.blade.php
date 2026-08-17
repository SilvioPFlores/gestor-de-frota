<!-- Modal Nova Viagem -->
<div class="modal fade" id="modal-viagem" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="modal-title">
                    Nova viagem
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar">
                </button>
            </div>

            <div class="modal-body">

                <form id="form-viagem" action="{{ route('trips.store') }}" method="POST">

                    @csrf

                    {{-- Método PUT quando estiver editando --}}
                    <div id="method-container"></div>


                    {{-- =====================================================
                        FINALIDADE
                    ====================================================== --}}

                    <div class="mb-3">

                        <label for="input-purpose" class="form-label fw-semibold">
                            Finalidade
                        </label>

                        <input type="text" id="input-purpose" name="purpose" required class="form-control"
                            @cannot('viagens.editar_dados')
                                readonly
                            @endcannot>

                    </div>


                    {{-- =====================================================
                        VEÍCULO / MOTORISTA
                    ====================================================== --}}

                    <div class="row g-3 mb-3">

                        {{-- VEÍCULO --}}

                        <div class="col-md-6">

                            <label for="input-vehicle" class="form-label fw-semibold">
                                Veículo
                            </label>

                            @can('viagens.alterar_veiculo')

                                <select id="input-vehicle" name="vehicle_id" class="form-select" data-editable="true">

                                    <option value="">
                                        Selecione
                                    </option>

                                    @foreach ($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}">
                                            {{ $vehicle->plate }} -
                                            {{ $vehicle->model }}
                                        </option>
                                    @endforeach

                                </select>
                            @else
                                <input type="text" id="input-vehicle-display" class="form-control" value=""
                                    readonly>

                                {{-- Valor usado pelo JavaScript --}}
                                <input type="hidden" id="input-vehicle" value="" data-editable="false">

                            @endcan

                        </div>


                        {{-- MOTORISTA --}}

                        <div class="col-md-6">

                            <label for="input-driver" class="form-label fw-semibold">
                                Motorista
                            </label>

                            @can('viagens.alterar_motorista')

                                <select id="input-driver" name="driver_id" class="form-select" data-editable="true">

                                    <option value="">
                                        Selecione
                                    </option>

                                    @foreach ($drivers as $driver)
                                        <option value="{{ $driver->id }}">
                                            {{ $driver->name }}
                                        </option>
                                    @endforeach

                                </select>
                            @else
                                <input type="text" id="input-driver-display" class="form-control" value=""
                                    readonly>

                                {{-- Valor usado pelo JavaScript --}}
                                <input type="hidden" id="input-driver" value="" data-editable="false">

                            @endcan

                        </div>

                    </div>


                    {{-- =====================================================
                        ORIGEM / DESTINO
                    ====================================================== --}}

                    <div class="row g-3 mb-3">

                        <div class="col-md-6">

                            <label for="input-origin" class="form-label fw-semibold">
                                Origem
                            </label>

                            <input type="text" id="input-origin" name="origin" required class="form-control"
                                @cannot('viagens.editar_dados')
                                    readonly
                                @endcannot>

                        </div>


                        <div class="col-md-6">

                            <label for="input-destination" class="form-label fw-semibold">
                                Destino
                            </label>

                            <input type="text" id="input-destination" name="destination" required
                                class="form-control"
                                @cannot('viagens.editar_dados')
                                    readonly
                                @endcannot>

                        </div>

                    </div>


                    {{-- =====================================================
                        HORÁRIOS
                    ====================================================== --}}

                    <div class="row g-3 mb-3">

                        <div class="col-md-6">

                            <label for="input-departure" class="form-label fw-semibold">
                                Saída prevista
                            </label>

                            <input type="datetime-local" id="input-departure" name="departure_time" required
                                class="form-control"
                                @cannot('viagens.editar_dados')
                                    readonly
                                @endcannot>

                        </div>


                        <div class="col-md-6">

                            <label for="input-arrival" class="form-label fw-semibold">
                                Chegada prevista
                            </label>

                            <input type="datetime-local" id="input-arrival" name="arrival_time" class="form-control"
                                @cannot('viagens.editar_dados')
                                    readonly
                                @endcannot>

                        </div>

                    </div>


                    {{-- =====================================================
                        STATUS
                    ====================================================== --}}

                    <div class="mb-3">

                        <label for="input-status" class="form-label fw-semibold">
                            Status
                        </label>

                        @can('viagens.alterar_status')
                            <select id="input-status" name="status" class="form-select" data-editable="true">

                                <option value="Solicitada">
                                    Solicitada
                                </option>

                                <option value="Agendada">
                                    Agendada
                                </option>

                                <option value="Em andamento">
                                    Em andamento
                                </option>

                                <option value="Concluida">
                                    Concluída
                                </option>

                                <option value="Cancelada">
                                    Cancelada
                                </option>

                            </select>
                        @else
                            <input type="text" id="input-status-display" class="form-control" value=""
                                readonly>

                            <input type="hidden" id="input-status" value="" data-editable="false">
                        @endcan

                    </div>


                    {{-- =====================================================
                        OBSERVAÇÕES
                    ====================================================== --}}

                    <div class="mb-4">

                        <label for="input-observations" class="form-label fw-semibold">
                            Observações
                        </label>

                        <input type="text" id="input-observations" name="observations" class="form-control">
                    </div>


                    {{-- =====================================================
                        BOTÕES
                    ====================================================== --}}

                    <div class="d-flex justify-content-end border-top pt-3">

                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">

                            Cancelar

                        </button>

                        <button type="submit" class="btn btn-primary px-4">

                            Salvar

                        </button>

                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
