@extends('layouts.master')

@section('title')
@lang('translation.home_title') - @lang('translation.business-center')
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
        min-height: 100vh;
    }

    .section-title {
    font-family: "MontserratBold";
        font-size: 1.25rem;
        color: var(--color-text);
        margin-bottom: 1.5rem;
    }

    /* Tarjetas */
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
    }

    .card-subtitle {
        font-family: "Montserrat";
        font-size: 0.85rem;
        color: var(--color-light-text);
    }

    .refresh-button {
        background: none;
        border: none;
        color: var(--color-secondary);
        cursor: pointer;
        transition: var(--transition-fast);
    }

    .refresh-button:hover {
        transform: rotate(45deg);
        color: var(--color-primary);
    }

    /* Tarjetas de métricas */
    .metric-card {
        background-color: white;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-card);
        padding: 1.5rem;
        height: 100%;
        transition: var(--transition-normal);
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .metric-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(19, 192, 230, 0.15);
    }

    .metric-icon {
        width: 50px;
        height: 50px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .metric-icon.revenue {
        background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
    }

    .metric-icon.orders {
        background: linear-gradient(135deg, var(--color-accent), var(--color-accent-dark));
    }

    .metric-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--color-text);
        margin-bottom: 0.5rem;
    }

    .metric-label {
        font-size: 0.9rem;
        color: var(--color-light-text);
        margin-bottom: 0.5rem;
    }
    .metric-title {
        font-size: 1.6rem;
        color: var(--color-text);
        margin-bottom: 0.25rem;
    }

    .metric-change {
        font-size: 0.85rem;
        font-weight: 500;
    }

    .metric-change.positive {
        color: var(--color-accent);
    }

    .metric-change.negative {
        color: var(--color-danger);
    }

    /* Filtros */
    .filter-section {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .filter-select {
        background-color: white;
        border: 1px solid var(--color-border);
        border-radius: var(--radius-md);
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
        color: var(--color-text);
        min-width: 120px;
        transition: var(--transition-fast);
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(19, 192, 230, 0.1);
    }

    .download-button {
        background-color: var(--color-primary);
        color: white;
        border: none;
        border-radius: var(--radius-md);
        padding: 0.5rem 1.5rem;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: var(--transition-fast);
    }

    .download-button:hover {
        background-color: var(--color-primary-dark);
        transform: translateY(-2px);
    }

    /* Gráficos */
    .chart-container {
        width: 100%;
        height: 300px;
        min-height: 300px;
    }

    /* Contenedores de gráficas con leyendas */
    .chart-container-with-legend {
        width: 100%;
        height: 400px;
        min-height: 400px;
    }

    /* Mobile responsive para gráficas */
    @media (max-width: 768px) {
        .chart-container {
            height: 350px;
            min-height: 350px;
        }

        .chart-container-with-legend {
            height: 500px;
            min-height: 500px;
        }

        /* Gráficas específicas que necesitan más altura en mobile */
        .mobile-chart-tall {
            height: 600px;
            min-height: 600px;
        }
    }

    /* Eventos */
    .event-card {
        background-color: white;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-card);
        margin-bottom: 1rem;
    overflow: hidden;
        transition: var(--transition-fast);
    }

    .event-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(19, 192, 230, 0.2);
    }

    .event-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: linear-gradient(135deg, var(--color-secondary), var(--color-primary));
        color: white;
    }

    .event-title {
        font-family: "MontserratBold";
        font-size: 1rem;
        margin-bottom: 0.25rem;
    }

    .event-date {
        font-size: 0.8rem;
        opacity: 0.9;
    }

    .event-icon {
        width: 24px;
        height: 24px;
    }

    .event-image {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }

    .event-image-fallback {
        width: 100%;
        height: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f1f8ff;
    }

    .event-image-fallback img {
        max-width: 50%;
        max-height: 50%;
    }

    .event-content {
        padding: 1rem;
        font-size: 0.9rem;
        color: var(--color-text);
    }

    .event-content p {
        max-height: 100px;
        overflow-y: auto;
        margin-bottom: 0.75rem;
        scrollbar-width: thin;
        scrollbar-color: var(--color-primary) #f1f8ff;
    }
    
    .event-content p::-webkit-scrollbar {
        width: 6px;
    }
    
    .event-content p::-webkit-scrollbar-track {
        background: #f1f8ff;
    }
    
    .event-content p::-webkit-scrollbar-thumb {
        background-color: var(--color-primary);
        border-radius: 20px;
    }

    .event-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background-color: #f8f9fa;
        border-top: 1px solid var(--color-border);
    }

    .event-button {
        background-color: var(--color-primary);
        color: white;
    border: none;
        border-radius: var(--radius-md);
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        transition: var(--transition-fast);
    }

    .event-button:hover {
        background-color: var(--color-primary-dark);
    }

    .event-time {
        font-size: 0.8rem;
        color: var(--color-light-text);
    }

    /* Team list */
    .team-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        height: 400px;
    }

    .team-item {
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        margin-bottom: 0.75rem;
        border-radius: var(--radius-md);
        transition: var(--transition-fast);
        background-color: #f8f9fa;
    }

    .team-item:hover {
        background-color: rgba(19, 192, 230, 0.05);
        transform: translateX(5px);
    }

    .team-color {
        width: 12px;
        height: 12px;
        border-radius: var(--radius-circle);
        margin-right: 0.75rem;
        flex-shrink: 0;
    }

    .team-name {
        font-size: 0.9rem;
        color: var(--color-text);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .filter-section {
            flex-direction: column;
            align-items: flex-start;
        }
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

    /* Loader */
    .dashboard-loader {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.9);
        z-index: 9999;
        justify-content: center;
        align-items: center;
    }

    .dashboard-loader.active {
        display: flex;
    }

    .spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid var(--color-primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Top lists */
    .top-list {
        padding: 1rem 0;
    }

    .top-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        margin-bottom: 0.75rem;
        background-color: #f8f9fa;
        border-radius: var(--radius-md);
        transition: var(--transition-fast);
        position: relative;
        overflow: hidden;
    }

    .top-item:hover {
        background-color: #e9ecef;
        transform: translateX(5px);
    }

    .top-rank {
        width: 40px;
        height: 40px;
        border-radius: var(--radius-circle);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.2rem;
        margin-right: 1rem;
    }

    .top-rank.gold {
        background: linear-gradient(135deg, #FFD700, #FFA500);
        color: white;
    }

    .top-rank.silver {
        background: linear-gradient(135deg, #C0C0C0, #A8A8A8);
        color: white;
    }

    .top-rank.bronze {
        background: linear-gradient(135deg, #CD7F32, #B87333);
        color: white;
    }

    .top-rank.other {
        background: linear-gradient(135deg, #6c757d, #495057);
        color: white;
    }

    .top-info {
        flex: 1;
    }

    .top-name {
        font-weight: 600;
        color: var(--color-text);
        margin-bottom: 0.25rem;
    }

    .top-stats {
        display: flex;
        gap: 1rem;
        font-size: 0.85rem;
        color: var(--color-light-text);
    }

    .top-value {
        font-weight: 700;
        font-size: 1.1rem;
        color: var(--color-primary);
    }

    /* Estilos para los controles de período */
    .periodo-controls-container .btn-group {
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-radius: var(--radius-md);
        overflow: hidden;
    }

    .periodo-controls-container .btn {
        border: none;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        font-weight: 500;
        transition: var(--transition-fast);
        background-color: white;
        color: var(--color-primary);
    }

    .periodo-controls-container .btn:hover {
        background-color: rgba(19, 192, 230, 0.1);
        color: var(--color-primary-dark);
    }

    .periodo-controls-container .btn.active {
        background-color: var(--color-primary);
        color: white;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.2);
    }

    .periodo-controls-container .btn:first-child {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    .periodo-controls-container .btn:last-child {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    /* Estilos específicos para los IDs individuales (por compatibilidad) */
    #periodoControls .btn-group,
    #salesVolumeByRolesPeriodoControls .btn-group,
    #ordersByStatusGlobalPeriodoControls .btn-group {
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-radius: var(--radius-md);
        overflow: hidden;
    }

    #periodoControls .btn,
    #salesVolumeByRolesPeriodoControls .btn,
    #ordersByStatusGlobalPeriodoControls .btn {
        border: none;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        font-weight: 500;
        transition: var(--transition-fast);
        background-color: white;
        color: var(--color-primary);
    }

    #periodoControls .btn:hover,
    #salesVolumeByRolesPeriodoControls .btn:hover,
    #ordersByStatusGlobalPeriodoControls .btn:hover {
        background-color: rgba(19, 192, 230, 0.1);
        color: var(--color-primary-dark);
    }

    #periodoControls .btn.active,
    #salesVolumeByRolesPeriodoControls .btn.active,
    #ordersByStatusGlobalPeriodoControls .btn.active {
        background-color: var(--color-primary);
        color: white;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.2);
    }

    #periodoControls .btn:first-child,
    #salesVolumeByRolesPeriodoControls .btn:first-child,
    #ordersByStatusGlobalPeriodoControls .btn:first-child {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    #periodoControls .btn:last-child,
    #salesVolumeByRolesPeriodoControls .btn:last-child,
    #ordersByStatusGlobalPeriodoControls .btn:last-child {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
</style>
@endpush

