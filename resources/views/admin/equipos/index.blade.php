@extends('layouts.app')

@section('title', 'Listado de Equipos')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0"><i class="bi bi-pc-display"></i> Equipos Registrados</h5>
        <a href="{{ route('equipos.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i>
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <strong><i class="bi bi-list-check"></i> Lista de Computadoras</strong>
            <!-- Buscador -->
            <form action="{{ route('equipos.index') }}" method="GET" class="row g-2">

                <!-- {{-- Buscar por texto --}}
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Por nombre..."
                        value="{{ request('search') }}">
                </div> -->

                {{-- Buscar por número de serie --}}
                <div class="col-md-3">
                    <input type="text" name="serie" class="form-control form-control-sm" placeholder="Por serie..."
                        value="{{ request('serie') }}">
                </div>

                {{-- Filtro por Agencia --}}
               <div class="col-md-4">
                    <select name="agencia" class="form-select form-select-sm select2">
                        <option value="">Agencias  </option>
                        @foreach ($agencias as $agencia)
                            <option value="{{ $agencia->id }}" {{ request('agencia') == $agencia->id ? 'selected' : '' }}>
                                {{ $agencia->nombre_agencia }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filtro por Oficina --}}
                <div class="col-md-3">
                    <select name="oficina" id="filtroOficina" class="form-select form-select-sm select2">
                        <option value="">Oficinas </option>
                        @foreach ($oficinas as $oficina)
                            <option value="{{ $oficina->id }}" 
                                    {{ request('oficina') == $oficina->id ? 'selected' : '' }}
                                    data-agencia="{{ $oficina->id_agencia }}">
                                {{ $oficina->nombre_oficina }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2 align-items-center">
                    <button class="btn btn-sm btn-outline-primary d-flex align-items-center justify-content-center">
                        <i class="bi bi-search"></i>
                    </button>

                    <a href="{{ route('equipos.index') }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center justify-content-center">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>

            </form>
        </div>

        <div class="card-body">
            <div id="equipos-table">
                @include('admin.equipos.partials.table')
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            function fetchEquipos(page = 1) {
                let search = $('input[name="search"]').val();
                let serie = $('input[name="serie"]').val();
                let oficina = $('select[name="oficina"]').val();

                $.ajax({
                    url: "{{ route('equipos.index') }}",
                    type: "GET",
                    data: {
                        search: search,
                        serie: serie,
                        oficina: oficina,
                        page: page
                    },
                    success: function (response) {
                        $('#equipos-table').html(response);
                    },
                    error: function (xhr) {
                        console.error("Error al filtrar equipos:", xhr);
                    }
                });
            }

            // Evento para inputs de búsqueda con debounce
            let typingTimer;
            $('input[name="search"], input[name="serie"]').on('input', function () {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(function () {
                    fetchEquipos();
                }, 500);
            });

            // Evento para el select de oficinas
            $('select[name="oficina"]').on('change', function () {
                fetchEquipos();
            });

            // Interceptar clics en la paginación para cargar vía AJAX
            $(document).on('click', '.pagination a', function (e) {
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                fetchEquipos(page);
            });

        });
    </script>
    </div>
@endsection