@props(['title', 'icon' => 'bookmark', 'has_team' => true])

<div class="container-1">
    <div class="col-12 flex-menu">
        <div class="left-section">
            <div class="title-container" data-icon="{{ $icon }}">
                {{ $title }}
            </div>
        </div>
        @if(request()->routeIs('dashboard'))
        <div class="right-section">
            <div class="dashboard-controls">
                <span class="role-tag">Dashboard del {{ session('rol_userlogin', 'Usuario') }}</span>
                <div class="view-buttons">
                    <button class="view-btn active" id="btn-personal" data-view="personal" title="Vista Personal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </button>
                    @php
                        // Verificar si debe mostrar el botón de equipo
                        $showTeamButton = true;
                        
                        // Para roles específicos (Analista, Analista Sr., Estudiante), verificar si tienen equipo
                        if (in_array(session('rol_userlogin_co'), [1, 2, 11])) {
                            // Para estos roles, solo mostrar si $has_team está definido y es true
                            $showTeamButton = isset($has_team) && $has_team === true;
                        }
                        

                    @endphp
                    @if($showTeamButton)
                    <button class="view-btn" id="btn-equipo" data-view="equipo" title="Vista de Equipo">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </button>
                    @endif
                    @if(in_array(session('rol_userlogin_co'), [3, 4, 5, 6, 7, 8]))
                    <button class="view-btn" id="btn-oficina" data-view="oficina" title="Vista de Oficina">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 3h18v18H3z"></path>
                            <path d="M3 9h18"></path>
                            <path d="M9 21V9"></path>
                            <path d="M15 21V9"></path>
                            <path d="M3 15h18"></path>
                        </svg>
                    </button>
                    @endif
                    @if(in_array(session('rol_userlogin_co'), [9, 10]))
                    <button class="view-btn" id="btn-global" data-view="global" title="Vista Global">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="2" y1="12" x2="22" y2="12"></line>
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                        </svg>
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @endif
        {{ $slot }}
    </div>
</div>

