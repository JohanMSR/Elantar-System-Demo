@extends('layouts.master')

@section('title')
Dashboard Instalador - @lang('translation.business-center')
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
        /* NO usar min-height: 100vh para evitar doble scroll */
    }

    .section-title {
        font-family: "MontserratBold";
        font-size: 1.25rem;
        color: var(--color-text);
        margin-bottom: 1.5rem;
    }

    /* Tarjetas principales usando estilos del dashboard principal */
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

    /* Tarjetas de métricas usando estilos del dashboard principal */
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

    .metric-icon.satisfaction {
        background: linear-gradient(135deg, var(--color-accent), var(--color-accent-dark));
    }

    .metric-icon.time {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    }

    .metric-icon.warning {
        background: linear-gradient(135deg, var(--color-warning), #e68900);
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

    /* Órdenes de instalación usando estilos similares a events */
    .order-card {
        background-color: white;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-card);
        margin-bottom: 1rem;
        overflow: hidden;
        transition: var(--transition-fast);
        border-left: 4px solid var(--color-primary);
    }

    .order-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(19, 192, 230, 0.2);
        border-left-color: var(--color-secondary);
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: linear-gradient(135deg, var(--color-secondary), var(--color-primary));
        color: white;
    }

    .order-id {
        font-family: "MontserratBold";
        font-size: 1rem;
        margin-bottom: 0.25rem;
    }

    .order-status {
        font-size: 0.8rem;
        opacity: 0.9;
        padding: 0.25rem 0.75rem;
        border-radius: var(--radius-sm);
        background-color: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .order-content {
        padding: 1rem;
        font-size: 0.9rem;
        color: var(--color-text);
    }

    .order-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--color-text);
        font-size: 0.9rem;
    }

    .info-item i {
        color: var(--color-primary);
        width: 16px;
    }

    .order-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background-color: #f8f9fa;
        border-top: 1px solid var(--color-border);
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .order-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    /* Botones usando estilos del dashboard principal */
    .download-button {
        background-color: var(--color-primary);
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

    .download-button:hover {
        background-color: var(--color-primary-dark);
        transform: translateY(-2px);
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

    /* Formularios de acción usando estilos del dashboard principal */
    .action-form {
        display: none;
        background-color: #f8f9fa;
        border-radius: var(--radius-md);
        padding: 1rem;
        margin-top: 1rem;
        border: 1px solid var(--color-border);
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: var(--color-text);
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
        width: 100%;
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(19, 192, 230, 0.1);
    }

    .form-control {
        background-color: white;
        border: 1px solid var(--color-border);
        border-radius: var(--radius-md);
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
        color: var(--color-text);
        transition: var(--transition-fast);
        width: 100%;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(19, 192, 230, 0.1);
    }

    .signature-pad {
        border: 2px dashed var(--color-border);
        border-radius: var(--radius-md);
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--color-light-text);
        cursor: pointer;
        transition: var(--transition-fast);
    }

    .signature-pad:hover {
        border-color: var(--color-primary);
        background-color: rgba(19, 192, 230, 0.05);
    }

    .photo-upload {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .photo-slot {
        aspect-ratio: 1;
        border: 2px dashed var(--color-border);
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: var(--transition-fast);
        background-color: #f8f9fa;
    }

    .photo-slot:hover {
        border-color: var(--color-primary);
        background-color: rgba(19, 192, 230, 0.05);
    }

    .photo-preview {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: var(--radius-md);
    }

    /* Gestión de costos usando estilos similares a team-item */
    .expense-item {
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        margin-bottom: 0.75rem;
        border-radius: var(--radius-md);
        transition: var(--transition-fast);
        background-color: #f8f9fa;
    }

    .expense-item:hover {
        background-color: rgba(19, 192, 230, 0.05);
        transform: translateX(5px);
    }

    .expense-info {
        flex: 1;
    }

    .expense-concept {
        font-weight: 600;
        color: var(--color-text);
        margin-bottom: 0.25rem;
    }

    .expense-date {
        font-size: 0.85rem;
        color: var(--color-light-text);
    }

    .expense-amount {
        font-weight: 700;
        font-size: 1.1rem;
        color: var(--color-primary);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .order-info {
            grid-template-columns: 1fr;
        }
        
        .order-actions {
            justify-content: center;
        }

        .photo-upload {
            grid-template-columns: repeat(2, 1fr);
        }

        .order-footer {
            flex-direction: column;
            align-items: stretch;
        }

        .order-actions {
            width: 100%;
            justify-content: space-around;
        }
    }

    /* Animaciones usando las del dashboard principal */
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
</style>
@endpush

@section('content')
<div class="main-content">
    @php
        $installerTitle = 'Dashboard de Instalador';
    @endphp
    <x-page-header :title="$installerTitle" icon="tools" :has_team="false" />

    <!-- Estadísticas principales -->
    <div class="row g-4 mb-4 mt-2">
        <div class="col-12 col-md-3 fade-in">
            <div class="metric-card">
                <div class="metric-icon revenue">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 11H3v10h6V11z"></path>
                        <path d="M21 3h-6v18h6V3z"></path>
                        <path d="M15 7h-6v14h6V7z"></path>
                    </svg>
                </div>
                <div class="metric-value">{{ $installerData['estadisticas']['ordenes_completadas_mes'] }}</div>
                <div class="metric-label">Órdenes Completadas</div>
            </div>
        </div>
        <div class="col-12 col-md-3 fade-in">
            <div class="metric-card">
                <div class="metric-icon warning">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12,6 12,12 16,14"></polyline>
                    </svg>
                </div>
                <div class="metric-value">{{ $installerData['estadisticas']['ordenes_pendientes'] }}</div>
                <div class="metric-label">Órdenes Pendientes</div>
            </div>
        </div>
        <div class="col-12 col-md-3 fade-in">
            <div class="metric-card">
                <div class="metric-icon satisfaction">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                    </svg>
                </div>
                <div class="metric-value">{{ $installerData['estadisticas']['satisfaccion_cliente'] }}</div>
                <div class="metric-label">Notificaciones no leídas</div>
            </div>
        </div>
        <div class="col-12 col-md-3 fade-in">
            <div class="metric-card">
                <div class="metric-icon time">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12,6 12,12 16,14"></polyline>
                    </svg>
                </div>
                <div class="metric-value">{{ $installerData['estadisticas']['tiempo_promedio_instalacion'] }}</div>
                <div class="metric-label">Fecha de próxima instalación</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Órdenes de Instalación Asignadas -->
        <div class="col-12 col-lg-8">
            <div class="dashboard-card fade-in">
                <div class="card-header-custom">
                    <div>
                        <h5 class="card-title">
                            <i class="fas fa-clipboard-list"></i>
                            Órdenes de Instalación Asignadas
                        </h5>
                        <p class="card-subtitle">Gestión de instalaciones programadas</p>
                    </div>
                </div>
                
                @foreach($installerData['ordenes_asignadas'] as $orden)
                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <div class="order-id">{{ $orden['id'] }}</div>
                            <div class="order-status">{{ $orden['estatus'] }}</div>
                        </div>
                        <div>
                            <i class="fas fa-wrench" style="width: 24px; height: 24px;"></i>
                        </div>
                    </div>

                    <div class="order-content">
                        <div class="order-info">
                            <div class="info-item">
                                <i class="fas fa-user"></i>
                                <span>{{ $orden['cliente'] }}</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-phone"></i>
                                <span>{{ $orden['telefono'] }}</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $orden['direccion'] }}</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-calendar"></i>
                                <span>{{ $orden['fecha_programada'] }} - {{ $orden['hora_programada'] }}</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-wrench"></i>
                                <span>{{ $orden['tipo_instalacion'] }}</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-dollar-sign"></i>
                                <span>${{ number_format($orden['costo_estimado'], 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="order-footer">
                        <div class="order-actions">
                            <button class="download-button" onclick="toggleForm('photos-{{ $orden['id'] }}')">
                                <i class="fas fa-camera"></i>
                                Fotos
                            </button>
                            <button class="btn-success" onclick="toggleForm('signature-{{ $orden['id'] }}')">
                                <i class="fas fa-signature"></i>
                                Firma Cliente
                            </button>
                            <button class="btn-warning" onclick="toggleForm('expenses-{{ $orden['id'] }}')">
                                <i class="fas fa-receipt"></i>
                                Gastos
                            </button>
                            @if($orden['estatus'] !== 'Completada')
                            <button class="btn-success" onclick="completeOrder('{{ $orden['id'] }}')">
                                <i class="fas fa-check"></i>
                                Completar
                            </button>
                            @endif
                        </div>
                    </div>

                    <!-- Formulario de Fotos -->
                    <div id="photos-{{ $orden['id'] }}" class="action-form">
                        <h6>Fotos de la Instalación</h6>
                        <div class="photo-upload">
                            <div class="photo-slot" onclick="uploadPhoto('before-{{ $orden['id'] }}')">
                                <div class="text-center">
                                    <i class="fas fa-camera" style="font-size: 2rem; color: var(--color-light-text);"></i>
                                    <p class="mb-0 mt-2">Foto Antes</p>
                                </div>
                            </div>
                            <div class="photo-slot" onclick="uploadPhoto('during-{{ $orden['id'] }}')">
                                <div class="text-center">
                                    <i class="fas fa-camera" style="font-size: 2rem; color: var(--color-light-text);"></i>
                                    <p class="mb-0 mt-2">Foto Durante</p>
                                </div>
                            </div>
                            <div class="photo-slot" onclick="uploadPhoto('after-{{ $orden['id'] }}')">
                                <div class="text-center">
                                    <i class="fas fa-camera" style="font-size: 2rem; color: var(--color-light-text);"></i>
                                    <p class="mb-0 mt-2">Foto Después</p>
                                </div>
                            </div>
                        </div>
                        <input type="file" id="photo-input" style="display: none;" accept="image/*" onchange="handlePhotoUpload(event)">
                    </div>

                    <!-- Formulario de Firma -->
                    <div id="signature-{{ $orden['id'] }}" class="action-form">
                        <h6>Firma del Cliente</h6>
                        <div class="signature-pad" onclick="openSignature('{{ $orden['id'] }}')">
                            <div class="text-center">
                                <i class="fas fa-signature" style="font-size: 2rem;"></i>
                                <p class="mb-0 mt-2">Haga clic para firmar</p>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <label class="form-label">Nombre del Cliente</label>
                            <input type="text" class="form-control" value="{{ $orden['cliente'] }}" readonly>
                        </div>
                    </div>

                    <!-- Formulario de Gastos -->
                    <div id="expenses-{{ $orden['id'] }}" class="action-form">
                        <h6>Registrar Gastos</h6>
                        <div class="row">
                            <div class="col-md-6">
                                                        <div class="form-group">
                            <label class="form-label">Concepto</label>
                            <select class="filter-select">
                                <option>Transporte</option>
                                <option>Materiales adicionales</option>
                                <option>Herramientas</option>
                                <option>Otros</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Monto ($)</label>
                            <input type="number" class="form-control" step="0.01" placeholder="0.00">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Descripción</label>
                    <textarea class="form-control" rows="3" placeholder="Descripción del gasto..."></textarea>
                </div>
                <button class="download-button" onclick="addExpense('{{ $orden['id'] }}')">
                    <i class="fas fa-plus"></i>
                    Agregar Gasto
                </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Panel Lateral -->
        <div class="col-12 col-lg-4">
            <div class="row g-3">
                <!-- Ingresos Recientes -->
                <div class="col-12">
                    <div class="dashboard-card fade-in">
                        <div class="card-header-custom">
                            <div>
                                <h5 class="card-title">
                                    <i class="fas fa-arrow-up" style="color: var(--color-success);"></i>
                                    Ingresos Recientes
                                </h5>
                                <p class="card-subtitle">Últimos ingresos registrados</p>
                            </div>
                        </div>
                        
                        <div class="expense-item">
                            <div class="expense-info">
                                <div class="expense-concept">Instalación #ORD-001</div>
                                <div class="expense-date">15/12/2024 - Cliente: Juan Pérez</div>
                            </div>
                            <div class="expense-amount" style="color: var(--color-success);">$1,250.00</div>
                        </div>
                        
                        <div class="expense-item">
                            <div class="expense-info">
                                <div class="expense-concept">Instalación #ORD-002</div>
                                <div class="expense-date">14/12/2024 - Cliente: María García</div>
                            </div>
                            <div class="expense-amount" style="color: var(--color-success);">$980.00</div>
                        </div>
                        
                        <div class="expense-item">
                            <div class="expense-info">
                                <div class="expense-concept">Instalación #ORD-003</div>
                                <div class="expense-date">13/12/2024 - Cliente: Carlos López</div>
                            </div>
                            <div class="expense-amount" style="color: var(--color-success);">$1,450.00</div>
                        </div>

                        <button class="download-button w-100 mt-3">
                            <i class="fas fa-eye"></i>
                            Ver Todos los Ingresos
                        </button>
                    </div>
                </div>

                <!-- Gastos Recientes -->
                <div class="col-12">
                    <div class="dashboard-card fade-in">
                        <div class="card-header-custom">
                            <div>
                                <h5 class="card-title">
                                    <i class="fas fa-arrow-down" style="color: var(--color-danger);"></i>
                                    Gastos Recientes
                                </h5>
                                <p class="card-subtitle">Últimos gastos registrados</p>
                            </div>
                        </div>
                        
                        <div class="expense-item">
                            <div class="expense-info">
                                <div class="expense-concept">Transporte</div>
                                <div class="expense-date">15/12/2024 - ORD-001</div>
                            </div>
                            <div class="expense-amount" style="color: var(--color-danger);">-$45.00</div>
                        </div>
                        
                        <div class="expense-item">
                            <div class="expense-info">
                                <div class="expense-concept">Materiales adicionales</div>
                                <div class="expense-date">14/12/2024 - ORD-002</div>
                            </div>
                            <div class="expense-amount" style="color: var(--color-danger);">-$120.00</div>
                        </div>
                        
                        <div class="expense-item">
                            <div class="expense-info">
                                <div class="expense-concept">Herramientas</div>
                                <div class="expense-date">13/12/2024 - ORD-003</div>
                            </div>
                            <div class="expense-amount" style="color: var(--color-danger);">-$85.00</div>
                        </div>

                        <button class="download-button w-100 mt-3">
                            <i class="fas fa-eye"></i>
                            Ver Todos los Gastos
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Firma -->
<div class="modal fade" id="signatureModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Firma del Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <canvas id="signatureCanvas" width="600" height="300" style="border: 1px solid #ddd; width: 100%;"></canvas>
                <div class="mt-3">
                    <button class="btn btn-secondary" onclick="clearSignature()">Limpiar</button>
                    <button class="btn btn-primary" onclick="saveSignature()">Guardar Firma</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Variables globales
let currentPhotoSlot = null;
let currentOrderId = null;
let isDrawing = false;
let signatureCanvas = null;
let signatureCtx = null;

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar canvas de firma
    signatureCanvas = document.getElementById('signatureCanvas');
    if (signatureCanvas) {
        signatureCtx = signatureCanvas.getContext('2d');
        setupSignatureCanvas();
    }
});

// Función para mostrar/ocultar formularios
function toggleForm(formId) {
    const form = document.getElementById(formId);
    const isVisible = form.style.display === 'block';
    
    // Ocultar todos los formularios
    document.querySelectorAll('.action-form').forEach(f => f.style.display = 'none');
    
    // Mostrar el formulario seleccionado si no estaba visible
    if (!isVisible) {
        form.style.display = 'block';
        form.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
}

// Función para subir fotos
function uploadPhoto(slotId) {
    currentPhotoSlot = slotId;
    document.getElementById('photo-input').click();
}

function handlePhotoUpload(event) {
    const file = event.target.files[0];
    if (file && currentPhotoSlot) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const slot = document.querySelector(`[onclick="uploadPhoto('${currentPhotoSlot}')"]`);
            if (slot) {
                slot.innerHTML = `<img src="${e.target.result}" class="photo-preview" alt="Foto">`;
                slot.style.border = '2px solid var(--color-success)';
            }
        };
        reader.readAsDataURL(file);
    }
}

