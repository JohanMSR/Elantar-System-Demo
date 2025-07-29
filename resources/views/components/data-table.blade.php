@props([
    'headers' => [],
    'data' => [],
    'perPage' => 10,
    'currentPage' => 1,
    'totalItems' => 0,
    'showPagination' => true,
    'showPerPageSelector' => true,
    'tableId' => 'data-table',
    'isLoading' => false,
    'emptyMessage' => 'No hay datos disponibles'
])

<div>
    @if($showPerPageSelector)
    <div class="per-page-selector mb-3">
        <div class="d-flex align-items-center flex-wrap">
            <label for="perPageSelect" class="d-flex align-items-center mb-0">
                <div class="per-page-label me-2 text-muted">Mostrar</div>
                <select id="perPageSelect" class="form-select form-select-sm custom-select" aria-label="Registros por página">
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                </select>
                <div class="per-page-label ms-2 text-muted">registros por página</div>
            </label>
        </div>
    </div>
    @endif

    <div class="table-responsive" id="{{ $tableId }}Loading">
        <table class="table rounded-3">
            <thead>
                <tr class="text-center">
                    @foreach($headers as $index => $header)
                        <th class="text-nowrap {{ $header['class'] ?? '' }} column-header-{{ $index }}">
                            {!! $header['title'] !!}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody id="table-body">
                @if(count($data) > 0)
                    {{ $slot }}
                @else
                    <tr>
                        <td colspan="{{ count($headers) }}" class="text-center py-4">
                            {{ $emptyMessage }}
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    
    @if($showPagination && $totalItems > 0)
        <div class="pagination-container mt-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div class="pagination-info text-muted">
                    @php
                        $from = ($currentPage - 1) * $perPage + 1;
                        $to = min($currentPage * $perPage, $totalItems);
                    @endphp
                    Mostrando {{ $from }} a {{ $to }} de {{ $totalItems }} registros
                </div>
                <div class="pagination-links">
                    @isset($pagination)
                        {{ $pagination }}
                    @endisset
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    /* Estilos para el selector de registros por página */
    .per-page-selector {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 1rem;
    }
    
    .per-page-selector label {
        display: flex;
        align-items: center;
        margin-bottom: 0;
        font-size: 0.875rem;
        color: #64748b;
    }
    
    .per-page-selector .per-page-label {
        display: inline-block;
        vertical-align: middle;
        white-space: nowrap;
        line-height: 1.2;
    }
    
    .per-page-selector select.custom-select {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 0.375rem;
        padding: 0.375rem 2rem 0.375rem 0.75rem;
        font-size: 0.875rem;
        width: auto;
        min-width: 80px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.5rem center;
        background-size: 16px 12px;
        vertical-align: middle;
    }
    
    .per-page-selector select.custom-select:hover {
        border-color: #94a3b8;
    }
    
    .per-page-selector select.custom-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 0.15rem rgba(59, 130, 246, 0.25);
        outline: none;
    }
    
    /* Estilos para la paginación */
    .pagination-container {
        padding: 0.5rem 0;
    }
    
    .pagination-info {
        font-size: 0.875rem;
    }
    
    /* Estilos globales para la tabla */
    .table th {
        padding: 12px 15px;
        text-align: center;
        font-weight: 600;
        font-size: 0.95rem;
        vertical-align: middle;
        height: 60px;
        background-color: #f8fafc;
        color: #475569;
        border-bottom: 1px solid rgba(100, 116, 139, 0.2);
    }
    
    .table td {
        padding: 12px 15px;
        text-align: center;
        border-bottom: 1px solid #f0f0f0;
        font-weight: 500;
        vertical-align: middle;
        height: 55px;
        color: #334155;
    }
    
    /* Colores sutiles para columnas alternadas */
    tr:nth-child(even) td {
        background-color: rgba(248, 250, 252, 0.7);
    }
    
    tr:nth-child(odd) td {
        background-color: rgba(255, 255, 255, 1);
    }
    
    /* Colores específicos para los encabezados - tonos grises claros */
    th:nth-child(1) {
        background-color: #f1f5f9;
    }
    
    th:nth-child(2) {
        background-color: #f1f5f9;
    }
    
    th:nth-child(3) {
        background-color: #f1f5f9;
    }
    
    th:nth-child(4) {
        background-color: #f1f5f9;
    }
    
    th:nth-child(5) {
        background-color: #f1f5f9;
    }
    
    th:nth-child(6) {
        background-color: #f1f5f9;
    }
    
    th:nth-child(7) {
        background-color: #f1f5f9;
    }
    
    th:nth-child(8) {
        background-color: #f1f5f9;
    }
    
    th:nth-child(9) {
        background-color: #f1f5f9;
    }
    
    th:nth-child(10) {
        background-color: #f1f5f9;
    }
    
    th:nth-child(11) {
        background-color: #f1f5f9;
    }
    
    th:nth-child(12) {
        background-color: #f1f5f9;
    }
    
    th:nth-child(13) {
        background-color: #f1f5f9;
    }
    
    /* Sutiles indicadores de columna - borde superior de color */
    th:nth-child(1) {
        border-top: 3px solid #38bdf8;
    }
    
    th:nth-child(2) {
        border-top: 3px solid #818cf8;
    }
    
    th:nth-child(3) {
        border-top: 3px solid #60a5fa;
    }
    
    th:nth-child(4) {
        border-top: 3px solid #2563eb;
    }
    
    th:nth-child(5) {
        border-top: 3px solid #34d399;
    }
    
    th:nth-child(6) {
        border-top: 3px solid #94a3b8;
    }
    
    th:nth-child(7) {
        border-top: 3px solid #64748b;
    }
    
    th:nth-child(8) {
        border-top: 3px solid #0ea5e9;
    }
    
    th:nth-child(9) {
        border-top: 3px solid #84cc16;
    }
    
    th:nth-child(10) {
        border-top: 3px solid #f59e0b;
    }
    
    th:nth-child(11) {
        border-top: 3px solid #6b7280;
    }
    
    th:nth-child(12) {
        border-top: 3px solid #ef4444;
    }
    
    th:nth-child(13) {
        border-top: 3px solid #8b5cf6;
    }
    
    /* Sutiles indicadores de columna - borde izquierdo delgado */
    td:nth-child(1) {
        border-left: 2px solid rgba(56, 189, 248, 0.2);
    }
    
    td:nth-child(2) {
        border-left: 2px solid rgba(129, 140, 248, 0.2);
    }
    
    td:nth-child(3) {
        border-left: 2px solid rgba(96, 165, 250, 0.2);
    }
    
    td:nth-child(4) {
        border-left: 2px solid rgba(37, 99, 235, 0.2);
    }
    
    td:nth-child(5) {
        border-left: 2px solid rgba(52, 211, 153, 0.2);
    }
    
    td:nth-child(6) {
        border-left: 2px solid rgba(148, 163, 184, 0.2);
    }
    
    td:nth-child(7) {
        border-left: 2px solid rgba(100, 116, 139, 0.2);
    }
    
    td:nth-child(8) {
        border-left: 2px solid rgba(14, 165, 233, 0.2);
    }
    
    td:nth-child(9) {
        border-left: 2px solid rgba(132, 204, 22, 0.2);
    }
    
    td:nth-child(10) {
        border-left: 2px solid rgba(245, 158, 11, 0.2);
    }
    
    td:nth-child(11) {
        border-left: 2px solid rgba(107, 114, 128, 0.2);
    }
    
    td:nth-child(12) {
        border-left: 2px solid rgba(239, 68, 68, 0.2);
    }
    
    td:nth-child(13) {
        border-left: 2px solid rgba(139, 92, 246, 0.2);
    }
    
    /* Para mantener la columna visible cuando hay celdas con coloreado específico */
    .table td[class*="bg-"] {
        background-color: inherit !important;
    }
    
    /* Mejora de contraste al pasar el ratón sobre las filas */
    .table tr:hover td {
        background-color: rgba(243, 244, 246, 0.9) !important;
        color: #111827;
    }
    
    /* Alineación vertical de iconos dentro de celdas */
    .table td svg {
        vertical-align: middle;
    }
    
    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 0;
    }
    
    .pagination .page-link {
        padding: 6px 12px;
        margin: 0 3px;
        border-radius: 5px;
        color: var(--color-primary, #3B82F6);
        background-color: white;
        border: 1px solid #e2e8f0;
        transition: all 0.2s;
    }
    
    .pagination .page-link:hover {
        background-color: #f1f5f9;
        border-color: #cbd5e1;
    }
    
    .pagination .page-item.active .page-link {
        background-color: var(--color-primary, #3B82F6);
        color: white;
        border-color: var(--color-primary, #3B82F6);
    }
    
    /* Fix for pagination arrows */
    .pagination-links svg {
        width: 20px;
        height: 20px;
        max-width: 20px;
        max-height: 20px;
    }
    
    .pagination .page-link {
        font-size: 0.875rem;
        padding: 0.25rem 0.5rem;
    }
    
    /* Estilos responsivos para móvil */
    @media (max-width: 768px) {
        .per-page-selector {
            justify-content: flex-start;
            margin-bottom: 1rem;
        }
        
        .per-page-selector label {
            flex-wrap: nowrap;
            justify-content: flex-start;
            width: 100%;
            padding: 0.5rem 0;
            align-items: center;
            gap: 0.5rem;
        }
        
        .per-page-selector .per-page-label {
            line-height: 1.2;
        }
        
        .per-page-selector select.custom-select {
            margin: 0;
        }
        
        .table {
            font-size: 0.875rem;
        }
        
        .table td, .table th {
            padding: 0.5rem;
        }
        
        .pagination-container {
            flex-direction: column;
        }
        
        .pagination-container .d-flex {
            flex-direction: column;
            gap: 1rem;
        }
        
        .pagination-info {
            margin-bottom: 0.5rem;
            text-align: center;
            width: 100%;
        }
        
        .pagination-links {
            justify-content: center;
            width: 100%;
        }
    }
    
    /* Ajustes para pantallas muy pequeñas */
    @media (max-width: 576px) {
        .per-page-selector label {
            font-size: 0.8rem;
            flex-direction: row;
            align-items: center;
            gap: 0.3rem;
        }
        
        .per-page-selector .per-page-label {
            font-size: 0.8rem;
        }
        
        .per-page-selector select.custom-select {
            font-size: 0.8rem;
            min-width: 60px;
            padding: 0.25rem 1.5rem 0.25rem 0.5rem;
            margin: 0;
        }
        
        .table td, .table th {
            padding: 0.25rem;
            font-size: 0.75rem;
        }
        
        .pagination .page-link {
            padding: 0.2rem 0.4rem;
            font-size: 0.75rem;
        }
    }
    
    /* Estilos para los badges de estado */
    .status-badge {
        background-color: #D1FAE5;
        color: #065F46;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.85rem;
        display: inline-block;
    }
</style>

<script>
    function blockTable(tableId, message = 'Cargando...') {
        const tableContainer = document.getElementById(tableId + 'Loading');
        if (tableContainer) {
            tableContainer.style.position = 'relative';
            tableContainer.style.minHeight = '200px';
            
            const overlay = document.createElement('div');
            overlay.className = 'table-loading-overlay';
            overlay.style.position = 'absolute';
            overlay.style.top = '0';
            overlay.style.left = '0';
            overlay.style.width = '100%';
            overlay.style.height = '100%';
            overlay.style.backgroundColor = 'rgba(255, 255, 255, 0.7)';
            overlay.style.display = 'flex';
            overlay.style.justifyContent = 'center';
            overlay.style.alignItems = 'center';
            overlay.style.zIndex = '10';
            
            const spinner = document.createElement('div');
            spinner.className = 'spinner-border text-primary';
            spinner.setAttribute('role', 'status');
            
            const srOnly = document.createElement('span');
            srOnly.className = 'visually-hidden';
            srOnly.textContent = message;
            
            spinner.appendChild(srOnly);
            overlay.appendChild(spinner);
            tableContainer.appendChild(overlay);
        }
    }
    
    function unblockTable(tableId) {
        const tableContainer = document.getElementById(tableId + 'Loading');
        if (tableContainer) {
            const overlay = tableContainer.querySelector('.table-loading-overlay');
            if (overlay) {
                overlay.remove();
            }
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const tableId = '{{ $tableId }}';
        if ({{ $isLoading ? 'true' : 'false' }}) {
            blockTable(tableId);
        }
        
        // Manejar el cambio en el selector de registros por página
        const perPageSelector = document.getElementById('perPageSelect');
        if (perPageSelector) {
            perPageSelector.addEventListener('change', function() {
                const event = new CustomEvent('perPageChanged', {
                    detail: { perPage: this.value }
                });
                document.dispatchEvent(event);
            });
        }
    });
</script> 