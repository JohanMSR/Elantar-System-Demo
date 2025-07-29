@extends('layouts.master')

@section('title')
Ejemplo de Tabla de Datos - @lang('translation.business-center')
@endsection

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
<div class="container-fluid bg-light">
    <x-page-header title="Ejemplo de Componente de Tabla" icon="table">
        <div class="search-header-container">
            <form method="GET" action="{{ route('home') }}" id="searchForm">
                <div class="input-group">
                    <input type="text" id="search" name="search" class="form-control" placeholder="Buscar...">
                    <button type="submit" class="btn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </x-page-header>

    <div class="row mb-4">
        <div class="col-12">
            <h5 class="text-primary mb-3">Tabla de datos de ejemplo</h5>
        </div>
    </div>

    @php
    // Datos de ejemplo para la tabla
    $headers = [
        ['title' => 'ID', 'class' => 'col-1'],
        ['title' => 'Nombre<br>Apellido', 'class' => 'col-2'],
        ['title' => 'Email', 'class' => 'col-3'],
        ['title' => 'Teléfono', 'class' => 'col-2'],
        ['title' => 'Ciudad', 'class' => 'col-2'],
        ['title' => 'Acciones', 'class' => 'col-2']
    ];
    
    $tableData = [
        [
            'id' => 1,
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'email' => 'juan.perez@ejemplo.com',
            'telefono' => '555-123-4567',
            'ciudad' => 'Madrid'
        ],
        [
            'id' => 2,
            'nombre' => 'María',
            'apellido' => 'González',
            'email' => 'maria.gonzalez@ejemplo.com',
            'telefono' => '555-987-6543',
            'ciudad' => 'Barcelona'
        ],
        [
            'id' => 3,
            'nombre' => 'Carlos',
            'apellido' => 'Rodríguez',
            'email' => 'carlos.rodriguez@ejemplo.com',
            'telefono' => '555-456-7890',
            'ciudad' => 'Valencia'
        ]
    ];
    @endphp

    <x-data-table 
        :headers="$headers" 
        :data="$tableData" 
        :perPage="10" 
        :currentPage="1" 
        :totalItems="count($tableData)"
    >
        @foreach($tableData as $item)
            <tr class="text-center">
                <td class="table-secondary">{{ $item['id'] }}</td>
                <td>
                    <div class="fw-bold">{{ $item['nombre'] }}</div>
                    <div>{{ $item['apellido'] }}</div>
                </td>
                <td>{{ $item['email'] }}</td>
                <td class="table-info">{{ $item['telefono'] }}</td>
                <td>{{ $item['ciudad'] }}</td>
                <td>
                    <div class="d-flex justify-content-center gap-3">
                        <div class="text-center" style="cursor:pointer;">
                            <i class="fas fa-comment-dots"></i>
                        </div>
                        <div class="text-center" style="cursor:pointer;">
                            <i class="fas fa-edit"></i>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
        
        <x-slot name="pagination">
            <ul class="pagination pagination-sm">
                <li class="page-item disabled">
                    <a class="page-link" href="#" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li class="page-item active">
                    <a class="page-link" href="#">1</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#">2</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#">3</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </x-slot>
    </x-data-table>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Escuchar el evento de cambio en perPage
        document.addEventListener('perPageChanged', function(event) {
            console.log('Se cambió el número de registros por página a:', event.detail.perPage);
            // Aquí podrías hacer una petición AJAX para recargar la tabla con el nuevo perPage
        });
    });
</script>
@endpush 