// Función para abrir modal de firma
function openSignature(orderId) {
    currentOrderId = orderId;
    const modal = new bootstrap.Modal(document.getElementById('signatureModal'));
    modal.show();
}

// Configurar canvas de firma
function setupSignatureCanvas() {
    signatureCanvas.addEventListener('mousedown', startDrawing);
    signatureCanvas.addEventListener('mousemove', draw);
    signatureCanvas.addEventListener('mouseup', stopDrawing);
    signatureCanvas.addEventListener('mouseout', stopDrawing);
    
    // Touch events para móviles
    signatureCanvas.addEventListener('touchstart', function(e) {
        e.preventDefault();
        const touch = e.touches[0];
        const mouseEvent = new MouseEvent('mousedown', {
            clientX: touch.clientX,
            clientY: touch.clientY
        });
        signatureCanvas.dispatchEvent(mouseEvent);
    });
    
    signatureCanvas.addEventListener('touchmove', function(e) {
        e.preventDefault();
        const touch = e.touches[0];
        const mouseEvent = new MouseEvent('mousemove', {
            clientX: touch.clientX,
            clientY: touch.clientY
        });
        signatureCanvas.dispatchEvent(mouseEvent);
    });
    
    signatureCanvas.addEventListener('touchend', function(e) {
        e.preventDefault();
        const mouseEvent = new MouseEvent('mouseup', {});
        signatureCanvas.dispatchEvent(mouseEvent);
    });
}