<style>
    .container-1 {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
        box-shadow: var(--shadow-card);
        padding: 2rem;
        position: relative;
        overflow: hidden;
        border-radius: var(--radius-lg);
        transition: var(--transition-normal);
        z-index: 1;
        width: 100%;
        margin: 0 auto 0.2rem auto;
        min-height: 120px;
    }

    .container-1:hover {
        box-shadow: 0 15px 35px rgba(19, 192, 230, 0.35);
        transform: translateY(-2px);
    }

    .container-1::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, var(--color-primary), transparent);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.5s ease;
    }

    .container-1:hover::after {
        transform: scaleX(1);
    }

    .flex-menu {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        height: 100%;
        padding: 0;
    }
    
    .left-section {
        display: flex;
        align-items: center;
        gap: 2rem;
    }
    
    .right-section {
        display: flex;
        align-items: center;
        justify-content: flex-end;
    }
    
    .dashboard-controls {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .role-tag {
        background-color: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 1.1rem;
        font-weight: 500;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    .view-buttons {
        display: flex;
        gap: 0.5rem;
    }
    
    .view-btn {
        background-color: transparent;
        border: 2px solid white;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .view-btn:hover {
        background-color: rgba(255, 255, 255, 0.1);
        transform: translateY(-2px);
    }
    
    .view-btn.active {
        background-color: rgba(255, 255, 255, 0.2);
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.3);
    }
    
    .view-btn svg {
        width: 20px;
        height: 20px;
        /* Estilos específicos para iOS/iPad */
        -webkit-transform: scale(1);
        transform: scale(1);
        -webkit-transform-origin: center;
        transform-origin: center;
        /* Forzar el tamaño en Safari iOS */
        min-width: 20px;
        min-height: 20px;
        max-width: 20px;
        max-height: 20px;
        /* Prevenir escalado automático en iOS */
        -webkit-appearance: none;
        appearance: none;
    }
    
    /* Estilos específicos para dispositivos iOS/iPad */
    @supports (-webkit-touch-callout: none) {
        .view-btn svg {
            width: 20px !important;
            height: 20px !important;
            min-width: 20px !important;
            min-height: 20px !important;
            max-width: 20px !important;
            max-height: 20px !important;
            -webkit-transform: scale(1) !important;
            transform: scale(1) !important;
        }
        
        .view-btn {
            /* Asegurar que el botón mantenga su tamaño en iOS */
            -webkit-transform: translateZ(0);
            transform: translateZ(0);
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
        }
    }
    
    /* Media query específica para iPad */
    @media only screen 
    and (min-device-width: 768px) 
    and (max-device-width: 1024px) 
    and (-webkit-min-device-pixel-ratio: 1) {
        .view-btn svg {
            width: 20px !important;
            height: 20px !important;
            min-width: 20px !important;
            min-height: 20px !important;
            max-width: 20px !important;
            max-height: 20px !important;
        }
        
        .view-btn {
            width: 40px !important;
            height: 40px !important;
            min-width: 40px !important;
            min-height: 40px !important;
        }
    }
    
    .title-container {
        font-size: 1.8rem;
        font-weight: 600;
        margin: 0;
        color: white;
        letter-spacing: 0.5px;
        position: relative;
        display: flex;
        align-items: center;
    }

    .title-container::before {
        content: '';
        display: inline-block;
        width: 2rem;
        height: 2rem;
        margin-right: 15px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M17 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z'%3E%3C/path%3E%3C/svg%3E");
        background-size: contain;
        background-repeat: no-repeat;
    }

    .title-container[data-icon="bookmark"]::before {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M17 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z'%3E%3C/path%3E%3C/svg%3E");
    }

    .title-container[data-icon="calendar"]::before {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect x='3' y='4' width='18' height='18' rx='2' ry='2'/%3E%3Cline x1='16' y1='2' x2='16' y2='6'/%3E%3Cline x1='8' y1='2' x2='8' y2='6'/%3E%3Cline x1='3' y1='10' x2='21' y2='10'/%3E%3C/svg%3E");
    }

    .title-container[data-icon="shopping-bag"]::before {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z'/%3E%3Cline x1='3' y1='6' x2='21' y2='6'/%3E%3Cpath d='M16 10a4 4 0 0 1-8 0'/%3E%3C/svg%3E");
    }

    .title-container[data-icon="user"]::before {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2'/%3E%3Ccircle cx='12' cy='7' r='4'/%3E%3C/svg%3E");
    }

    .title-container[data-icon="book"]::before {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M4 19.5A2.5 2.5 0 0 1 6.5 17H20'/%3E%3Cpath d='M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z'/%3E%3C/svg%3E");
    }

    .title-container[data-icon="home"]::before {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z'/%3E%3Cpolyline points='9 22 9 12 15 12 15 22'/%3E%3C/svg%3E");
    }

    .title-container[data-icon="briefcase"]::before {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect x='2' y='7' width='20' height='14' rx='2' ry='2'/%3E%3Cpath d='M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16'/%3E%3C/svg%3E");
    }

    .title-container[data-icon="bar-chart"]::before {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cline x1='12' y1='20' x2='12' y2='10'%3E%3C/line%3E%3Cline x1='18' y1='20' x2='18' y2='4'%3E%3C/line%3E%3Cline x1='6' y1='20' x2='6' y2='16'%3E%3C/line%3E%3C/svg%3E");
    }

    .title-container[data-icon="users"]::before {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2'%3E%3C/path%3E%3Ccircle cx='9' cy='7' r='4'%3E%3C/circle%3E%3Cpath d='M23 21v-2a4 4 0 0 0-3-3.87'%3E%3C/path%3E%3Cpath d='M16 3.13a4 4 0 0 1 0 7.75'%3E%3C/path%3E%3C/svg%3E");
    }

    .title-container[data-icon="book-open"]::before {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z'%3E%3C/path%3E%3Cpath d='M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z'%3E%3C/path%3E%3C/svg%3E");
    }

    .title-container[data-icon="help-circle"]::before {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='12' cy='12' r='10'%3E%3C/circle%3E%3Cpath d='M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3'%3E%3C/path%3E%3Cline x1='12' y1='17' x2='12.01' y2='17'%3E%3C/line%3E%3C/svg%3E");
    }

    .title-container[data-icon="video"]::before {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolygon points='23 7 16 12 23 17 23 7'%3E%3C/polygon%3E%3Cpath d='M14 5H3a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2z'%3E%3C/path%3E%3C/svg%3E");
    }

    .title-container[data-icon="file-text"]::before {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z'%3E%3C/path%3E%3Cpolyline points='14 2 14 8 20 8'%3E%3C/polyline%3E%3Cline x1='16' y1='13' x2='8' y2='13'%3E%3C/line%3E%3Cline x1='16' y1='17' x2='8' y2='17'%3E%3C/line%3E%3Cpolyline points='10 9 9 9 8 9'%3E%3C/polyline%3E%3C/svg%3E");
    }
    
    /* Estilos específicos para el componente de búsqueda en el encabezado */
    .search-header-container {
        width: 100%;
        max-width: 450px;
    }
    
    .search-header-container .input-group {
        display: flex;
        flex-wrap: nowrap;
    }
    
    .search-header-container .input-group .form-control {
        background-color: white;
        color: #333;
        border: 1px solid #fff;
        border-radius: 30px 0 0 30px;
    }
    
    .search-header-container .input-group .btn {
        background-color: white;
        color: var(--color-primary);
        border-color: #fff;
        border-radius: 0 30px 30px 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    @media (max-width: 768px) {
        .container-1 {
            padding: 1.5rem;
            min-height: 100px;
        }
        
        .flex-menu {
            flex-direction: column;
            gap: 1rem;
        }
        
        .left-section {
            width: 100%;
            justify-content: center;
        }
        
        .right-section {
            width: 100%;
            justify-content: center;
        }
        
        .dashboard-controls {
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .title-container {
            font-size: 1.5rem;
        }
        
        .search-header-container {
            width: 100%;
        }
        
        /* Estilos específicos para iPad en modo responsive */
        .view-btn {
            width: 40px !important;
            height: 40px !important;
        }
        
        .view-btn svg {
            width: 20px !important;
            height: 20px !important;
            min-width: 20px !important;
            min-height: 20px !important;
            max-width: 20px !important;
            max-height: 20px !important;
        }
    }
    
    @media (max-width: 480px) {
        .container-1 {
            flex-direction: column;
            padding: 1.5rem;
            min-height: 140px;
            justify-content: center;
        }
        
        .flex-menu {
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1rem;
        }
        
        .left-section {
            width: 100%;
            align-items: center;
        }
        
        .right-section {
            width: 100%;
            align-items: center;
        }
        
        .dashboard-controls {
            justify-content: center;
        }
        
        .role-tag {
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
        }
        
        .view-btn {
            width: 35px;
            height: 35px;
        }
        
        .view-btn svg {
            width: 18px !important;
            height: 18px !important;
            min-width: 18px !important;
            min-height: 18px !important;
            max-width: 18px !important;
            max-height: 18px !important;
        }
        
        .title-container {
            font-size: 1.3rem;
            text-align: center;
            margin-bottom: 0;
        }
        
        .title-container::before {
            margin-right: 10px;
            width: 1.5rem;
            height: 1.5rem;
        }
        
        .search-header-container {
            width: 100%;
            margin-top: 0;
        }
        
        .search-header-container .input-group {
            display: flex;
            flex-wrap: nowrap;
            width: 100%;
        }
        
        .search-header-container .input-group .form-control {
            flex: 1;
        }
        
        .search-header-container .input-group .btn {
            width: auto;
            flex-shrink: 0;
        }
    }
</style> 