@section('content')
    @php
        $homeTitle = __('translation.home_title');
        // $rol_co ahora se pasa desde el controlador
    @endphp
    
    <!-- Update navbar title dynamically -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof updatePageTitle === 'function') {
                updatePageTitle('{{ $homeTitle }}', 'home');
            }
        });
    </script>
    
    <!-- Loader -->
    <div class="dashboard-loader" id="dashboardLoader">
        <div class="spinner"></div>
    </div>

    <!-- Script para pasar variables PHP a JavaScript -->
    <script>
        const rol_co = {{ $rol_co ?? 0 }};
    </script>

    <!-- Métricas principales -->
    @if(in_array($rol_co ?? 0, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]))
    <div class="row g-4 mb-3 mt-2">
        <!-- Total Revenue -->
        <div class="col-12 col-md-6 fade-in">
            <div class="metric-card">
                <div class="metric-icon revenue">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="1" x2="12" y2="23"></line>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                </div>
                <div class="metric-title" id="ventasTitle">Mis Ventas del Año</div>
                <div class="metric-label">Apps: Instalación Completada (3 del mes – 2 del siguiente)</div>
                <div class="metric-value" id="totalRevenue">...</div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="col-12 col-md-6 fade-in">
            <div class="metric-card">
                <div class="metric-icon orders">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 11H3v10h6V11z"></path>
                        <path d="M21 3h-6v18h6V3z"></path>
                        <path d="M15 7h-6v14h6V7z"></path>
                    </svg>
                </div>
                <div class="metric-title" id="ordenesTitle">Mis Órdenes del Año</div>
                <div class="metric-label">Apps: Instalación Completada (3 del mes – 2 del siguiente)</div>
                <div class="metric-value" id="totalOrders">...</div>
            </div>
        </div>
    </div>
    @endif

    <div class="row g-4">
        <!-- Gráfica de volumen de ventas comparativo -->
        @if(in_array($rol_co ?? 0, [0, 1, 2, 3, 4,5, 6, 7, 8, 9, 10, 11]))
        <!-- Gráfica de montos -->
        <div class="col-12 col-lg-6">
            <div class="dashboard-card fade-in">
                <div class="card-header-custom">
                    <div>
                        <h5 class="card-title">Comparación de Montos de Instalaciones (Año Actual vs Año Anterior)</h5>
                        <p class="card-subtitle" id="volumenVentasSubtitle">Montos de ventas completadas por mes - Vista Personal</p>
                    </div>
                </div>
                <div class="chart-container" style="height: 400px;">
                    <div id="volumenVentasChart" style="width: 100%; height: 100%;"></div>
                </div>
            </div>
        </div>

        <!-- Gráfica de cantidades -->
        <div class="col-12 col-lg-6">
            <div class="dashboard-card fade-in">
                <div class="card-header-custom">
                    <div>
                        <h5 class="card-title">Comparación de Cantidad de Instalaciones (Año Actual vs Año Anterior)</h5>
                        <p class="card-subtitle" id="cantidadVentasSubtitle">Número de aplicaciones completadas por mes - Vista Personal</p>
                    </div>
                </div>
                <div class="chart-container" style="height: 400px;">
                    <div id="cantidadVentasChart" style="width: 100%; height: 100%;"></div>
                </div>
            </div>
        </div>
        @endif

        <!-- Gráfica de Volumen de Ventas por Roles (Solo en vista global) -->
        <div class="col-12" id="salesVolumeByRolesContainer" style="display: none;">
            <div class="dashboard-card fade-in">
                <div class="card-header-custom">
                    <div>
                        <h5 class="card-title">Volumen de Ventas por Roles</h5>
                        <p class="card-subtitle" id="salesVolumeByRolesSubtitle">Distribución de ventas completadas por rol (Año actual) - Vista Global</p>
                    </div>
                    <!-- Controles de período para Volumen de Ventas por Roles -->
                    <div id="salesVolumeByRolesPeriodoControls" class="periodo-controls-container" style="display: none;">
                        <div class="btn-group" role="group" aria-label="Período">
                            <button type="button" class="btn btn-outline-primary active" id="btn-salesVolumeByRoles-anual" onclick="cambiarPeriodoSalesVolumeByRoles('anual')">Anual</button>
                            <button type="button" class="btn btn-outline-primary" id="btn-salesVolumeByRoles-90dias" onclick="cambiarPeriodoSalesVolumeByRoles('90dias')">Últimos 90 días</button>
                        </div>
                    </div>
                </div>
                <div class="chart-container-with-legend mobile-chart-tall">
                    <div id="salesVolumeByRolesChart" style="width: 100%; height: 100%;"></div>
                </div>
            </div>
        </div>

        <!-- Top 10 Team Members (Solo en vista global) -->
        <div class="col-12" id="top10GlobalContainer" style="display: none;">
            <div class="dashboard-card fade-in">
                <div class="card-header-custom">
                    <div>
                        <h5 class="card-title">Top 10 Vendedores Globales</h5>
                        <p class="card-subtitle" id="top10GlobalSubtitle">Mes actual - Instalación Completada (3 del mes – 2 del siguiente)</p>
                    </div>
                    <!-- Controles de período para Top 10 Vendedores Globales -->
                    <div id="top5OrganizationSalespeopleControls" class="periodo-controls-container" style="display: none;">
                        <div class="btn-group" role="group" aria-label="Período">
                            <button type="button" class="btn btn-outline-primary active" id="btn-top5OrganizationSalespeople-anual" onclick="cambiarPeriodoTop5OrganizationSalespeople('anual')">Anual</button>
                            <button type="button" class="btn btn-outline-primary" id="btn-top5OrganizationSalespeople-90dias" onclick="cambiarPeriodoTop5OrganizationSalespeople('90dias')">Últimos 90 días</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="chart-container-with-legend mobile-chart-tall">
                            <div id="top10GlobalChart" style="width: 100%; height: 100%;"></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <ul class="team-list" id="top10GlobalList">
                            <!-- Lista se llenará dinámicamente -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales by Region Global -->
        <div class="col-12" id="salesByRegionGlobalContainer" style="display: none;">
            <div class="dashboard-card fade-in">
                <div class="card-header-custom">
                    <div>
                        <h5 class="card-title">Ventas por Región Global</h5>
                        <p class="card-subtitle">Distribución de ventas por región (Mes actual) - Vista Global</p>
                    </div>
                </div>
                <div class="chart-container">
                    <div id="salesByRegionGlobalChart" style="width: 100%; height: 100%;"></div>
                </div>
            </div>
        </div>

        <!-- Top 5 Offices (Solo en vista global) -->
        <div class="col-12" id="top3OfficesContainer" style="display: none;">
            <div class="dashboard-card fade-in">
                <div class="card-header-custom">
                    <div>
                        <h5 class="card-title">Top 5 Oficinas Globales</h5>
                        <p class="card-subtitle">Mes actual - Instalación Completada (3 del mes – 2 del siguiente)</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="chart-container-with-legend">
                            <div id="top3OfficesChart" style="width: 100%; height: 100%;"></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <ul class="team-list" id="top3OfficesList">
                            <!-- Lista se llenará dinámicamente -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top 10 Equipo - Gráfica de Torta -->
        <div class="col-12 fade-in" id="top10EquipoContainer" style="display: none;">
            <div class="dashboard-card">
                <div class="card-header-custom">
                    <div>
                        <h5 class="card-title">Top 10 Vendedores de mi Equipo</h5>
                        <p class="card-subtitle" id="top10EquipoSubtitle">Mes actual (del 3 al 2) - Vista Personal</p>
                    </div>
                </div>
                <div class="chart-container-with-legend mobile-chart-tall">
                    <div id="top10EquipoChart" style="width: 100%; height: 100%;"></div>
                </div>
            </div>
        </div>

        <!-- Top 10 de mi equipo - Solo visible en vista de equipo -->
        <div class="col-12" id="top10Container" style="display: none;">
            <div class="dashboard-card fade-in">
                <div class="card-header-custom">
                    <div>
                        <h5 class="card-title" id="top10Title">Top 10 Vendedores de mi Equipo</h5>
                        <p class="card-subtitle" id="top10Subtitle">Últimos 3 meses</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="chart-container-with-legend mobile-chart-tall">
                            <div id="chart2" style="width: 100%; height: 100%;"></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <ul class="team-list" id="team-list">
                            @if(isset($equipo) && isset($equipo['getTop10TeamMembers']) && count($equipo['getTop10TeamMembers']) > 0)
                                @foreach($equipo['getTop10TeamMembers'] as $key => $item)
                                <li class="team-item">
                                    <span class="team-color" style="background-color: {{ $key < 10 ? ['#4687e6', '#13c0e6', '#8ce04f', '#FFA500', '#C0C0C0', '#FF6B6B', '#9775FA', '#63E6BE', '#74C0FC', '#B197FC'][$key] : '#C0C0C0' }};"></span>
                                    <span class="team-name">{{ $item->name }}</span>
                                </li>
                                @endforeach
                            @else
                                <li class="team-item">
                                    <span class="team-color" style="background-color: #C0C0C0;"></span>
                                    <span class="team-name">Sin datos disponibles</span>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Orders by Status - Visible en vista personal -->
        <div class="col-12 fade-in" id="ordersByStatusContainer">
            <div class="dashboard-card">
                <div class="card-header-custom">
                    <div>
                        <h5 class="card-title">Distribución de Aplicaciones por Status</h5>
                        <p class="card-subtitle" id="ordersByStatusSubtitle">Año actual (del 3 de enero al 2 de enero siguiente) - Vista Personal</p>
                    </div>
                    <!-- Controles de período para todas las vistas excepto global -->
                    <div id="periodoControls" class="periodo-controls-container" style="display: none;">
                        <div class="btn-group" role="group" aria-label="Período">
                            <button type="button" class="btn btn-outline-primary active" id="btn-anual" onclick="cambiarPeriodo('anual')">Anual</button>
                            <button type="button" class="btn btn-outline-primary" id="btn-90dias" onclick="cambiarPeriodo('90dias')">Últimos 90 días</button>
                        </div>
                    </div>
                    <!-- Controles de período para vista global -->
                    <div id="ordersByStatusGlobalPeriodoControls" class="periodo-controls-container" style="display: none;">
                        <div class="btn-group" role="group" aria-label="Período">
                            <button type="button" class="btn btn-outline-primary active" id="btn-ordersByStatus-global-anual" onclick="cambiarPeriodoOrdersByStatusGlobal('anual')">Anual</button>
                            <button type="button" class="btn btn-outline-primary" id="btn-ordersByStatus-global-90dias" onclick="cambiarPeriodoOrdersByStatusGlobal('90dias')">Últimos 90 días</button>
                        </div>
                    </div>
                </div>
                <div class="chart-container-with-legend mobile-chart-tall">
                    <div id="ordersByStatusChart" style="width: 100%; height: 100%;"></div>
                </div>
            </div>
        </div>

  
    <!-- Top 10 Mis Oficinas -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="dashboard-card fade-in" id="top10MisOficinasContainer" style="display: none;">
                <div class="card-header-custom">
                    <div>
                        <h5 class="card-title">Top 10 de Mis Oficinas</h5>
                        <p class="card-subtitle">Mes actual (del 3 al 2) - Vista Oficina</p>
                    </div>
                    <button class="refresh-button" onclick="loadDashboardData(currentView)">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="chart-container-with-legend mobile-chart-tall">
                            <div id="top10MisOficinasChart" style="width: 100%; height: 100%;"></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <ul class="team-list" id="top10MisOficinasList">
                            <!-- Lista se llenará dinámicamente -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Ventas por Oficina -->

        <!-- Top 10 Team Director Manager - Visible en vistas equipo y oficina -->
        @if(in_array($vista_actual, ['equipo', 'oficina']))
        <div class="col-12 fade-in" id="top10TeamDirectorManagerContainer">
            <div class="dashboard-card">
                <div class="card-header-custom">
                    <div>
                        <h5 class="card-title">Top 10 Miembros del Equipo</h5>
                        <p class="card-subtitle" id="top10TeamDirectorManagerSubtitle">Mes actual (del 3 al 2)</p>
                    </div>
                </div>
                <div class="chart-container-with-legend mobile-chart-tall">
                    <div id="top10TeamDirectorManagerChart" style="width: 100%; height: 100%;"></div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-xl-12" id="top10TeamVendorsContainer" style="display: none;">
        <div class="dashboard-card fade-in">
            <div class="card-header-custom">
                <div>
                    <h3 class="card-title">Top 10 Vendedores del Equipo</h3>
                    <p class="card-subtitle"> Mes actual (del 3 al 2)</p>
                </div>
                <button class="refresh-button" onclick="refreshTop10TeamVendors()">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
            <div id="top10TeamVendorsChart" class="chart-container-with-legend mobile-chart-tall"></div>
        </div>
    </div>

    <!-- Top 10 Vendedores de la Oficina -->
    <div class="col-xl-12" id="top10OfficeSalespeopleContainer" style="display: none;">
        <div class="dashboard-card fade-in">
            <div class="card-header-custom">
                <div>
                    <h3 class="card-title">Top 10 Vendedores de la Oficina</h3>
                    <p class="card-subtitle"> Mes actual (del 3 al 2)</p>
                </div>
            </div>
            <div class="chart-container">
                <div id="top10OfficeSalespeopleChart" style="width: 100%; height: 100%;"></div>
            </div>
        </div>
    </div>
    <br>


    <!-- Daily Sales para Mentores y Jr Manager -->
    @if(in_array($rol_co ?? 0, [3, 4, 5, 6, 7, 8, 9, 10, 11]))
    <div class="row g-4">
        <div class="col-12">
            <div class="dashboard-card fade-in">
                <div class="card-header-custom">
                    <div>
                        <h5 class="card-title">Ventas Diarias</h5>
                        <p class="card-subtitle" id="dailySalesSubtitle">Mes actual (del 3 al 2) - Vista Personal</p>
                    </div>
                </div>
                <div class="chart-container" style="height: 350px;">
                    <div id="dailySalesChart" style="width: 100%; height: 100%;"></div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row mt-4">
        <div class="col-12 fade-in">
            <h4 class="section-title">Novedades y eventos</h4>
        </div>
    </div>

    <div class="row g-4">
        @if(isset($eventos2) && count($eventos2) > 0)
            @foreach($eventos2 as $key => $evento)
                @if($key < 8)
                <div class="col-12 col-md-6 fade-in evento-card">
                    <div class="event-card">
                        <div class="event-header">
                            <div>
                                <h5 class="event-title">{{ $evento->tx_titulo }}</h5>
                                <span class="event-date">{{ \Carbon\Carbon::parse($evento->fe_registro)->format('d F - Y | h:i a') }}</span>
                            </div>
                        </div>
                        
                        @if(isset($evento->adjuntos) && count($evento->adjuntos) > 0)
                            <img src="{{ asset($evento->adjuntos[0]->tx_url_adj) }}" alt="{{ $evento->tx_titulo }}" class="event-image" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="event-image-fallback" style="display: none;">
                                <img src="{{ asset('favicon10.png') }}" alt="Logo">
                            </div>
                        @else
                            <div class="event-image-fallback">
                                <img src="{{ asset('favicon10.png') }}" alt="Logo">
                            </div>
                        @endif
                        
                        <div class="event-content">
                            <p>{{ $evento->tx_descripcion }}</p>
                        </div>
                        <div class="event-footer">
                            <a href="{{ route('event') }}" class="event-button">IR A EVENTOS</a>
                            <span class="event-time">{{ \Carbon\Carbon::parse($evento->fe_registro)->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
            
            @if(count($eventos2) > 8)
            <div class="col-12 mt-4 text-center fade-in">
                <button id="mostrarMasEventos" class="download-button">MOSTRAR MÁS</button>
            </div>
            @endif
        @else
            <div class="col-12 fade-in">
                <div class="alert alert-info">
                    No hay eventos disponibles en este momento.
                </div>
            </div>
        @endif
    </div>


</div>
@endsection

@push('scripts')
<script src="{{ asset('vendor/echarts/echarts.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/locale/es.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Variables globales
    const rolCo = {{ $rol_co ?? 0 }};
    // Determinar vista por defecto según el rol
    let currentView = [0, 1, 2, 11].includes(rolCo) ? 'personal' : 'equipo';
    let currentPeriodo = 'anual'; // anual o 90dias
    let currentPeriodoSalesVolumeByRoles = 'anual'; // período específico para Sales Volume by Roles
    let currentPeriodoOrdersByStatusGlobal = 'anual'; // período específico para Orders by Status en vista global
    let dashboardData = {};
    
    // Función para detectar mobile y obtener configuración responsive
    function isMobile() {
        return window.innerWidth <= 768;
    }
    
    function getResponsiveConfig() {
        const mobile = isMobile();
        return {
            mobile: mobile,
            legendConfig: mobile ? {
                type: 'scroll',
                orient: 'horizontal',
                bottom: 0,
                left: 'center',
                itemWidth: 24,
                itemHeight: 24,
                textStyle: {
                    fontSize: 12
                },
                pageIconSize: 10,
                pageTextStyle: {
                    fontSize: 12
                }
            } : {
                type: 'scroll',
                orient: 'vertical',
                right: 10,
                top: 20,
                bottom: 20,
                textStyle: {
                    fontSize: 12
                }
            },
            gridConfig: mobile ? {
                left: '5%',
                right: '5%',
                bottom: '20%',
                top: '5%',
                containLabel: true
            } : {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            pieCenter: mobile ? ['50%', '45%'] : ['40%', '50%'],
            pieRadius: mobile ? ['30%', '55%'] : ['40%', '70%']
        };
    }
    
    // Hacer variables globales para el botón de refresh
    window.currentView = currentView;
    window.currentPeriodoSalesVolumeByRoles = currentPeriodoSalesVolumeByRoles;
    window.currentPeriodoOrdersByStatusGlobal = currentPeriodoOrdersByStatusGlobal;
    window.loadDashboardData = loadDashboardData;
    
    // Inicialización de gráficas
    const dom2 = document.getElementById('chart2');
    let myChart2 = null;
    
    // Inicializar gráfica de ventas por oficina para directores regionales
    let salesByOfficeChart = null;
    if (rolCo === 4) {
        const salesByOfficeDom = document.getElementById('salesByOfficeChart');
        if (salesByOfficeDom) {
            salesByOfficeChart = echarts.init(salesByOfficeDom);
        }
    }
    
    function initChart2() {
        if (myChart2) {
            myChart2.dispose();
        }
        myChart2 = echarts.init(dom2);
    }
    
    // Inicializar la gráfica
    initChart2();

    const ordersByStatusDom = document.getElementById('ordersByStatusChart');
    const ordersByStatusChart = echarts.init(ordersByStatusDom);
    
    const top10TeamDirectorManagerDom = document.getElementById('top10TeamDirectorManagerChart');
    let top10TeamDirectorManagerChart = null;
    if (top10TeamDirectorManagerDom) {
        top10TeamDirectorManagerChart = echarts.init(top10TeamDirectorManagerDom);
    }
    
    const volumenVentasDom = document.getElementById('volumenVentasChart');
    let volumenVentasChart = null;
    if (volumenVentasDom) {
        volumenVentasChart = echarts.init(volumenVentasDom);
    }

    // Inicializar gráfica de volumen de ventas por roles
    const salesVolumeByRolesDom = document.getElementById('salesVolumeByRolesChart');
    let salesVolumeByRolesChart = null;
    if (salesVolumeByRolesDom) {
        salesVolumeByRolesChart = echarts.init(salesVolumeByRolesDom);
    }

    // Inicializar gráfica de Top 10 Global
    const top10GlobalDom = document.getElementById('top10GlobalChart');
    let top10GlobalChart = null;
    if (top10GlobalDom) {
        top10GlobalChart = echarts.init(top10GlobalDom);
    }

    // Inicializar gráfica de Top 5 Organization Salespeople
    const top5OrganizationDom = document.getElementById('top5OrganizationChart');
    let top5OrganizationChart = null;
    if (top5OrganizationDom) {
        top5OrganizationChart = echarts.init(top5OrganizationDom);
    }

    // Gráficas adicionales para managers
    const revenueVsGoalsDom = document.getElementById('revenueVsGoalsChart');
    let revenueVsGoalsChart = null;
    if (revenueVsGoalsDom) {
        revenueVsGoalsChart = echarts.init(revenueVsGoalsDom);
    }

    const salesTrendDom = document.getElementById('salesTrendChart');
    let salesTrendChart = null;
    if (salesTrendDom) {
        salesTrendChart = echarts.init(salesTrendDom);
    }

    const dailySalesDom = document.getElementById('dailySalesChart');
    let dailySalesChart = null;
    if (dailySalesDom) {
        dailySalesChart = echarts.init(dailySalesDom);
    }

    // Gráficas adicionales para embajador/master
    const salesByRegionDom = document.getElementById('salesByRegionChart');
    let salesByRegionChart = null;
    if (salesByRegionDom) {
        salesByRegionChart = echarts.init(salesByRegionDom);
    }

    const salesVolumeByOfficesDom = document.getElementById('salesVolumeByOfficesChart');
    let salesVolumeByOfficesChart = null;
    if (salesVolumeByOfficesDom) {
        salesVolumeByOfficesChart = echarts.init(salesVolumeByOfficesDom);
    }

    // Inicializar gráfica de Sales by Region Global
    const salesByRegionGlobalDom = document.getElementById('salesByRegionGlobalChart');
    let salesByRegionGlobalChart = null;
    if (salesByRegionGlobalDom) {
        salesByRegionGlobalChart = echarts.init(salesByRegionGlobalDom);
    }

    // Inicializar gráfica de Top 10 Mis Oficinas
    const top10MisOficinasDom = document.getElementById('top10MisOficinasChart');
    let top10MisOficinasChart = null;
    if (top10MisOficinasDom) {
        top10MisOficinasChart = echarts.init(top10MisOficinasDom);
    }

    // Datos para la primera gráfica desde el controlador
    const meses = @json($meses ?? []);
    const data = @json($data ?? []);

    // Configuración de la primera gráfica
    let option = {
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'shadow'
            },
            formatter: function(params) {
                return params[0].name + '<br/>' + 
                       params[0].seriesName + ': ' + params[0].value;
            }
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        xAxis: [{
            type: 'category',
            data: meses.length > 0 ? meses : ['Sin datos'],
            axisPointer: {
                type: 'shadow'
            },
            axisLabel: {
                rotate: 45,
                interval: 0,
                fontSize: 11,
                margin: 15,
                align: 'right',
                verticalAlign: 'middle',
                formatter: function (value) {
                    // Si el valor es muy largo, truncarlo
                    return value.length > 8 ? value.substring(0, 7) + '...' : value;
                }
            }
        }],
        yAxis: [{
            type: 'value'
        }],
        series: [{
            name: 'Aplicaciones instaladas',
            type: 'bar',
            barWidth: '60%',
            itemStyle: {
                color: function(params) {
                    const colors = ['#4687e6', '#13c0e6', '#8ce04f', '#FFA500'];
                    return colors[params.dataIndex % colors.length];
                }
            },
            data: data.length > 0 ? data : [0]
        }]
    };

    // Datos para la segunda gráfica (torta) desde el controlador
    const dataTorta = @json($data_torta ?? []);
    const chartData = [];
    
    if (dataTorta && dataTorta.length > 0) {
        const colors = ['#4687e6', '#13c0e6', '#8ce04f', '#FFA500', '#C0C0C0', '#FF6B6B', '#9775FA', '#63E6BE', '#74C0FC', '#B197FC'];
        
        dataTorta.forEach((item, index) => {
            chartData.push({
                value: item.value,
                name: item.name,
                itemStyle: {
                    color: colors[index % colors.length]
                }
            });
        });
    } else {
        chartData.push({
            value: 100,
            name: 'Sin datos',
            itemStyle: { color: '#C0C0C0' }
        });
    }

    // Configuración de la segunda gráfica
    function getOption2(chartData) {
        const responsiveConfig = getResponsiveConfig();
        return {
            tooltip: {
                trigger: 'item',
                formatter: function(params) {
                    // Formateo mejorado del tooltip con el nombre en el color del segmento
                    return `<div style="text-align:center; font-weight:bold; margin-bottom:8px; color:${params.color}; font-size:15px;">${params.name}</div>
                            <div style="display:flex; justify-content:space-between; margin-bottom:5px;">
                                <span style="color:rgba(0,0,0,0.7);">Valor:</span>
                                <span style="font-weight:bold; color:#333;">$${params.value.toLocaleString()}</span>
                            </div>
                            <div style="display:flex; justify-content:space-between;">
                                <span style="color:rgba(0,0,0,0.7);">Porcentaje:</span> 
                                <span style="font-weight:bold; color:#333;">${params.percent}%</span>
                            </div>`;
                },
                confine: true,
                appendToBody: false,
                backgroundColor: 'rgba(255, 255, 255, 0.75)',
                borderColor: 'rgba(70, 135, 230, 0.4)',
                borderWidth: 2,
                padding: [12, 18],
                textStyle: {
                    color: '#333',
                    fontSize: 14
                },
                extraCssText: 'box-shadow: 0 6px 16px rgba(0,0,0,0.12); border-radius: 12px; backdrop-filter: blur(4px); pointer-events: none; transition: all 0.3s cubic-bezier(0.23, 1, 0.32, 1);',
                position: function(pos, params, el, elRect, size) {
                    // Posicionamiento optimizado para móviles
                    const obj = {top: 10};
                    obj[['left', 'right'][+(pos[0] < size.viewSize[0] / 2)]] = 30;
                    return obj;
                },
                triggerOn: 'mousemove|click',  // Activar con hover(mousemove) y con click o toque
                enterable: false,              // No bloquear interacción con elementos debajo
                hideDelay: 300,                // Tiempo antes de ocultar en milisegundos
                transitionDuration: 0.4,       // Duración de la animación de transición
            },
            legend: responsiveConfig.mobile ? {
                ...responsiveConfig.legendConfig,
                data: chartData.map(item => item.name)
            } : undefined,
            series: [{
                name: 'Top 10',
                type: 'pie',
                radius: responsiveConfig.pieRadius,
                center: responsiveConfig.pieCenter,
                avoidLabelOverlap: false,
                itemStyle: {
                    borderRadius: 10,
                    borderColor: '#fff',
                    borderWidth: 2
                },
                label: {
                    show: false
                },
                emphasis: {
                    scale: true,
                    scaleSize: responsiveConfig.mobile ? 5 : 10,
                    itemStyle: {
                        shadowBlur: 15,
                        shadowColor: 'rgba(0, 0, 0, 0.3)',
                        opacity: 0.8
                    }
                },
                data: chartData
            }]
        };
    }

    let option2 = getOption2(chartData);

    // Renderizar las gráficas
    if (dom2) {
        myChart2.setOption(option2);
        // Forzar resize después de un pequeño delay
        setTimeout(() => {
            myChart2.resize();
        }, 100);

        // Agregar animación avanzada para cambios entre elementos
        document.getElementById('chart2').addEventListener('mousedown', function() {
            const tooltipEl = document.querySelector('.echarts-tooltip');
            if (tooltipEl) {
                tooltipEl.style.transition = 'all 0.4s cubic-bezier(0.23, 1, 0.32, 1)';
                tooltipEl.style.opacity = '0';
                setTimeout(() => {
                    tooltipEl.style.opacity = '1';
                }, 50);
            }
        });
    }

    // Función para cargar datos del dashboard
    function loadDashboardData(vista, periodo = null, periodoSalesVolumeByRoles = null, periodoOrdersByStatusGlobal = null, periodoTop5OrganizationSalespeople = null) {
        const loader = document.getElementById('dashboardLoader');
        loader.classList.add('active');
        
        // Si no se especifica período, usar el actual
        if (periodo === null) {
            periodo = currentPeriodo;
        }
        
        // Si no se especifica período para Sales Volume by Roles, usar el actual
        if (periodoSalesVolumeByRoles === null) {
            periodoSalesVolumeByRoles = currentPeriodoSalesVolumeByRoles;
        }
        
        // Si no se especifica período para Orders by Status Global, usar el actual
        if (periodoOrdersByStatusGlobal === null) {
            periodoOrdersByStatusGlobal = currentPeriodoOrdersByStatusGlobal;
        }
        
        // Si no se especifica período para Top 5 Organization Salespeople, usar el actual
        if (periodoTop5OrganizationSalespeople === null) {
            periodoTop5OrganizationSalespeople = currentPeriodoTop5OrganizationSalespeople;
        }
        
        fetch(`/dashboard/data?vista=${vista}&periodo=${periodo}&periodoSalesVolumeByRoles=${periodoSalesVolumeByRoles}&periodoOrdersByStatusGlobal=${periodoOrdersByStatusGlobal}&periodoTop5OrganizationSalespeople=${periodoTop5OrganizationSalespeople}`)
            .then(response => response.json())
            .then(data => {
                dashboardData = data;
                updateDashboard(data, vista, periodo, periodoSalesVolumeByRoles, periodoOrdersByStatusGlobal, periodoTop5OrganizationSalespeople);
                loader.classList.remove('active');
                
                // Forzar resize de todas las gráficas después de cargar los datos
                setTimeout(() => {
                    if (myChart2) myChart2.resize();
                    if (ordersByStatusChart) ordersByStatusChart.resize();
                    if (volumenVentasChart) volumenVentasChart.resize();
                    if (revenueVsGoalsChart) revenueVsGoalsChart.resize();
                    if (salesTrendChart) salesTrendChart.resize();
                    if (dailySalesChart) dailySalesChart.resize();
                    if (salesByRegionChart) salesByRegionChart.resize();
                    if (salesVolumeByRolesChart) salesVolumeByRolesChart.resize();
                    if (salesVolumeByOfficesChart) salesVolumeByOfficesChart.resize();
                    if (top5OrganizationChart) top5OrganizationChart.resize();
                    if (salesByRegionGlobalChart) salesByRegionGlobalChart.resize();
                    if (salesByOfficeChart) salesByOfficeChart.resize();
                }, 300);
            })
            .catch(error => {
                console.error('Error al cargar datos:', error);
                loader.classList.remove('active');
            });
    }

    // Función para actualizar el dashboard
    function updateDashboard(data, vista, periodo = 'anual', periodoSalesVolumeByRoles = 'anual', periodoOrdersByStatusGlobal = 'anual', periodoTop5OrganizationSalespeople = 'anual') {
        // Remover la clase fade-in de todas las cards
        document.querySelectorAll('.dashboard-card').forEach(card => {
            card.classList.remove('fade-in');
        });

        // Forzar un reflow
        void document.body.offsetHeight;

        // Agregar la clase fade-in a todas las cards
        document.querySelectorAll('.dashboard-card').forEach(card => {
            card.classList.add('fade-in');
        });

        // Mostrar/ocultar controles de período según vista y rol
        const periodoControls = document.getElementById('periodoControls');
        if (periodoControls) {
            // Mostrar controles en todas las vistas (personal, equipo, oficina)
            periodoControls.style.display = (vista !== 'global') ? 'block' : 'none';
        }

        // Mostrar/ocultar controles de período para Sales Volume by Roles según vista
        const salesVolumeByRolesPeriodoControls = document.getElementById('salesVolumeByRolesPeriodoControls');
        if (salesVolumeByRolesPeriodoControls) {
            // Mostrar controles solo en vista global
            salesVolumeByRolesPeriodoControls.style.display = (vista === 'global') ? 'block' : 'none';
        }

        // Mostrar/ocultar controles de período para Orders by Status en vista global
        const ordersByStatusGlobalPeriodoControls = document.getElementById('ordersByStatusGlobalPeriodoControls');
        if (ordersByStatusGlobalPeriodoControls) {
            // Mostrar controles solo en vista global
            ordersByStatusGlobalPeriodoControls.style.display = (vista === 'global') ? 'block' : 'none';
        }

        // Mostrar/ocultar controles de período para Top 5 Organization Salespeople en vista global
        const top5OrganizationSalespeopleControls = document.getElementById('top5OrganizationSalespeopleControls');
        if (top5OrganizationSalespeopleControls) {
            // Mostrar controles solo en vista global
            top5OrganizationSalespeopleControls.style.display = (vista === 'global') ? 'block' : 'none';
        }

        // Actualizar subtítulos según la vista
        const aplicacionesSubtitle = document.getElementById('aplicacionesSubtitle');
        const ordersByStatusSubtitle = document.getElementById('ordersByStatusSubtitle');
        const volumenVentasSubtitle = document.getElementById('volumenVentasSubtitle');
        const revenueGoalsSubtitle = document.getElementById('revenueGoalsSubtitle');
        const salesTrendSubtitle = document.getElementById('salesTrendSubtitle');
        const dailySalesSubtitle = document.getElementById('dailySalesSubtitle');
        const salesVolumeByRolesSubtitle = document.getElementById('salesVolumeByRolesSubtitle');
        
        const vistaText = vista === 'personal' ? 'Personal' : vista === 'oficina' ? 'de Oficina' : vista === 'global' ? 'Global' : 'de Equipo';
        
        if (aplicacionesSubtitle) {
            aplicacionesSubtitle.textContent = `Últimos 12 meses - Vista ${vistaText}`;
        }
        
        if (ordersByStatusSubtitle) {
            // Para vista global, usar el período específico de Orders by Status
            const periodoToUse = vista === 'global' ? periodoOrdersByStatusGlobal : periodo;
            const periodoText = periodoToUse === '90dias' ? 'Últimos 90 días' : 'Año actual (del 3 de enero al 2 de enero siguiente)';
            ordersByStatusSubtitle.textContent = `${periodoText} - Vista ${vistaText}`;
        }
        
        if (volumenVentasSubtitle) {
            volumenVentasSubtitle.textContent = `Instalaciones completadas por mes - Vista ${vistaText}`;
        }

        if (revenueGoalsSubtitle) {
            revenueGoalsSubtitle.textContent = `Comparación del mes actual - Vista ${vistaText}`;
        }

        if (salesTrendSubtitle) {
            salesTrendSubtitle.textContent = `Año actual - Vista ${vistaText}`;
        }

        if (dailySalesSubtitle) {
            dailySalesSubtitle.textContent = `Mes actual (del 3 al 2) - Vista ${vistaText}`;
        }

        if (salesVolumeByRolesSubtitle) {
            const periodoSalesVolumeByRolesText = periodoSalesVolumeByRoles === '90dias' ? 'Últimos 90 días' : 'Año actual (del 3 de enero al 2 de enero siguiente)';
            salesVolumeByRolesSubtitle.textContent = `Distribución de ventas completadas por rol (${periodoSalesVolumeByRolesText}) - Vista ${vistaText}`;
        }

        // Actualizar subtítulo de Top 10 Vendedores Globales
        const top10GlobalSubtitle = document.getElementById('top10GlobalSubtitle');
        if (top10GlobalSubtitle) {
            const periodoTop5OrganizationSalespeopleText = periodoTop5OrganizationSalespeople === '90dias' ? 'Últimos 90 días' : 'Mes actual';
            top10GlobalSubtitle.textContent = `${periodoTop5OrganizationSalespeopleText} - Instalación Completada`;
        }
        
        // Actualizar métricas según la vista
        if (data.totalRevenue !== undefined) {
            document.getElementById('totalRevenue').textContent = '$' + Number(data.totalRevenue).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }
        
        if (data.totalOrders !== undefined) {
            document.getElementById('totalOrders').textContent = data.totalOrders.toLocaleString();
        }

        // Actualizar títulos según la vista
        const ventasTitle = document.getElementById('ventasTitle');
        const ordenesTitle = document.getElementById('ordenesTitle');
        
        if (ventasTitle) {
            ventasTitle.textContent = vista === 'personal' ? 'Mis Ventas del Año' : 
                                    vista === 'oficina' ? ({{ $rol_co ?? 0 }} === 8 ? 'Ventas de Mis Oficinas del Año' : 'Ventas de Mi Oficina del Año') : 
                                    'Ventas del Equipo del Año';
        }
        
        if (ordenesTitle) {
            ordenesTitle.textContent = vista === 'personal' ? 'Mis Órdenes del Año' : 
                                     vista === 'oficina' ? ({{ $rol_co ?? 0 }} === 8 ? 'Órdenes de Mis Oficinas del Año' : 'Órdenes de Mi Oficina del Año') : 
                                     'Órdenes del Equipo del Año';
        }

        if (vista === 'global') {
            ventasTitle.textContent = 'Ventas Globales del Año';
            ordenesTitle.textContent = 'Órdenes Globales del Año';
        }

        // Eliminar las tarjetas duplicadas si existen
        const equipoCards = document.getElementById('equipoCards');
        if (equipoCards) {
            equipoCards.remove();
        }
        
        // Actualizar gráfica Orders by Status
        if (data.ordersByStatus && ordersByStatusChart) {
            const statusData = data.ordersByStatus.map(item => ({
                value: item.cantidad_aplicaciones,
                name: item.nombre_estatus
            }));
            
            // Verificar si hay datos
            if (statusData.length === 0 || statusData.every(item => item.value === 0)) {
                statusData.push({
                    value: 1,
                    name: 'Sin aplicaciones instaladas este mes'
                });
            }
            
            const responsiveConfig = getResponsiveConfig();
            
            const statusOption = {
                tooltip: {
                    trigger: 'item',
                    formatter: function(params) {
                        if (params.name === 'Sin aplicaciones este mes') {
                            return 'No hay aplicaciones en el período seleccionado';
                        }
                        return `${params.name}<br/>Cantidad: ${params.value} (${params.percent}%)`;
                    }
                },
                legend: {
                    ...responsiveConfig.legendConfig,
                    data: statusData.map(item => item.name)
                },
                series: [{
                    name: 'Orders by Status',
                    type: 'pie',
                    radius: responsiveConfig.pieRadius,
                    center: responsiveConfig.pieCenter,
                    avoidLabelOverlap: false,
                    itemStyle: {
                        borderRadius: 10,
                        borderColor: '#fff',
                        borderWidth: 2
                    },
                    label: {
                        show: false,
                        position: 'center'
                    },
                    emphasis: {
                        label: {
                            show: true,
                            fontSize: responsiveConfig.mobile ? '14' : '16',
                            fontWeight: 'bold'
                        }
                    },
                    labelLine: {
                        show: false
                    },
                    data: statusData
                }],
                color: ['#4687e6', '#13c0e6', '#8ce04f', '#FFA500', '#FF6B6B', '#9775FA', '#63E6BE', '#74C0FC', '#B197FC', '#FFD93D']
            };
            
            ordersByStatusChart.setOption(statusOption, true);
            // Forzar resize
            setTimeout(() => {
                ordersByStatusChart.resize();
            }, 100);
        }
        
        // Actualizar gráfica de volumen de ventas comparativo
        if (data.volumenVentas && volumenVentasChart) {
            const anioActual = new Date().getFullYear();
            const anioAnterior = anioActual - 1;
            
            const mesesNombres = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
            
            const datosAnioActual = [];
            const datosAnioAnterior = [];
            const cantidadesAnioActual = [];
            const cantidadesAnioAnterior = [];
            
            data.volumenVentas.forEach(item => {
                const mesIndex = parseInt(item.numero_mes) - 1;
                if (item.anio == anioActual) {
                    datosAnioActual[mesIndex] = parseFloat(item.monto_total_mensual).toFixed(2);
                    cantidadesAnioActual[mesIndex] = item.cantidad_aplicaciones;
                } else if (item.anio == anioAnterior) {
                    datosAnioAnterior[mesIndex] = parseFloat(item.monto_total_mensual).toFixed(2);
                    cantidadesAnioAnterior[mesIndex] = item.cantidad_aplicaciones;
                }
            });
            
            // Configuración para la gráfica de montos
            const responsiveConfigVolumen = getResponsiveConfig();

            const volumenOption = {
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'shadow'
                    },
                    formatter: function(params) {
                        let result = params[0].name + '<br/>';
                        params.forEach(item => {
                            const value = parseFloat(item.value).toFixed(2);
                            result += item.marker + ' ' + item.seriesName + ': $' + value.replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '<br/>';
                        });
                        return result;
                    }
                },
                legend: {
                    data: [`Año ${anioActual}`, `Año ${anioAnterior}`],
                    ...(responsiveConfigVolumen.mobile && {
                        orient: 'horizontal',
                        bottom: 0,
                        left: 'center',
                        textStyle: { fontSize: 10 }
                    })
                },
                grid: responsiveConfigVolumen.gridConfig,
                xAxis: {
                    type: 'category',
                    data: mesesNombres,
                    axisLabel: {
                        ...(responsiveConfigVolumen.mobile && {
                            rotate: 45,
                            fontSize: 10
                        })
                    }
                },
                yAxis: {
                    type: 'value',
                    name: 'Monto',
                    axisLabel: {
                        formatter: function(value) {
                            return '$' + (value / 1000).toFixed(2) + 'k';
                        },
                        fontSize: responsiveConfigVolumen.mobile ? 10 : 12
                    }
                },
                series: [
                    {
                        name: `Año ${anioActual}`,
                        type: 'bar',
                        data: datosAnioActual,
                        itemStyle: {
                            color: '#4687e6'
                        }
                    },
                    {
                        name: `Año ${anioAnterior}`,
                        type: 'bar',
                        data: datosAnioAnterior,
                        itemStyle: {
                            color: '#13c0e6'
                        }
                    }
                ]
            };
            
            // Configuración para la gráfica de cantidades
            const responsiveConfigCantidad = getResponsiveConfig();

            const cantidadOption = {
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'shadow'
                    },
                    formatter: function(params) {
                        let result = params[0].name + '<br/>';
                        params.forEach(item => {
                            result += item.marker + ' ' + item.seriesName + ': ' + item.value + ' aplicaciones<br/>';
                        });
                        return result;
                    }
                },
                legend: {
                    data: [`Año ${anioActual}`, `Año ${anioAnterior}`],
                    ...(responsiveConfigCantidad.mobile && {
                        orient: 'horizontal',
                        bottom: 0,
                        left: 'center',
                        textStyle: { fontSize: 10 }
                    })
                },
                grid: responsiveConfigCantidad.gridConfig,
                xAxis: {
                    type: 'category',
                    data: mesesNombres,
                    axisLabel: {
                        ...(responsiveConfigCantidad.mobile && {
                            rotate: 45,
                            fontSize: 10
                        })
                    }
                },
                yAxis: {
                    type: 'value',
                    name: 'Cantidad',
                    axisLabel: {
                        formatter: '{value}',
                        fontSize: responsiveConfigCantidad.mobile ? 10 : 12
                    }
                },
                series: [
                    {
                        name: `Año ${anioActual}`,
                        type: 'bar',
                        data: cantidadesAnioActual,
                        itemStyle: {
                            color: '#8ce04f'
                        }
                    },
                    {
                        name: `Año ${anioAnterior}`,
                        type: 'bar',
                        data: cantidadesAnioAnterior,
                        itemStyle: {
                            color: '#FFA500'
                        }
                    }
                ]
            };
            
            volumenVentasChart.setOption(volumenOption);
            
            // Inicializar y configurar la gráfica de cantidades
            const cantidadVentasDom = document.getElementById('cantidadVentasChart');
            if (cantidadVentasDom) {
                const cantidadVentasChart = echarts.init(cantidadVentasDom);
                cantidadVentasChart.setOption(cantidadOption);
                
                // Agregar al evento resize
                window.addEventListener('resize', function() {
                    cantidadVentasChart.resize();
                });
            }
        }
        
        // Actualizar Top 10 del equipo
        if (vista === 'equipo' && data.equipo && data.equipo.getTop10TeamMembers) {
            const top10Data = data.equipo.getTop10TeamMembers.map((item, index) => ({
                value: item.value,
                name: item.name,
                itemStyle: {
                    color: ['#4687e6', '#13c0e6', '#8ce04f', '#FFA500', '#C0C0C0', '#FF6B6B', '#9775FA', '#63E6BE', '#74C0FC', '#B197FC'][index % 10]
                }
            }));
            
            // Usar la función responsive para obtener la configuración actualizada
            const updatedOption2 = getOption2(top10Data);
            
            // Reinicializar la gráfica antes de actualizar
            initChart2();
            myChart2.setOption(updatedOption2);
            
            // Forzar resize después de un pequeño delay
            setTimeout(() => {
                myChart2.resize();
            }, 100);
            
            // Actualizar lista del equipo
            const teamList = document.getElementById('team-list');
            teamList.innerHTML = '';
            data.equipo.getTop10TeamMembers.forEach((item, index) => {
                const li = document.createElement('li');
                li.className = 'team-item';
                li.innerHTML = `
                    <span class="team-color" style="background-color: ${['#4687e6', '#13c0e6', '#8ce04f', '#FFA500', '#C0C0C0', '#FF6B6B', '#9775FA', '#63E6BE', '#74C0FC', '#B197FC'][index % 10]};"></span>
                    <span class="team-name">${item.name}</span>
                `;
                teamList.appendChild(li);
            });
        }
        
        // Primero verificamos que el contenedor exista
        if (top10TeamVendorsContainer) {
            top10TeamVendorsContainer.style.display = vista === 'equipo' ? 'block' : 'none';
        }
        if (top10OfficeSalespeopleContainer) {
            top10OfficeSalespeopleContainer.style.display = vista === 'oficina' ? 'block' : 'none';
        }
        // Mostrar Top 10 Mis Oficinas solo en vista oficina y rol 8
        if (top10MisOficinasContainer) {
            top10MisOficinasContainer.style.display = (vista === 'oficina' && rolCo === 8) ? 'block' : 'none';
        }

        // Mostrar/ocultar contenedores específicos de la vista global
        const salesVolumeByRolesContainer = document.getElementById('salesVolumeByRolesContainer');
        const top10GlobalContainer = document.getElementById('top10GlobalContainer');
        const salesByRegionGlobalContainer = document.getElementById('salesByRegionGlobalContainer');
        const top3OfficesContainer = document.getElementById('top3OfficesContainer');

        if (salesVolumeByRolesContainer) {
            salesVolumeByRolesContainer.style.display = vista === 'global' ? 'block' : 'none';
        }
        if (top10GlobalContainer) {
            top10GlobalContainer.style.display = vista === 'global' ? 'block' : 'none';
        }
        if (salesByRegionGlobalContainer) {
            salesByRegionGlobalContainer.style.display = vista === 'global' ? 'block' : 'none';
        }
        if (top3OfficesContainer) {
            top3OfficesContainer.style.display = vista === 'global' ? 'block' : 'none';
        }
        // Actualizar Top 10 Team Vendors
        if (top10TeamVendorsContainer) {
            top10TeamVendorsContainer.style.display = vista === 'equipo' ? 'block' : 'none';
            
            // Si estamos en vista de equipo, inicializar la gráfica con los datos
            if (vista === 'equipo' && data.equipo && data.equipo.getTop10TeamVendors) {
                const chartDom = document.getElementById('top10TeamVendorsChart');
                if (chartDom) {
                    // Verificar si hay datos
                    let chartData = [];
                    if (!data.equipo.getTop10TeamVendors || data.equipo.getTop10TeamVendors.length === 0 || data.equipo.getTop10TeamVendors.every(item => item.value === 0)) {
                        chartData.push({
                            value: 1,
                            name: 'Sin ventas este mes',
                            itemStyle: { color: '#4687e6' }
                        });
                    } else {
                        chartData = data.equipo.getTop10TeamVendors.map((item, index) => ({
                            value: item.value,
                            name: item.name,
                            itemStyle: {
                                color: ['#4687e6', '#13c0e6', '#8ce04f', '#FFA500', '#C0C0C0', '#FF6B6B', '#9775FA', '#63E6BE', '#74C0FC', '#B197FC'][index % 10]
                            }
                        }));
                    }

                    const myChart = echarts.init(chartDom);
                    const responsiveConfigVendors = getResponsiveConfig();
                    
                    const option = {
                        tooltip: {
                            trigger: 'item',
                            formatter: function(params) {
                                if (params.name === 'Sin ventas este mes') {
                                    return 'No hay aplicaciones en el período seleccionado';
                                }
                                return `${params.name}<br/>Cantidad: ${params.value} (${params.percent}%)`;
                            }
                        },
                        legend: {
                            ...responsiveConfigVendors.legendConfig,
                            data: chartData.map(item => item.name)
                        },
                        series: [{
                            name: 'Top 10 Vendedores del Equipo',
                            type: 'pie',
                            radius: responsiveConfigVendors.pieRadius,
                            center: responsiveConfigVendors.pieCenter,
                            avoidLabelOverlap: false,
                            itemStyle: {
                                borderRadius: 10,
                                borderColor: '#fff',
                                borderWidth: 2
                            },
                            label: {
                                show: false,
                                position: 'center'
                            },
                            emphasis: {
                                label: {
                                    show: true,
                                    fontSize: responsiveConfigVendors.mobile ? '14' : '16',
                                    fontWeight: 'bold'
                                }
                            },
                            labelLine: {
                                show: false
                            },
                            data: chartData
                        }]
                    };
                    
                    myChart.setOption(option, true);
                    
                    // Forzar resize después de un pequeño delay
                    setTimeout(() => {
                        myChart.resize();
                    }, 100);
                }
            }
        }
        if (top10OfficeSalespeopleContainer) {
            // Mostramos el contenedor solo si estamos en la vista oficina y el rol es 7 u 8
            const userRole = {{ $rol_co ?? 0 }}; // Obtenemos el rol del usuario
            const shouldShow = vista === 'oficina' && (userRole === 6 || userRole === 7);
            top10OfficeSalespeopleContainer.style.display = shouldShow ? 'block' : 'none';
            console.log('Vista actual:', vista);
            console.log('Rol del usuario:', userRole);
            console.log('Estado del contenedor:', top10OfficeSalespeopleContainer.style.display);
            
            // Si estamos en la vista oficina y el rol es correcto, hacemos resize de la gráfica
            if (shouldShow) {
                const chartElement = document.getElementById('top10OfficeSalespeopleChart');
                if (chartElement) {
                    const chart = echarts.getInstanceByDom(chartElement);
                    if (chart) {
                        setTimeout(() => {
                            chart.resize();
                        }, 100);
                    }
                }
            }
        } else {
            console.error('No se encontró el contenedor que contiene top10OfficeSalespeopleChart');
        }
        
        // Actualizar Revenue vs Goals
        if (data.revenueVsGoals && revenueVsGoalsChart) {
            const revenueData = data.revenueVsGoals;
            const mesActual = new Date().toLocaleString('es', { month: 'long' });
            const anioActual = new Date().getFullYear();
            const anioAnterior = anioActual - 1;
            
            const revenueOption = {
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'shadow'
                    },
                    formatter: function(params) {
                        return `${params[0].name}<br/>
                                ${params[0].marker} ${params[0].seriesName}: $${params[0].value.toLocaleString()}<br/>
                                ${params[1].marker} ${params[1].seriesName}: $${params[1].value.toLocaleString()}<br/>
                                <strong>Diferencia: ${((params[0].value - params[1].value) / params[1].value * 100).toFixed(1)}%</strong>`;
                    }
                },
                legend: {
                    data: [`${mesActual} ${anioActual}`, `${mesActual} ${anioAnterior}`]
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '3%',
                    containLabel: true
                },
                xAxis: {
                    type: 'category',
                    data: [mesActual]
                },
                yAxis: {
                    type: 'value',
                    axisLabel: {
                        formatter: function(value) {
                            return '$' + (value / 1000) + 'k';
                        }
                    }
                },
                series: [
                    {
                        name: `${mesActual} ${anioActual}`,
                        type: 'bar',
                        data: [revenueData.revenue_actual],
                        itemStyle: {
                            color: '#4687e6'
                        }
                    },
                    {
                        name: `${mesActual} ${anioAnterior}`,
                        type: 'bar',
                        data: [revenueData.revenue_anterior],
                        itemStyle: {
                            color: '#13c0e6'
                        }
                    }
                ]
            };
            
            revenueVsGoalsChart.setOption(revenueOption);
        }

        // Actualizar Sales Trend
        if (data.salesTrend && salesTrendChart) {
            const trendData = data.salesTrend;
            const meses = trendData.map(item => item.mes_nombre);
            const montos = trendData.map(item => item.monto);
            
            const trendOption = {
                tooltip: {
                    trigger: 'axis',
                    formatter: function(params) {
                        return `${params[0].name}<br/>
                                Monto: $${params[0].value.toLocaleString()}`;
                    }
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '3%',
                    containLabel: true
                },
                xAxis: {
                    type: 'category',
                    data: meses,
                    axisLabel: {
                        rotate: 45,
                        interval: 0
                    }
                },
                yAxis: {
                    type: 'value',
                    axisLabel: {
                        formatter: function(value) {
                            return '$' + (value / 1000) + 'k';
                        }
                    }
                },
                series: [{
                    name: 'Tendencia de Ventas',
                    type: 'line',
                    smooth: true,
                    data: montos,
                    itemStyle: {
                        color: '#8ce04f'
                    },
                    areaStyle: {
                        color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                            { offset: 0, color: 'rgba(140, 224, 79, 0.3)' },
                            { offset: 1, color: 'rgba(140, 224, 79, 0.05)' }
                        ])
                    }
                }]
            };
            
            salesTrendChart.setOption(trendOption);
        }

        // Actualizar Daily Sales
        if (data.dailySales && dailySalesChart) {
            const dias = data.dailySales.map(item => item.dia_numero);
            const montos = data.dailySales.map(item => item.monto || 0);
            const cantidades = data.dailySales.map(item => item.cantidad || 0);
            
            const dailyOption = {
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'shadow'
                    },
                    formatter: function(params) {
                        let result = `Día ${params[0].name}<br/>`;
                        params.forEach(item => {
                            const value = item.value || 0;
                            if (item.seriesName === 'Monto') {
                                result += `${item.marker} ${item.seriesName}: $${value.toLocaleString()}<br/>`;
                            } else {
                                result += `${item.marker} ${item.seriesName}: ${value}<br/>`;
                            }
                        });
                        return result;
                    }
                },
                legend: {
                    data: ['Monto', 'Cantidad']
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '3%',
                    containLabel: true
                },
                xAxis: {
                    type: 'category',
                    data: dias,
                    axisLabel: {
                        formatter: '{value}'
                    }
                },
                yAxis: [
                    {
                        type: 'value',
                        name: 'Monto',
                        position: 'left',
                        axisLabel: {
                            formatter: function(value) {
                                return '$' + (value / 1000).toFixed(2) + 'k';
                            }
                        }
                    },
                    {
                        type: 'value',
                        name: 'Cantidad',
                        position: 'right',
                        axisLabel: {
                            formatter: '{value}'
                        }
                    }
                ],
                series: [
                    {
                        name: 'Monto',
                        type: 'bar',
                        data: montos,
                        itemStyle: {
                            color: '#4687e6'
                        }
                    },
                    {
                        name: 'Cantidad',
                        type: 'line',
                        yAxisIndex: 1,
                        data: cantidades,
                        itemStyle: {
                            color: '#8ce04f'
                        }
                    }
                ]
            };
            
            dailySalesChart.setOption(dailyOption);
        }

        // Actualizar Sales by Region
        if (data.salesByRegion && salesByRegionChart) {
            const regionData = data.salesByRegion;
            const paises = regionData.map(item => item.pais);
            const montos = regionData.map(item => item.monto);
            
            const regionOption = {
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'shadow'
                    },
                    formatter: function(params) {
                        return `${params[0].name}<br/>
                                Monto: $${params[0].value.toLocaleString()}`;
                    }
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '15%',
                    containLabel: true
                },
                xAxis: {
                    type: 'category',
                    data: paises,
                    axisLabel: {
                        rotate: 45,
                        interval: 0
                    }
                },
                yAxis: {
                    type: 'value',
                    axisLabel: {
                        formatter: function(value) {
                            return '$' + (value / 1000) + 'k';
                        }
                    }
                },
                series: [{
                    name: 'Ventas por País',
                    type: 'bar',
                    data: montos,
                    itemStyle: {
                        color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                            { offset: 0, color: '#4687e6' },
                            { offset: 1, color: '#13c0e6' }
                        ])
                    }
                }]
            };
            
            salesByRegionChart.setOption(regionOption);
        }

        // Actualizar Sales Volume by Roles
        if (data.global && data.global.getSalesVolumeByRoles && salesVolumeByRolesChart) {
            let rolesData = data.global.getSalesVolumeByRoles;
            
            // Verificar si hay datos
            if (!rolesData || rolesData.length === 0) {
                rolesData = [{
                    grupo_rol: 'Sin datos',
                    monto: 0
                }];
            }

            const responsiveConfig = getResponsiveConfig();

            const rolesOption = {
                tooltip: {
                    trigger: 'item',
                    formatter: function(params) {
                        if (params.name === 'Sin datos') {
                            return 'No hay datos disponibles';
                        }
                        return `${params.name}<br/>
                                Monto: $${params.value.toLocaleString()}<br/>
                                Porcentaje: ${params.percent}%`;
                    }
                },
                legend: responsiveConfig.legendConfig,
                series: [{
                    name: 'Volumen por Rol',
                    type: 'pie',
                    radius: responsiveConfig.pieRadius,
                    center: responsiveConfig.pieCenter,
                    avoidLabelOverlap: false,
                    itemStyle: {
                        borderRadius: 10,
                        borderColor: '#fff',
                        borderWidth: 2
                    },
                    label: {
                        show: false,
                        position: 'center'
                    },
                    emphasis: {
                        label: {
                            show: true,
                            fontSize: responsiveConfig.mobile ? '14' : '16',
                            fontWeight: 'bold'
                        }
                    },
                    labelLine: {
                        show: false
                    },
                    data: rolesData.map((item, index) => ({
                        value: parseFloat(item.monto),
                        name: item.grupo_rol,
                        itemStyle: {
                            color: ['#4687e6', '#13c0e6', '#8ce04f', '#FFA500', '#FF6B6B', '#9775FA', '#63E6BE', '#74C0FC'][index % 8]
                        }
                    }))
                }]
            };
            
            salesVolumeByRolesChart.setOption(rolesOption, true);
            
            // Forzar resize y actualización
            setTimeout(() => {
                salesVolumeByRolesChart.resize();
            }, 100);
        }

        // Actualizar Sales Volume by Offices
        if (data.salesVolumeByOffices && salesVolumeByOfficesChart) {
            const officesData = data.salesVolumeByOffices;
            const oficinas = officesData.map(item => item.oficina);
            const montos = officesData.map(item => item.monto);
            
            const officesOption = {
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'shadow'
                    },
                    formatter: function(params) {
                        return `${params[0].name}<br/>
                                Monto: $${params[0].value.toLocaleString()}`;
                    }
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '25%',
                    containLabel: true
                },
                xAxis: {
                    type: 'category',
                    data: oficinas,
                    axisLabel: {
                        rotate: 45,
                        interval: 0,
                        fontSize: 10
                    }
                },
                yAxis: {
                    type: 'value',
                    axisLabel: {
                        formatter: function(value) {
                            return '$' + (value / 1000) + 'k';
                        }
                    }
                },
                dataZoom: [{
                    type: 'slider',
                    show: true,
                    xAxisIndex: [0],
                    start: 0,
                    end: 100
                }],
                series: [{
                    name: 'Volumen por Oficina',
                    type: 'bar',
                    data: montos,
                    itemStyle: {
                        color: function(params) {
                            const colors = ['#4687e6', '#13c0e6', '#8ce04f', '#FFA500', '#FF6B6B'];
                            return colors[params.dataIndex % colors.length];
                        }
                    }
                }]
            };
            
            salesVolumeByOfficesChart.setOption(officesOption);
        }

        // Top 3 Offices
        const top3OfficesChart = echarts.init(document.getElementById('top3OfficesChart'));
        const top3OfficesData = data.global && data.global.getTop3Offices ? data.global.getTop3Offices : [];
        const responsiveConfigOffices = getResponsiveConfig();
        
        const top3OfficesOption = {
            tooltip: {
                trigger: 'item',
                formatter: function(params) {
                    return `${params.name}<br/>
                            Aplicaciones: <strong>${params.value}</strong><br/>
                            Porcentaje: ${params.percent}%`;
                }
            },
            // No usar leyenda interna ya que tiene lista lateral
            legend: {
                show: false
            },
            series: [{
                name: 'Top 3 Oficinas',
                type: 'pie',
                radius: responsiveConfigOffices.pieRadius,
                center: responsiveConfigOffices.pieCenter,
                avoidLabelOverlap: false,
                itemStyle: {
                    borderRadius: 10,
                    borderColor: '#fff',
                    borderWidth: 2
                },
                label: {
                    show: false,
                    position: 'center'
                },
                emphasis: {
                    label: {
                        show: true,
                        fontSize: responsiveConfigOffices.mobile ? '14' : '16',
                        fontWeight: 'bold'
                    }
                },
                labelLine: {
                    show: false
                },
                data: top3OfficesData.length > 0 ? top3OfficesData.map((office, index) => ({
                    value: office.cantidad_aplicaciones || office.cantidad, // Soporta ambos nombres
                    name: office.oficina,
                    itemStyle: {
                        color: ['#4687e6', '#13c0e6', '#8ce04f'][index % 3]
                    }
                })) : []
            }]
        };

        top3OfficesChart.setOption(top3OfficesOption);

        // Forzar resize y actualización
        setTimeout(() => {
            top3OfficesChart.resize();
        }, 100);

        // Actualizar lista de Top 3 Oficinas
        const top3OfficesList = document.getElementById('top3OfficesList');
        if (top3OfficesList) {
            top3OfficesList.innerHTML = '';
            if (top3OfficesData.length > 0) {
                top3OfficesData.forEach((office, index) => {
                    const li = document.createElement('li');
                    li.className = 'team-item';
                    li.innerHTML = `
                        <span class="team-color" style="background-color: ${['#4687e6', '#13c0e6', '#8ce04f'][index % 3]};"></span>
                        <span class="team-name">${office.oficina}</span>
                    `;
                    top3OfficesList.appendChild(li);
                });
            } else {
                const li = document.createElement('li');
                li.className = 'team-item';
                li.innerHTML = '<span class="team-name">No hay datos disponibles</span>';
                top3OfficesList.appendChild(li);
            }
        }

        // Actualizar Top 5 Salespeople
        if (data.top5Salespeople) {
            const top5Container = document.getElementById('top5SalespeopleList');
            if (top5Container) {
                top5Container.innerHTML = '';
                data.top5Salespeople.forEach((person, index) => {
                    const rankClass = index === 0 ? 'gold' : index === 1 ? 'silver' : index === 2 ? 'bronze' : 'other';
                    const html = `
                        <div class="top-item">
                            <div class="top-rank ${rankClass}">${index + 1}</div>
                            <div class="top-info">
                                <div class="top-name">${person.vendedor}</div>
                                <div class="top-stats">
                                    <span>Aplicaciones: <strong>${person.cantidad}</strong></span>
                                    <span>Monto: <strong class="top-value">$${person.monto.toLocaleString()}</strong></span>
                                </div>
                            </div>
                        </div>
                    `;
                    top5Container.innerHTML += html;
                });
            }
        }

        if (cantidadVentasSubtitle) {
            cantidadVentasSubtitle.textContent = `Número de aplicaciones por mes - Vista ${vista === 'personal' ? 'Personal' : vista === 'oficina' ? 'de Oficina' : vista === 'global' ? 'Global' : 'de Equipo'}`;
        }

        // Actualizar título y subtítulo del Top 10 según la vista
        const top10Title = document.getElementById('top10Title');
        const top10Subtitle = document.getElementById('top10Subtitle');
        
        if (top10Title && top10Subtitle) {
            if (vista === 'oficina') {
                top10Title.textContent = 'Top 10 Vendedores de mi Oficina';
                top10Subtitle.textContent = 'Mes actual';
            } else if (vista === 'equipo') {
                top10Title.textContent = 'Top 10 Vendedores de mi Equipo';
                top10Subtitle.textContent = 'Mes actual';
            }
        }

        // Actualizar Top 10 Global
        if (data.global && data.global.getTop10TeamMembers && top10GlobalChart) {
            const top10Data = data.global.getTop10TeamMembers.map((item, index) => ({
                value: item.value,
                name: item.name,
                itemStyle: {
                    color: ['#4687e6', '#13c0e6', '#8ce04f', '#FFA500', '#C0C0C0', '#FF6B6B', '#9775FA', '#63E6BE', '#74C0FC', '#B197FC'][index % 10]
                }
            }));

            const responsiveConfig = getResponsiveConfig();

            const top10GlobalOption = {
                tooltip: {
                    trigger: 'item',
                    formatter: function(params) {
                        return `<div style="text-align:center; font-weight:bold; margin-bottom:8px; color:${params.color}; font-size:15px;">${params.name}</div>
                                <div style="display:flex; justify-content:space-between; margin-bottom:5px;">
                                    <span style="color:rgba(0,0,0,0.7);">Aplicaciones:</span>
                                    <span style="font-weight:bold; color:#333;">${params.value}</span>
                                </div>
                                <div style="display:flex; justify-content:space-between;">
                                    <span style="color:rgba(0,0,0,0.7);">Porcentaje:</span> 
                                    <span style="font-weight:bold; color:#333;">${params.percent}%</span>
                                </div>`;
                    }
                },
                // No usar leyenda interna ya que tiene lista lateral
                legend: {
                    show: false
                },
                series: [{
                    name: 'Top 10 Global',
                    type: 'pie',
                    radius: responsiveConfig.pieRadius,
                    center: responsiveConfig.pieCenter,
                    avoidLabelOverlap: false,
                    itemStyle: {
                        borderRadius: 10,
                        borderColor: '#fff',
                        borderWidth: 2
                    },
                    label: {
                        show: false
                    },
                    emphasis: {
                        scale: true,
                        scaleSize: responsiveConfig.mobile ? 5 : 10,
                        itemStyle: {
                            shadowBlur: 15,
                            shadowColor: 'rgba(0, 0, 0, 0.3)',
                            opacity: 0.8
                        }
                    },
                    data: top10Data
                }]
            };

            top10GlobalChart.setOption(top10GlobalOption);

            // Actualizar lista del Top 10 Global
            const top10GlobalList = document.getElementById('top10GlobalList');
            if (top10GlobalList) {
                top10GlobalList.innerHTML = '';
                data.global.getTop10TeamMembers.forEach((item, index) => {
                    const li = document.createElement('li');
                    li.className = 'team-item';
                    li.innerHTML = `
                        <span class="team-color" style="background-color: ${['#4687e6', '#13c0e6', '#8ce04f', '#FFA500', '#C0C0C0', '#FF6B6B', '#9775FA', '#63E6BE', '#74C0FC', '#B197FC'][index % 10]};"></span>
                        <span class="team-name">${item.name}</span>
                    `;
                    top10GlobalList.appendChild(li);
                });
            }

            // Forzar resize después de un pequeño delay
            setTimeout(() => {
                top10GlobalChart.resize();
            }, 100);
        }

        // Top 5 Organization Salespeople
        if (top5OrganizationChart) {
            const top5OrganizationData = data.global && data.global.getTop5OrganizationSalespeople ? 
                data.global.getTop5OrganizationSalespeople.map((item, index) => ({
                    value: item.monto_total,
                    name: item.vendedor,
                    itemStyle: {
                        color: ['#4687e6', '#13c0e6', '#8ce04f', '#FFA500', '#C0C0C0'][index % 5]
                    }
                })) : [{
                    value: 1,
                    name: 'Sin datos disponibles',
                    itemStyle: {
                        color: '#C0C0C0'
                    }
                }];

            const top5OrganizationOption = {
                tooltip: {
                    trigger: 'item',
                    formatter: function(params) {
                        if (params.name === 'Sin datos disponibles') {
                            return 'No hay datos disponibles para mostrar';
                        }
                        return `<div style="text-align:center; font-weight:bold; margin-bottom:8px; color:${params.color}; font-size:15px;">${params.name}</div>
                                <div style="display:flex; justify-content:space-between; margin-bottom:5px;">
                                    <span style="color:rgba(0,0,0,0.7);">Monto:</span>
                                    <span style="font-weight:bold; color:#333;">$${params.value.toLocaleString()}</span>
                                </div>
                                <div style="display:flex; justify-content:space-between;">
                                    <span style="color:rgba(0,0,0,0.7);">Porcentaje:</span> 
                                    <span style="font-weight:bold; color:#333;">${params.percent}%</span>
                                </div>`;
                    }
                },
                series: [{
                    name: 'Top 5 Organization Salespeople',
                    type: 'pie',
                    radius: ['45%', '75%'],
                    center: ['50%', '50%'],
                    avoidLabelOverlap: false,
                    itemStyle: {
                        borderRadius: 10,
                        borderColor: '#fff',
                        borderWidth: 2
                    },
                    label: {
                        show: false
                    },
                    emphasis: {
                        scale: true,
                        scaleSize: 10,
                        itemStyle: {
                            shadowBlur: 15,
                            shadowColor: 'rgba(0, 0, 0, 0.3)',
                            opacity: 0.8
                        }
                    },
                    data: top5OrganizationData
                }]
            };

            if (top5OrganizationChart) {
                top5OrganizationChart.setOption(top5OrganizationOption);
            }

            // Actualizar lista de Top 5 Organization Salespeople
            const top5OrganizationList = document.getElementById('top5OrganizationList');
            if (top5OrganizationList) {
                top5OrganizationList.innerHTML = '';
                if (data.global && data.global.getTop5OrganizationSalespeople && data.global.getTop5OrganizationSalespeople.length > 0) {
                    data.global.getTop5OrganizationSalespeople.forEach((item, index) => {
                        const li = document.createElement('li');
                        li.className = 'team-item';
                        li.innerHTML = `
                            <span class="team-color" style="background-color: ${['#4687e6', '#13c0e6', '#8ce04f', '#FFA500', '#C0C0C0'][index % 5]};"></span>
                            <span class="team-name">${item.vendedor}</span>
                        `;
                        top5OrganizationList.appendChild(li);
                    });
                } else {
                    const li = document.createElement('li');
                    li.className = 'team-item';
                    li.innerHTML = '<span class="team-name">No hay datos disponibles</span>';
                    top5OrganizationList.appendChild(li);
                }
            }

            // Forzar resize después de un pequeño delay
            setTimeout(() => {
                if (top5OrganizationChart) {
                    top5OrganizationChart.resize();
                }
            }, 100);
        }

        // Actualizar Sales by Region Global
        if (data.global && data.global.SalesByRegionGlobal && salesByRegionGlobalChart) {
            const regionData = data.global.SalesByRegionGlobal;
            const paises = regionData.map(item => item.pais);
            const montos = regionData.map(item => item.monto_total_ventas);
            const cantidades = regionData.map(item => item.cantidad_aplicaciones);
            
            const regionGlobalOption = {
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'shadow'
                    },
                    formatter: function(params) {
                        const dataIndex = params[0].dataIndex;
                        const oficinas = regionData[dataIndex].oficinas_involucradas;
                        return `${params[0].name}<br/>
                                Monto: $${params[0].value.toLocaleString()}<br/>
                                Cantidad: ${params[1].value}<br/>
                                Oficinas: ${oficinas}`;
                    }
                },
                legend: {
                    data: ['Monto', 'Cantidad'],
                    top: 0
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '15%',
                    containLabel: true
                },
                xAxis: {
                    type: 'category',
                    data: paises,
                    axisLabel: {
                        rotate: 45,
                        interval: 0
                    }
                },
                yAxis: [
                    {
                        type: 'value',
                        name: 'Monto',
                        axisLabel: {
                            formatter: function(value) {
                                return '$' + (value / 1000) + 'k';
                            }
                        }
                    },
                    {
                        type: 'value',
                        name: 'Cantidad',
                        axisLabel: {
                            formatter: '{value}'
                        }
                    }
                ],
                series: [
                    {
                        name: 'Monto',
                        type: 'bar',
                        data: montos,
                        itemStyle: {
                            color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                                { offset: 0, color: '#4687e6' },
                                { offset: 1, color: '#13c0e6' }
                            ])
                        }
                    },
                    {
                        name: 'Cantidad',
                        type: 'line',
                        yAxisIndex: 1,
                        data: cantidades,
                        itemStyle: {
                            color: '#FFA500'
                        }
                    }
                ]
            };
            
            salesByRegionGlobalChart.setOption(regionGlobalOption);
        }

        if (data.equipo?.getTop10TeamDirectorManager && top10TeamDirectorManagerChart) {
            document.getElementById('top10TeamDirectorManagerSubtitle').textContent = 'Mes actual (del 3 al 2) - Vista Equipo';
            
            // Verificar si hay datos
            let chartData = [];
            if (!data.equipo.getTop10TeamDirectorManager || data.equipo.getTop10TeamDirectorManager.length === 0 || data.equipo.getTop10TeamDirectorManager.every(item => item.value === 0)) {
                chartData.push({
                    value: 1,
                    name: 'Sin ventas este mes',
                    itemStyle: { color: '#4687e6' }
                });
            } else {
                chartData = data.equipo.getTop10TeamDirectorManager.map((item, index) => ({
                    value: item.value,
                    name: item.name,
                    itemStyle: {
                        color: ['#4687e6', '#13c0e6', '#8ce04f', '#FFA500', '#C0C0C0', '#FF6B6B', '#9775FA', '#63E6BE', '#74C0FC', '#B197FC'][index % 10]
                    }
                }));
            }

            const responsiveConfigTeam1 = getResponsiveConfig();

            const teamOption = {
                tooltip: {
                    trigger: 'item',
                    formatter: function(params) {
                        if (params.name === 'Sin ventas este mes') {
                            return 'No hay aplicaciones en el período seleccionado';
                        }
                        return `${params.name}<br/>Cantidad: ${params.value} (${params.percent}%)`;
                    }
                },
                legend: {
                    ...responsiveConfigTeam1.legendConfig,
                    data: chartData.map(item => item.name)
                },
                series: [{
                    name: 'Top 10 Vendedores del Equipo',
                    type: 'pie',
                    radius: responsiveConfigTeam1.pieRadius,
                    center: responsiveConfigTeam1.pieCenter,
                    avoidLabelOverlap: false,
                    itemStyle: {
                        borderRadius: 10,
                        borderColor: '#fff',
                        borderWidth: 2
                    },
                    label: {
                        show: false,
                        position: 'center'
                    },
                    emphasis: {
                        label: {
                            show: true,
                            fontSize: responsiveConfigTeam1.mobile ? '14' : '16',
                            fontWeight: 'bold'
                        }
                    },
                    labelLine: {
                        show: false
                    },
                    data: chartData
                }]
            };
            top10TeamDirectorManagerChart.setOption(teamOption, true);
            top10TeamDirectorManagerChart.resize();
        } else if (data.oficina?.getTop10TeamDirectorManager && top10TeamDirectorManagerChart) {
            document.getElementById('top10TeamDirectorManagerSubtitle').textContent = 'Mes actual (del 3 al 2) - Vista Oficina';
            
            // Verificar si hay datos
            let chartData = [];
            if (!data.oficina.getTop10TeamDirectorManager || data.oficina.getTop10TeamDirectorManager.length === 0 || data.oficina.getTop10TeamDirectorManager.every(item => item.value === 0)) {
                chartData.push({
                    value: 1,
                    name: 'Sin ventas este mes',
                    itemStyle: { color: '#4687e6' }
                });
            } else {
                chartData = data.oficina.getTop10TeamDirectorManager.map((item, index) => ({
                    value: item.value,
                    name: item.name,
                    itemStyle: {
                        color: ['#4687e6', '#13c0e6', '#8ce04f', '#FFA500', '#C0C0C0', '#FF6B6B', '#9775FA', '#63E6BE', '#74C0FC', '#B197FC'][index % 10]
                    }
                }));
            }

            const responsiveConfigTeam2 = getResponsiveConfig();

            const teamOption = {
                tooltip: {
                    trigger: 'item',
                    formatter: function(params) {
                        if (params.name === 'Sin ventas este mes') {
                            return 'No hay aplicaciones en el período seleccionado';
                        }
                        return `${params.name}<br/>Cantidad: ${params.value} (${params.percent}%)`;
                    }
                },
                legend: {
                    ...responsiveConfigTeam2.legendConfig,
                    data: chartData.map(item => item.name)
                },
                series: [{
                    name: 'Top 10 Vendedores del Equipo',
                    type: 'pie',
                    radius: responsiveConfigTeam2.pieRadius,
                    center: responsiveConfigTeam2.pieCenter,
                    avoidLabelOverlap: false,
                    itemStyle: {
                        borderRadius: 10,
                        borderColor: '#fff',
                        borderWidth: 2
                    },
                    label: {
                        show: false,
                        position: 'center'
                    },
                    emphasis: {
                        label: {
                            show: true,
                            fontSize: responsiveConfigTeam2.mobile ? '14' : '16',
                            fontWeight: 'bold'
                        }
                    },
                    labelLine: {
                        show: false
                    },
                    data: chartData
                }]
            };
            top10TeamDirectorManagerChart.setOption(teamOption, true);
            top10TeamDirectorManagerChart.resize();
        }

        // Actualizar Top 10 Mis Oficinas para rol 8 en vista oficina
        if (vista === 'oficina' && rolCo === 8 && data.oficina?.getTop10MisOficinas && top10MisOficinasChart) {
            const top10MisOficinasData = data.oficina.getTop10MisOficinas;
            
            // Verificar si hay datos
            let chartData = [];
            if (!top10MisOficinasData || top10MisOficinasData.length === 0 || top10MisOficinasData.every(item => item.value === 0)) {
                chartData.push({
                    value: 1,
                    name: 'Sin ventas este mes',
                    itemStyle: { color: '#4687e6' }
                });
            } else {
                chartData = top10MisOficinasData.map((item, index) => ({
                    value: item.value,
                    name: item.name,
                    itemStyle: {
                        color: ['#4687e6', '#13c0e6', '#8ce04f', '#FFA500', '#C0C0C0', '#FF6B6B', '#9775FA', '#63E6BE', '#74C0FC', '#B197FC'][index % 10]
                    }
                }));
            }

            const responsiveConfigMisOficinas = getResponsiveConfig();

            const top10MisOficinasOption = {
                tooltip: {
                    trigger: 'item',
                    formatter: function(params) {
                        if (params.name === 'Sin ventas este mes') {
                            return 'No hay aplicaciones en el período seleccionado';
                        }
                        const dataIndex = top10MisOficinasData.findIndex(item => item.name === params.name);
                        const oficina = dataIndex >= 0 ? top10MisOficinasData[dataIndex] : null;
                        if (oficina) {
                            return `<div style="text-align:center; font-weight:bold; margin-bottom:8px; color:${params.color}; font-size:15px;">${oficina.oficina}</div>
                                    <div style="display:flex; justify-content:space-between; margin-bottom:5px;">
                                        <span style="color:rgba(0,0,0,0.7);">Aplicaciones:</span>
                                        <span style="font-weight:bold; color:#333;">${params.value}</span>
                                    </div>
                                    <div style="display:flex; justify-content:space-between; margin-bottom:5px;">
                                        <span style="color:rgba(0,0,0,0.7);">Monto:</span>
                                        <span style="font-weight:bold; color:#333;">$${oficina.monto.toLocaleString()}</span>
                                    </div>
                                    <div style="display:flex; justify-content:space-between;">
                                        <span style="color:rgba(0,0,0,0.7);">Porcentaje:</span> 
                                        <span style="font-weight:bold; color:#333;">${params.percent}%</span>
                                    </div>`;
                        }
                        return `${params.name}<br/>Cantidad: ${params.value} (${params.percent}%)`;
                    }
                },
                // No usar leyenda interna ya que tiene lista lateral
                legend: {
                    show: false
                },
                series: [{
                    name: 'Top 10 Mis Oficinas',
                    type: 'pie',
                    radius: responsiveConfigMisOficinas.pieRadius,
                    center: responsiveConfigMisOficinas.pieCenter,
                    avoidLabelOverlap: false,
                    itemStyle: {
                        borderRadius: 10,
                        borderColor: '#fff',
                        borderWidth: 2
                    },
                    label: {
                        show: false
                    },
                    emphasis: {
                        scale: true,
                        scaleSize: responsiveConfigMisOficinas.mobile ? 5 : 10,
                        itemStyle: {
                            shadowBlur: 15,
                            shadowColor: 'rgba(0, 0, 0, 0.3)',
                            opacity: 0.8
                        }
                    },
                    data: chartData
                }]
            };

            top10MisOficinasChart.setOption(top10MisOficinasOption);

            // Actualizar lista del Top 10 Mis Oficinas
            const top10MisOficinasList = document.getElementById('top10MisOficinasList');
            if (top10MisOficinasList) {
                top10MisOficinasList.innerHTML = '';
                if (top10MisOficinasData && top10MisOficinasData.length > 0) {
                    top10MisOficinasData.forEach((item, index) => {
                        const li = document.createElement('li');
                        li.className = 'team-item';
                        li.innerHTML = `
                            <span class="team-color" style="background-color: ${['#4687e6', '#13c0e6', '#8ce04f', '#FFA500', '#C0C0C0', '#FF6B6B', '#9775FA', '#63E6BE', '#74C0FC', '#B197FC'][index % 10]};"></span>
                            <span class="team-name">${item.oficina}</span>
                        `;
                        top10MisOficinasList.appendChild(li);
                    });
                } else {
                    const li = document.createElement('li');
                    li.className = 'team-item';
                    li.innerHTML = '<span class="team-name">No hay datos disponibles</span>';
                    top10MisOficinasList.appendChild(li);
                }
            }

            // Forzar resize después de un pequeño delay
            setTimeout(() => {
                top10MisOficinasChart.resize();
            }, 100);
        }

        // Actualizar gráfica de ventas por oficina para directores regionales
        if (rolCo === 4 && salesByOfficeChart && dashboardData.salesByOffice) {
            const option = {
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'shadow'
                    },
                    formatter: function(params) {
                        const data = params[0];
                        return `${data.name}<br/>
                                Ventas: $${data.value.toLocaleString()}<br/>
                                Órdenes: ${data.data.total_orders}`;
                    }
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '3%',
                    containLabel: true
                },
                xAxis: {
                    type: 'category',
                    data: dashboardData.salesByOffice.map(item => item.name),
                    axisLabel: {
                        interval: 0,
                        rotate: 30
                    }
                },
                yAxis: {
                    type: 'value',
                    name: 'Ventas ($)',
                    axisLabel: {
                        formatter: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                },
                series: [{
                    name: 'Ventas por Oficina',
                    type: 'bar',
                    data: dashboardData.salesByOffice.map(item => ({
                        value: item.value,
                        total_orders: item.total_orders
                    })),
                    itemStyle: {
                        color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                            { offset: 0, color: '#13c0e6' },
                            { offset: 1, color: '#4687e6' }
                        ])
                    },
                    emphasis: {
                        itemStyle: {
                            color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                                { offset: 0, color: '#10a5c6' },
                                { offset: 1, color: '#3472c9' }
                            ])
                        }
                    }
                }]
            };
            salesByOfficeChart.setOption(option);
        }

        // Inicializar gráfica de torta para Top 10 Equipo
        const top10EquipoChart = echarts.init(document.getElementById('top10EquipoChart'));
        const top10EquipoOption = {
            tooltip: {
                trigger: 'item',
                formatter: function(params) {
                    if (params.name === 'Sin órdenes este mes') {
                        return 'No hay órdenes en el período seleccionado';
                    }
                    return `${params.name}<br/>Cantidad: ${params.value} (${params.percent}%)`;
                }
            },
            legend: {
                type: 'scroll',
                orient: 'vertical', 
                right: 10,
                top: 20,
                bottom: 20,
                data: ['Sin órdenes este mes'],
                textStyle: {
                    fontSize: 12
                }
            },
            series: [{
                name: 'Top 10 Equipo',
                type: 'pie',
                radius: ['40%', '70%'],
                center: ['40%', '50%'],
                avoidLabelOverlap: false,
                itemStyle: {
                    borderRadius: 10,
                    borderColor: '#fff',
                    borderWidth: 2
                },
                label: {
                    show: false,
                    position: 'center'
                },
                emphasis: {
                    label: {
                        show: true,
                        fontSize: '16',
                        fontWeight: 'bold'
                    }
                },
                labelLine: {
                    show: false
                },
                data: [{
                    value: 1,
                    name: 'Sin órdenes este mes',
                    itemStyle: {
                        color: '#4687e6'
                    }
                }]
            }]
        };
        
        top10EquipoChart.setOption(top10EquipoOption);

        // Actualizar Top 10 Equipo
        if (data.personal && data.personal.getTop10Equipo) {
            // Verificar si hay datos
            let top10Data = [];
            if (!data.personal.getTop10Equipo || data.personal.getTop10Equipo.length === 0 || data.personal.getTop10Equipo.every(item => item.monto_total === 0)) {
                top10Data = [{
                    value: 1,
                    name: 'Sin órdenes este mes',
                    itemStyle: {
                        color: '#4687e6'
                    }
                }];
            } else {
                top10Data = data.personal.getTop10Equipo.map((item, index) => {
                    return {
                        value: item.monto_total,
                        name: item.nombre_usuario + ' ($' + item.monto_total + ')',
                        itemStyle: {
                            color: ['#4687e6', '#13c0e6', '#8ce04f', '#FFA500', '#C0C0C0', '#FF6B6B', '#9775FA', '#63E6BE', '#74C0FC', '#B197FC'][index % 10]
                        }
                    };
                });
            }
            
            const responsiveConfigEquipo = getResponsiveConfig();
            
            const top10EquipoUpdatedOption = {
                tooltip: {
                    trigger: 'item',
                    formatter: function(params) {
                        if (params.name === 'Sin órdenes este mes') {
                            return 'No hay órdenes en el período seleccionado';
                        }
                        return `${params.name}<br/>Cantidad: ${params.value} (${params.percent}%)`;
                    }
                },
                legend: {
                    ...responsiveConfigEquipo.legendConfig,
                    data: top10Data.map(item => item.name)
                },
                series: [{
                    name: 'Top 10 Equipo',
                    type: 'pie',
                    radius: responsiveConfigEquipo.pieRadius,
                    center: responsiveConfigEquipo.pieCenter,
                    avoidLabelOverlap: false,
                    itemStyle: {
                        borderRadius: 10,
                        borderColor: '#fff',
                        borderWidth: 2
                    },
                    label: {
                        show: false,
                        position: 'center'
                    },
                    emphasis: {
                        label: {
                            show: true,
                            fontSize: responsiveConfigEquipo.mobile ? '14' : '16',
                            fontWeight: 'bold'
                        }
                    },
                    labelLine: {
                        show: false
                    },
                    data: top10Data
                }]
            };
            
            top10EquipoChart.setOption(top10EquipoUpdatedOption);
            
            // Forzar resize después de un pequeño delay
            setTimeout(() => {
                top10EquipoChart.resize();
            }, 100);
        }
    }

    // Event listeners para los botones de vista
    const btnPersonal = document.getElementById('btn-personal');
    const btnEquipo = document.getElementById('btn-equipo');
    const btnOficina = document.getElementById('btn-oficina');
    const btnGlobal = document.getElementById('btn-global');
    
    if (btnPersonal && btnEquipo) {
        // Establecer el botón activo según la vista por defecto
        if (currentView === 'equipo') {
            btnEquipo.classList.add('active');
            btnPersonal.classList.remove('active');
            if (btnOficina) btnOficina.classList.remove('active');
            if (btnGlobal) btnGlobal.classList.remove('active');
        } else if (currentView === 'oficina') {
            if (btnOficina) btnOficina.classList.add('active');
            btnPersonal.classList.remove('active');
            btnEquipo.classList.remove('active');
            if (btnGlobal) btnGlobal.classList.remove('active');
        } else if (currentView === 'global') {
            if (btnGlobal) btnGlobal.classList.add('active');
            btnPersonal.classList.remove('active');
            btnEquipo.classList.remove('active');
            if (btnOficina) btnOficina.classList.remove('active');
        } else {
            btnPersonal.classList.add('active');
            btnEquipo.classList.remove('active');
            if (btnOficina) btnOficina.classList.remove('active');
            if (btnGlobal) btnGlobal.classList.remove('active');
        }
        
        btnPersonal.addEventListener('click', function() {
            if (currentView !== 'personal') {
                currentView = 'personal';
                window.currentView = currentView;
                btnPersonal.classList.add('active');
                btnEquipo.classList.remove('active');
                if (btnOficina) btnOficina.classList.remove('active');
                if (btnGlobal) btnGlobal.classList.remove('active');
                loadDashboardData('personal');
            }
        });
        
        btnEquipo.addEventListener('click', function() {
            if (currentView !== 'equipo') {
                currentView = 'equipo';
                window.currentView = currentView;
                btnEquipo.classList.add('active');
                btnPersonal.classList.remove('active');
                if (btnOficina) btnOficina.classList.remove('active');
                if (btnGlobal) btnGlobal.classList.remove('active');
                loadDashboardData('equipo');
            }
        });

        if (btnOficina) {
            btnOficina.addEventListener('click', function() {
                if (currentView !== 'oficina') {
                    currentView = 'oficina';
                    window.currentView = currentView;
                    btnOficina.classList.add('active');
                    btnPersonal.classList.remove('active');
                    btnEquipo.classList.remove('active');
                    if (btnGlobal) btnGlobal.classList.remove('active');
                    loadDashboardData('oficina');
                }
            });
        }

        if (btnGlobal) {
            btnGlobal.addEventListener('click', function() {
                if (currentView !== 'global') {
                    currentView = 'global';
                    window.currentView = currentView;
                    btnGlobal.classList.add('active');
                    btnPersonal.classList.remove('active');
                    btnEquipo.classList.remove('active');
                    if (btnOficina) btnOficina.classList.remove('active');
                    loadDashboardData('global');
                }
            });
        }
    }

    // Función para cambiar período (anual/90dias)
    window.cambiarPeriodo = function(nuevoPeriodo) {
        if (currentPeriodo !== nuevoPeriodo) {
            currentPeriodo = nuevoPeriodo;
            
            // Actualizar botones
            const btnAnual = document.getElementById('btn-anual');
            const btn90dias = document.getElementById('btn-90dias');
            
            if (btnAnual && btn90dias) {
                btnAnual.classList.toggle('active', nuevoPeriodo === 'anual');
                btn90dias.classList.toggle('active', nuevoPeriodo === '90dias');
            }
            
            // Recargar datos con el nuevo período
            loadDashboardData(currentView, nuevoPeriodo, currentPeriodoSalesVolumeByRoles, currentPeriodoOrdersByStatusGlobal);
        }
    };

    // Función para cambiar período específicamente para Sales Volume by Roles
    window.cambiarPeriodoSalesVolumeByRoles = function(nuevoPeriodo) {
        if (currentPeriodoSalesVolumeByRoles !== nuevoPeriodo) {
            currentPeriodoSalesVolumeByRoles = nuevoPeriodo;
            
            // Actualizar botones
            const btnAnual = document.getElementById('btn-salesVolumeByRoles-anual');
            const btn90dias = document.getElementById('btn-salesVolumeByRoles-90dias');
            
            if (btnAnual && btn90dias) {
                btnAnual.classList.toggle('active', nuevoPeriodo === 'anual');
                btn90dias.classList.toggle('active', nuevoPeriodo === '90dias');
            }
            
            // Recargar datos con el nuevo período para Sales Volume by Roles
            loadDashboardData(currentView, currentPeriodo, nuevoPeriodo, currentPeriodoOrdersByStatusGlobal);
        }
    };

    // Función para cambiar período específicamente para Orders by Status en vista global
    window.cambiarPeriodoOrdersByStatusGlobal = function(nuevoPeriodo) {
        if (currentPeriodoOrdersByStatusGlobal !== nuevoPeriodo) {
            currentPeriodoOrdersByStatusGlobal = nuevoPeriodo;
            
            // Actualizar botones
            const btnAnual = document.getElementById('btn-ordersByStatus-global-anual');
            const btn90dias = document.getElementById('btn-ordersByStatus-global-90dias');
            
            if (btnAnual && btn90dias) {
                btnAnual.classList.toggle('active', nuevoPeriodo === 'anual');
                btn90dias.classList.toggle('active', nuevoPeriodo === '90dias');
            }
            
            // Recargar datos con el nuevo período para Orders by Status Global
            loadDashboardData(currentView, currentPeriodo, currentPeriodoSalesVolumeByRoles, nuevoPeriodo);
        }
    };

    // Variable global para Top 5 Organization Salespeople
    let currentPeriodoTop5OrganizationSalespeople = 'anual';

    // Función para cambiar período específicamente para Top 5 Organization Salespeople
    window.cambiarPeriodoTop5OrganizationSalespeople = function(nuevoPeriodo) {
        if (currentPeriodoTop5OrganizationSalespeople !== nuevoPeriodo) {
            currentPeriodoTop5OrganizationSalespeople = nuevoPeriodo;
            
            // Actualizar botones
            const btnAnual = document.getElementById('btn-top5OrganizationSalespeople-anual');
            const btn90dias = document.getElementById('btn-top5OrganizationSalespeople-90dias');
            
            if (btnAnual && btn90dias) {
                btnAnual.classList.toggle('active', nuevoPeriodo === 'anual');
                btn90dias.classList.toggle('active', nuevoPeriodo === '90dias');
            }
            
            // Recargar datos con el nuevo período para Top 5 Organization Salespeople
            loadDashboardData(currentView, currentPeriodo, currentPeriodoSalesVolumeByRoles, currentPeriodoOrdersByStatusGlobal, nuevoPeriodo);
        }
    };

    // Cargar datos iniciales con la vista por defecto
    loadDashboardData(currentView);

    // Forzar un resize inicial de todas las gráficas después de que todo esté cargado
    setTimeout(() => {
        window.dispatchEvent(new Event('resize'));
    }, 500);

    // Manejar el redimensionamiento
    let resizeTimeout;
    let lastIsMobile = isMobile();
    
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            // Detectar si cambió de mobile a desktop o viceversa
            const currentIsMobile = isMobile();
            
            if (currentIsMobile !== lastIsMobile) {
                // Si cambió el tipo de dispositivo, recargar todas las gráficas con nueva configuración
                loadDashboardData(currentView, currentPeriodo, currentPeriodoSalesVolumeByRoles, currentPeriodoOrdersByStatusGlobal);
                lastIsMobile = currentIsMobile;
            } else {
                // Solo hacer resize normal
                if (myChart2) {
                    myChart2.resize();
                }
                if (ordersByStatusChart) {
                    ordersByStatusChart.resize();
                }
                if (volumenVentasChart) {
                    volumenVentasChart.resize();
                }
                if (revenueVsGoalsChart) {
                    revenueVsGoalsChart.resize();
                }
                if (salesTrendChart) {
                    salesTrendChart.resize();
                }
                if (dailySalesChart) {
                    dailySalesChart.resize();
                }
                if (salesByRegionChart) {
                    salesByRegionChart.resize();
                }
                if (salesVolumeByRolesChart) {
                    salesVolumeByRolesChart.resize();
                }
                if (salesVolumeByOfficesChart) {
                    salesVolumeByOfficesChart.resize();
                }
                if (top5OrganizationChart) {
                    top5OrganizationChart.resize();
                }
                if (salesByRegionGlobalChart) {
                    salesByRegionGlobalChart.resize();
                }
                if (top10TeamDirectorManagerChart) {
                    top10TeamDirectorManagerChart.resize();
                }
                if (top10MisOficinasChart) {
                    top10MisOficinasChart.resize();
                }
                if (salesByOfficeChart) salesByOfficeChart.resize();
                if (top10GlobalChart) top10GlobalChart.resize();
            }
        }, 250);
    });
    
    // Funcionalidad para mostrar más eventos
    const btnMostrarMas = document.getElementById('mostrarMasEventos');
    if (btnMostrarMas) {
        let eventosMostrados = 8;
        const todosLosEventos = @json($eventos2 ?? []);
        
        btnMostrarMas.addEventListener('click', function() {
            const eventosContainer = document.querySelector('.row.g-4');
            const eventosAMostrar = todosLosEventos.slice(eventosMostrados, eventosMostrados + 8);
            
            if (eventosAMostrar.length > 0) {
                eventosAMostrar.forEach(evento => {
                    const fechaFormateada = new Date(evento.fe_registro).toLocaleDateString('es', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    }) + ' | ' + new Date(evento.fe_registro).toLocaleTimeString('es', {
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    
                    const tiempoRelativo = moment(evento.fe_registro).fromNow();
                    
                    // Determinar si tiene adjuntos para mostrar la imagen correcta
                    let imageHTML = '';
                    if (evento.adjuntos && evento.adjuntos.length > 0) {
                        imageHTML = `<img src="${evento.adjuntos[0].tx_url_adj}" class="event-image" alt="${evento.tx_titulo}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="event-image-fallback" style="display: none;">
                            <img src="/favicon10.png" alt="Logo">
                        </div>`;
                    } else {
                        imageHTML = `<div class="event-image-fallback">
                            <img src="/favicon10.png" alt="Logo">
                        </div>`;
                    }
                    
                    const eventoHTML = `
                    <div class="col-12 col-md-6 fade-in evento-card">
                        <div class="event-card">
                            <div class="event-header">
                                <div>
                                    <h5 class="event-title">${evento.tx_titulo}</h5>
                                    <span class="event-date">${fechaFormateada}</span>
                                </div>
                            </div>
                            ${imageHTML}
                            <div class="event-content">
                                <p>${evento.tx_descripcion}</p>
                            </div>
                            <div class="event-footer">
                                <a href="/event" class="event-button">IR A EVENTOS</a>
                                <span class="event-time">${tiempoRelativo}</span>
                            </div>
                        </div>
                    </div>`;
                    
                    // Insertar antes del botón "Mostrar más"
                    btnMostrarMas.parentElement.insertAdjacentHTML('beforebegin', eventoHTML);
                });
                
                eventosMostrados += eventosAMostrar.length;
                
                // Ocultar el botón si ya no hay más eventos para mostrar
                if (eventosMostrados >= todosLosEventos.length) {
                    btnMostrarMas.parentElement.style.display = 'none';
                }
            }
        });
    }

    // Función para inicializar la gráfica Top 10 Team Vendors integrada en updateDashboard

    // Gráfica de Top 10 Vendedores de la Oficina
    function initTop10OfficeSalespeopleChart(data) {
        console.log('Inicializando gráfica Top 10 Vendedores de la Oficina:', data);
        
        const chartDom = document.getElementById('top10OfficeSalespeopleChart');
        if (!chartDom) {
            console.log('No se encontró el elemento del DOM');
            return;
        }
        
        const chart = echarts.init(chartDom);
        
        // Verificar si hay datos
        if (!data || data.length === 0) {
            const option = {
                tooltip: {
                    trigger: 'item',
                    formatter: 'Sin datos hasta el momento'
                },
                series: [{
                    name: 'Sin datos',
                    type: 'pie',
                    radius: ['40%', '70%'],
                    center: ['50%', '50%'],
                    avoidLabelOverlap: false,
                    itemStyle: {
                        color: '#4687e6'
                    },
                    label: {
                        show: true,
                        position: 'center',
                        formatter: 'Sin datos hasta el momento',
                        fontSize: 16,
                        fontWeight: 'bold'
                    },
                    data: [{
                        value: 1,
                        name: 'Sin datos hasta el momento'
                    }]
                }]
            };
            chart.setOption(option);
            return;
        }
        
        const option = {
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'shadow'
                }
            },
            grid: {
                left: '15%',
                right: '5%',
                bottom: '5%',
                top: '5%',
                containLabel: true
            },
            xAxis: {
                type: 'value',
                axisLabel: {
                    formatter: '{value} ventas',
                    fontSize: 12
                }
            },
            yAxis: {
                type: 'category',
                data: data.map(item => item.name),
                axisLabel: {
                    interval: 0,
                    rotate: 30,
                    fontSize: 12,
                    width: 200,
                    overflow: 'break'
                }
            },
            series: [{
                name: 'Ventas',
                type: 'bar',
                data: data.map(item => item.value),
                itemStyle: {
                    color: function(params) {
                        const colorList = [
                            '#5470c6', '#91cc75', '#fac858', '#ee6666',
                            '#73c0de', '#3ba272', '#fc8452', '#9a60b4',
                            '#ea7ccc', '#48b3bd'
                        ];
                        return colorList[params.dataIndex % colorList.length];
                    }
                },
                label: {
                    show: true,
                    position: 'right',
                    formatter: '{c} ventas',
                    fontSize: 12
                },
                barWidth: '60%'
            }]
        };

        chart.setOption(option);
        
        // Asegurarse de que la gráfica se redimensione correctamente
        window.addEventListener('resize', function() {
            chart.resize();
        });
    }

    // Función para actualizar todas las gráficas
    function updateCharts(data) {
        console.log('Actualizando gráficas con datos:', data);
        
        // ... existing chart updates ...
        
        // Actualizar la gráfica de Top 10 Vendedores de la Oficina
        if (data.top10OfficeSalespeople) {
            initTop10OfficeSalespeopleChart(data.top10OfficeSalespeople);
        }
    }

    // Cargar datos iniciales
    $.get('/dashboard/data', function(data) {
        console.log('Datos recibidos del servidor:', data);
        dashboardData = data;
        updateCharts(data);
    });

    function cambiarVista(nuevaVista) {
        vista = nuevaVista;
        
        // Actualizar botones
        $('.vista-btn').removeClass('active');
        $(`#vista-${nuevaVista}`).addClass('active');
        
        // Actualizar contenedores
        $('.vista-container').hide();
        $(`#${nuevaVista}-container`).show();
        
        // Actualizar gráficas según la vista
        if (nuevaVista === 'oficina') {
            // Resize de la gráfica de Top 10 Vendedores de la Oficina
            const chartElement = document.getElementById('top10OfficeSalespeopleChart');
            if (chartElement) {
                const chart = echarts.getInstanceByDom(chartElement);
                if (chart) {
                    setTimeout(() => {
                        chart.resize();
                    }, 100);
                }
            }
        }
        
        // Actualizar datos
        $.get('/dashboard/data', { vista: nuevaVista }, function(data) {
            dashboardData = data;
            updateCharts(data);
        });
    }

});
</script>
@endpush