function startDrawing(e) {
    isDrawing = true;
    draw(e);
}

function draw(e) {
    if (!isDrawing) return;
    
    const rect = signatureCanvas.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;
    
    signatureCtx.lineWidth = 2;
    signatureCtx.lineCap = 'round';
    signatureCtx.strokeStyle = '#000';
    
    signatureCtx.lineTo(x, y);
    signatureCtx.stroke();
    signatureCtx.beginPath();
    signatureCtx.moveTo(x, y);
}

function stopDrawing() {
    if (isDrawing) {
        signatureCtx.beginPath();
        isDrawing = false;
    }
}

function clearSignature() {
    signatureCtx.clearRect(0, 0, signatureCanvas.width, signatureCanvas.height);
}

function saveSignature() {
    const signatureData = signatureCanvas.toDataURL();
    
    // Mostrar la firma en el formulario
    const signaturePad = document.querySelector(`#signature-${currentOrderId} .signature-pad`);
    if (signaturePad) {
        signaturePad.innerHTML = `
            <img src="${signatureData}" style="max-width: 100%; max-height: 100%; border-radius: var(--radius-md);" alt="Firma del cliente">
        `;
        signaturePad.style.border = '2px solid var(--color-success)';
    }
    
    // Cerrar modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('signatureModal'));
    modal.hide();
    
    // Limpiar canvas
    clearSignature();
    
    // Mostrar mensaje de éxito
    showNotification('Firma guardada exitosamente', 'success');
}

