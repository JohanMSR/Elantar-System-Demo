@extends('layouts.master')

@section('title')
Gastos - @lang('translation.business-center')
@endsection

@push('css')
<style>
    :root {
        --color-primary: #13c0e6;
        --color-primary-dark: #10a5c6;
        --color-secondary: #4687e6;
        --color-secondary-dark: #3472c9;
        --color-accent: #8ce04f;
        --color-accent-dark: #7ac843;
        --color-warning: #FFA500;
        --color-danger: #FF4D4F;
        --color-success: #52c41a;
        --color-dark: #162d92;
        --color-text: #495057;
        --color-light-text: #6c757d;
        --color-border: #eaeaea;
        --color-input-bg: #ffffff;
        --color-input-bg-hover: #f8f9fa;
        --shadow-card: 0 8px 20px rgba(19, 192, 230, 0.1);
        --shadow-btn: 0 5px 15px rgba(70, 135, 230, 0.2);
        --transition-normal: all 0.3s ease;
        --transition-fast: all 0.2s ease;
        --radius-sm: 4px;
        --radius-md: 8px;
        --radius-lg: 12px;
        --radius-circle: 50%;
    }

    .main-content {
        background-color: #f1f8ff;
        padding: 0;
    }

    .section-title {
        font-family: "MontserratBold";
        font-size: 1.25rem;
        color: var(--color-text);
        margin-bottom: 1.5rem;
    }

    .dashboard-card {
        background-color: white;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-card);
        padding: 1.5rem;
        height: 100%;
        transition: var(--transition-normal);
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(19, 192, 230, 0.15);
    }

    .card-header-custom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .card-title {
        font-family: "MontserratBold";
        font-size: 1.1rem;
        color: var(--color-text);
        margin-bottom: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .card-title i {
        color: var(--color-primary);
    }

    .card-subtitle {
        font-family: "Montserrat";
        font-size: 0.85rem;
        color: var(--color-light-text);
    }

    .btn-primary {
        background: linear-gradient(to right, var(--color-primary), var(--color-secondary));
        color: white;
        border: none;
        border-radius: var(--radius-md);
        padding: 0.75rem 1.5rem;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: var(--transition-fast);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-primary:hover {
        filter: brightness(1.05);
        box-shadow: var(--shadow-btn);
        text-decoration: none;
        color: white;
    }

    .btn-success {
        background-color: var(--color-success);
        color: white;
        border: none;
        border-radius: var(--radius-md);
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        transition: var(--transition-fast);
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .btn-success:hover {
        background-color: #45a049;
        transform: translateY(-2px);
    }

    .btn-warning {
        background-color: var(--color-warning);
        color: white;
        border: none;
        border-radius: var(--radius-md);
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        transition: var(--transition-fast);
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .btn-warning:hover {
        background-color: #e68900;
        transform: translateY(-2px);
    }

    .btn-danger {
        background-color: var(--color-danger);
        color: white;
        border: none;
        border-radius: var(--radius-md);
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        transition: var(--transition-fast);
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .btn-danger:hover {
        background-color: #d32f2f;
        transform: translateY(-2px);
    }

    .water-type-badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 500;
        margin: 0.125rem;
    }

    .water-type-pozo {
        background-color: rgba(19, 192, 230, 0.1);
        color: var(--color-primary);
        border: 1px solid rgba(19, 192, 230, 0.3);
    }

    .water-type-ciudad {
        background-color: rgba(70, 135, 230, 0.1);
        color: var(--color-secondary);
        border: 1px solid rgba(70, 135, 230, 0.3);
    }

    .cost-amount {
        font-weight: 700;
        color: var(--color-primary);
        font-size: 1.1rem;
    }

    .dropdown-menu {
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-card);
        border: 1px solid var(--color-border);
        padding: 0.5rem;
    }

    .dropdown-item {
        padding: 0.5rem 1rem;
        border-radius: var(--radius-sm);
        transition: var(--transition-fast);
    }

    .dropdown-item:hover {
        background-color: rgba(19, 192, 230, 0.1);
    }

    .form-check-input:checked {
        background-color: var(--color-primary);
        border-color: var(--color-primary);
    }

    .fade-in {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeIn 0.6s ease forwards;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive Design Improvements */
    @media (max-width: 768px) {
        .section-title {
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }

        .btn-primary {
            padding: 0.6rem 1rem;
            font-size: 0.85rem;
            gap: 0.3rem;
        }

        .btn-primary i {
            width: 16px;
            height: 16px;
        }

        /* Table responsive improvements */
        .table-responsive {
            border-radius: var(--radius-md);
            overflow: hidden;
        }

        /* Action buttons responsive */
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            align-items: center;
        }

        .action-buttons .btn-warning,
        .action-buttons .btn-success,
        .action-buttons .btn-danger {
            padding: 0.4rem 0.6rem;
            font-size: 0.75rem;
            min-width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .action-buttons i {
            width: 14px;
            height: 14px;
        }

        /* Water type badges responsive */
        .water-type-badge {
            font-size: 0.7rem;
            padding: 0.2rem 0.4rem;
            margin: 0.1rem;
        }

        /* Cost amount responsive */
        .cost-amount {
            font-size: 0.9rem;
            font-weight: 600;
        }

        /* Modal responsive */
        .modal-dialog {
            margin: 1rem;
            max-width: calc(100% - 2rem);
        }

        .modal-title {
            font-size: 1.1rem;
        }

        .modal-title i {
            width: 18px;
            height: 18px;
        }

        /* Form responsive */
        .form-check {
            margin-bottom: 0.75rem;
        }

        .form-check-label {
            font-size: 0.9rem;
        }
    }

    @media (max-width: 576px) {
        .container-fluid {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        .dashboard-card {
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .card-header-custom {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .btn-primary {
            width: 100%;
            justify-content: center;
            margin-top: 0.5rem;
        }

        /* Table improvements for very small screens */
        .table th,
        .table td {
            padding: 0.5rem 0.25rem;
            font-size: 0.8rem;
        }

        .table th {
            font-size: 0.75rem;
        }

        /* Action buttons stack vertically on very small screens */
        .action-buttons {
            flex-direction: column;
            gap: 0.25rem;
        }

        .action-buttons .btn-warning,
        .action-buttons .btn-success,
        .action-buttons .btn-danger {
            width: 100%;
            max-width: 120px;
            justify-content: center;
        }

        /* Water type badges wrap better */
        .water-type-badge {
            display: inline-block;
            margin-bottom: 0.25rem;
        }

        /* Cost amount smaller on mobile */
        .cost-amount {
            font-size: 0.8rem;
        }
    }

    /* Touch-friendly improvements */
    @media (hover: none) and (pointer: coarse) {
        .btn-warning,
        .btn-success,
        .btn-danger {
            min-height: 44px;
            min-width: 44px;
        }

        .form-check-input {
            min-width: 20px;
            min-height: 20px;
        }
    }
</style>
@endpush

@section('content')
    <div class="main-content">
    @php
        $expensesTitle = 'Gastos';
    @endphp
    <x-page-header :title="$expensesTitle" icon="dollar-sign" :has_team="false" />
    <br>
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="section-title">Lista de Gastos</h5>
                    <p class="text-muted">Gestión de gastos por tipo de agua</p>
                </div>
                <a href="{{ route('expenses.create') }}" class="btn-primary">
                    <i data-feather="plus"></i>
                    Crear Gasto
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="dashboard-card fade-in">
                    @php
                        $headers = [
                            ['title' => 'Acciones'],
                            ['title' => 'Nombre'],
                            ['title' => 'Descripción'],
                            ['title' => 'Costo Estimado'],
                            ['title' => 'Tipos de Agua'],
                            ['title' => 'Creado por'],
                            ['title' => 'Fecha de Creación']
                        ];
                    @endphp
                    
                    <x-data-table 
                        :headers="$headers" 
                        :data="$expenses->items()" 
                        :perPage="$expenses->perPage()" 
                        :currentPage="$expenses->currentPage()" 
                        :totalItems="$expenses->total()" 
                        tableId="expensesTable" 
                        :isLoading="false"
                    >
                        @foreach($expenses as $expense)
                            <tr class="text-center">
                                <td>
                                    <div class="action-buttons d-flex justify-content-center gap-2">
                                        <a href="{{ route('expenses.edit', $expense) }}" class="btn-warning" title="Editar">
                                            <i data-feather="edit"></i>
                                        </a>
                                        <button class="btn-success" onclick="updateWaterTypes({{ $expense->id }})" title="Gestionar Tipos de Agua">
                                            <i data-feather="droplet"></i>
                                        </button>
                                        <button class="btn-danger" onclick="deleteExpense({{ $expense->id }})" title="Eliminar">
                                            <i data-feather="trash-2"></i>
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $expense->name }}</div>
                                </td>
                                <td>
                                    <div>{{ $expense->description ?: 'Sin descripción' }}</div>
                                </td>
                                <td>
                                    <div class="cost-amount">${{ number_format($expense->estimated_cost, 2) }}</div>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap justify-content-center gap-1">
                                        @foreach($expense->waterTypes as $waterType)
                                            <span class="water-type-badge water-type-{{ $waterType->code }}">
                                                {{ $waterType->tx_tipo_agua }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <div>{{ $expense->creator->name ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    <div>{{ $expense->created_at->format('d/m/Y H:i') }}</div>
                                </td>
                            </tr>
                        @endforeach
                        
                        <x-slot name="pagination">
                            {{ $expenses->links('pagination::bootstrap-4') }}
                        </x-slot>
                    </x-data-table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para gestionar tipos de agua -->
<div class="modal fade" id="waterTypesModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i data-feather="droplet"></i>
                    Gestionar Tipos de Agua
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="waterTypesForm">
                    @csrf
                    <input type="hidden" id="expenseId" name="expense_id">
                    <div class="mb-3">
                        <label class="form-label">Seleccionar tipos de agua aplicables:</label>
                        @foreach($waterTypes as $waterType)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       name="water_types[]" 
                                       value="{{ $waterType->co_tipo_agua }}" 
                                       id="waterType{{ $waterType->co_tipo_agua }}">
                                <label class="form-check-label" for="waterType{{ $waterType->co_tipo_agua }}">
                                    {{ $waterType->tx_tipo_agua }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="saveWaterTypes()">Guardar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateWaterTypes(expenseId) {
    // Limpiar formulario
    document.getElementById('waterTypesForm').reset();
    document.getElementById('expenseId').value = expenseId;
    
    // Obtener los tipos de agua actuales del gasto
    fetch(`/expenses/${expenseId}/water-types`)
        .then(response => response.json())
        .then(data => {
            // Marcar los checkboxes correspondientes
            data.water_types.forEach(waterTypeId => {
                const checkbox = document.getElementById(`waterType${waterTypeId}`);
                if (checkbox) {
                    checkbox.checked = true;
                }
            });
        })
        .catch(error => {
            console.error('Error:', error);
        });
    
    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('waterTypesModal'));
    modal.show();
}

function saveWaterTypes() {
    const form = document.getElementById('waterTypesForm');
    const formData = new FormData(form);
    const expenseId = document.getElementById('expenseId').value;
    
    fetch(`/expenses/${expenseId}/water-types`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Cerrar modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('waterTypesModal'));
            modal.hide();
            
            // Recargar página para mostrar cambios
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al actualizar los tipos de agua');
    });
}

function deleteExpense(expenseId) {
    if (confirm('¿Está seguro de que desea eliminar este gasto?')) {
        fetch(`/expenses/${expenseId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (response.ok) {
                location.reload();
            } else {
                alert('Error al eliminar el gasto');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar el gasto');
        });
    }
}

// Inicializar Feather icons
document.addEventListener('DOMContentLoaded', function() {
    if (typeof feather !== 'undefined' && feather.replace) {
        feather.replace();
    }
});
</script>
@endpush 