// Función para agregar gastos
function addExpense(orderId) {
    const form = document.querySelector(`#expenses-${orderId}`);
    const concepto = form.querySelector('select').value;
    const monto = form.querySelector('input[type="number"]').value;
    const descripcion = form.querySelector('textarea').value;
    
    if (!monto || monto <= 0) {
        showNotification('Por favor ingrese un monto válido', 'error');
        return;
    }
    
    // Aquí se enviaría la información al servidor
    console.log('Agregando gasto:', { orderId, concepto, monto, descripcion });
    
    // Limpiar formulario
    form.querySelector('input[type="number"]').value = '';
    form.querySelector('textarea').value = '';
    
    showNotification('Gasto registrado exitosamente', 'success');
}

// Función para completar orden
function completeOrder(orderId) {
    if (confirm('¿Está seguro de que desea marcar esta orden como completada?')) {
        // Aquí se enviaría la información al servidor
        console.log('Completando orden:', orderId);
        
        // Actualizar UI
        const orderCard = document.querySelector(`[data-order-id="${orderId}"]`);
        if (orderCard) {
            const statusElement = orderCard.querySelector('.order-status');
            statusElement.textContent = 'Completada';
            statusElement.className = 'order-status status-completada';
            statusElement.style.backgroundColor = 'rgba(82, 196, 26, 0.1)';
            statusElement.style.color = 'var(--color-success)';
            statusElement.style.borderColor = 'var(--color-success)';
        }
        
        showNotification('Orden completada exitosamente', 'success');
    }
}

// Función para mostrar notificaciones
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background-color: ${type === 'success' ? 'var(--color-success)' : type === 'error' ? 'var(--color-danger)' : 'var(--color-primary)'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-card);
        z-index: 9999;
        animation: slideIn 0.3s ease;
    `;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Agregar estilos para animaciones de notificación
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);
</script>
@endpush 