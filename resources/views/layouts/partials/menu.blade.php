{{-- Floating Menu for the application --}}
<style>
    /* Variables de animación */
    :root {
        --transition-curve: cubic-bezier(0.4, 0, 0.2, 1);
        --transition-time: 0.3s;
        --transition-bounce: cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }
    
    /* Floating Menu Styles */
    .floating-menu {
        width: 320px;
        height: calc(100vh - 100px);
        max-height: calc(100vh - 100px);
        position: fixed;
        top: 90px;
        left: 0; /* Always visible */
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(15px);
        box-shadow: 5px 0 30px rgba(0, 0, 0, 0.15);
        transition: all var(--transition-time) var(--transition-curve);
        z-index: 999;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        border-radius: 0 20px 20px 0;
        border: 1px solid rgba(255, 255, 255, 0.2);
        transform: translateX(-100%);
    }
    
    .floating-menu.show {
        transform: translateX(0);
        box-shadow: 10px 0 40px rgba(0, 0, 0, 0.2);
    }
    
    /* Collapsed state - show only icons */
    .floating-menu.collapsed {
        width: 80px;
        transform: translateX(0);
    }
    
    .floating-menu.collapsed .floating-menu-header {
        padding: 1rem 0.5rem;
        justify-content: center;
    }
    
    .floating-menu.collapsed .menu-logo-container {
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .floating-menu.collapsed .floating-menu-logo {
        height: 25px;
        content: url('{{ asset("iconoElantar.png") }}');
    }
    
    .floating-menu.collapsed .menu-logo-text {
        display: none;
    }
    
    .floating-menu.collapsed .menu-close-btn {
        display: none;
    }
    
    .floating-menu.collapsed .floating-menu-text,
    .floating-menu.collapsed .floating-notification-badge,
    .floating-menu.collapsed .floating-user-section {
        display: none;
    }
    
    .floating-menu.collapsed .floating-menu-item {
        padding: 0.75rem;
        justify-content: center;
        border-radius: 8px;
        margin: 0 0.5rem 0.25rem 0.5rem;
    }
    
    .floating-menu.collapsed .floating-menu-icon {
        margin-right: 0;
        width: 24px;
        height: 24px;
    }
    
    .floating-menu.collapsed .floating-submenu {
        display: none;
    }
    
    .floating-menu.collapsed .floating-dropdown-toggle::after {
        display: none;
    }
    
    /* Menu backdrop */
    .floating-menu-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(3px);
        opacity: 0;
        visibility: hidden;
        transition: all var(--transition-time) var(--transition-curve);
        z-index: 998;
    }
    
    .floating-menu-backdrop.show {
        opacity: 1;
        visibility: visible;
    }
    
    /* Menu header */
    .floating-menu-header {
        padding: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid var(--color-border);
        background: linear-gradient(135deg, rgba(0, 34, 112, 0.1), rgba(0, 10, 41, 0.1));
    }
    
    .menu-logo-container {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .floating-menu-logo {
        height: 30px;
        width: auto;
        max-width: none;
        border-radius: 12px;
        object-fit: contain;
    }
    
    .menu-close-btn {
        background: rgba(220, 53, 69, 0.1);
        border: none;
        color: #dc3545;
        width: 35px;
        height: 35px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .menu-close-btn:hover {
        background: rgba(220, 53, 69, 0.2);
        transform: scale(1.1);
    }
    
    /* Menu content */
    .floating-menu-content {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        padding: 1rem 0;
        scrollbar-width: thin;
        scrollbar-color: rgba(0, 34, 112, 0.3) transparent;
    }
    
    .floating-menu-content::-webkit-scrollbar {
        width: 6px;
    }
    
    .floating-menu-content::-webkit-scrollbar-track {
        background: transparent;
        border-radius: 10px;
        margin: 10px 0;
    }
    
    .floating-menu-content::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, rgba(0, 34, 112, 0.4), rgba(0, 10, 41, 0.4));
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
    }
    
    .floating-menu-content::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, rgba(0, 34, 112, 0.6), rgba(0, 10, 41, 0.6));
    }
    
    /* Menu items */
    .floating-menu-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .floating-menu-list li {
        width: 100%;
        margin-bottom: 0.25rem;
    }
    
    .floating-menu-item {
        display: flex;
        align-items: center;
        padding: 0.875rem 1.5rem;
        text-decoration: none;
        color: var(--color-text);
        position: relative;
        transition: all var(--transition-time) var(--transition-curve);
        border-radius: 0;
        white-space: nowrap;
        transform-origin: left center;
    }
    
    .floating-menu-item:hover {
        background: linear-gradient(90deg, rgba(255, 165, 0, 0.1), transparent);
        color: #ff8c00;
        transform: translateX(10px);
        border-left: 4px solid #ff8c00;
    }
    
    .floating-menu-item.active {
        background: linear-gradient(90deg, rgba(0, 10, 41, 0.15), rgba(0, 34, 112, 0.1));
        color: var(--color-secondary);
        font-weight: 600;
        border-left: 4px solid var(--color-secondary);
    }
    
    .floating-menu-icon {
        width: 22px;
        height: 22px;
        margin-right: 15px;
        color: currentColor;
        transition: all var(--transition-time) var(--transition-curve);
        flex-shrink: 0;
    }
    
    .floating-menu-text {
        transition: all var(--transition-time) var(--transition-curve);
        font-weight: 500;
    }
    
    /* Dropdown styles */
    .floating-dropdown-toggle::after {
        margin-left: auto;
        display: block;
        transition: var(--transition-normal);
        transform: rotate(0deg);
    }
    
    .floating-dropdown-toggle.collapsed::after {
        transform: rotate(-90deg);
    }
    
    .floating-submenu {
        list-style: none;
        padding-left: 3rem;
        max-height: 0;
        overflow: hidden;
        transition: all var(--transition-time) var(--transition-curve);
        opacity: 0;
        background: rgba(0, 34, 112, 0.05);
    }
    
    .floating-submenu.show {
        max-height: 500px;
        opacity: 1;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }
    
    .floating-submenu-item {
        padding: 0.5rem 0;
        font-size: 0.9rem;
        color: var(--color-light-text);
        transition: all var(--transition-time) var(--transition-curve);
        display: block;
        text-decoration: none;
        position: relative;
        transform-origin: left center;
    }
    
    .floating-submenu-item:hover {
        color: #ff8c00;
        transform: translateX(8px);
    }
    
    .floating-submenu-item.active {
        color: var(--color-secondary);
        font-weight: 600;
    }
    
    .floating-submenu-item::before {
        content: '';
        position: absolute;
        left: -15px;
        top: 50%;
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: #ff8c00;
        transform: translateY(-50%) scale(0);
        opacity: 0;
        transition: all var(--transition-time) var(--transition-curve);
    }
    
    .floating-submenu-item:hover::before {
        transform: translateY(-50%) scale(1);
        opacity: 0.7;
    }
    
    /* Notification badge */
    .floating-notification-badge {
        position: absolute;
        top: 50%;
        right: 15px;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        border-radius: 10px;
        padding: 0.2rem 0.5rem;
        font-size: 0.7rem;
        font-weight: 600;
        min-width: 1.2rem;
        text-align: center;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
        transform: translateY(-50%);
        transition: all var(--transition-time) var(--transition-curve);
        animation: pulse 2s infinite;
    }
    
    /* User section in floating menu */
    .floating-user-section {
        padding: 1.5rem;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        margin-top: auto;
        background: linear-gradient(135deg, rgba(0, 34, 112, 0.05), rgba(0, 10, 41, 0.05));
        backdrop-filter: blur(5px);
    }
    
    .floating-user-avatar {
        width: 50px;
        height: 50px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        flex-shrink: 0;
        background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
        box-shadow: 0 4px 12px rgba(0, 34, 112, 0.3);
        overflow: hidden;
        transition: all var(--transition-time) var(--transition-curve);
    }
    
    .floating-user-avatar:hover {
        transform: scale(1.05);
    }
    
    .floating-user-info {
        margin-left: 12px;
        overflow: hidden;
        flex: 1;
    }
    
    .floating-user-name {
        font-weight: 600;
        color: var(--color-text);
        margin-bottom: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 0.95rem;
        background: linear-gradient(45deg, var(--color-primary), var(--color-secondary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .floating-user-role {
        font-size: 0.8rem;
        color: var(--color-light-text);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .floating-user-dropdown {
        position: relative;
        margin-left: auto;
    }
    
    .floating-logout-btn {
        background: transparent;
        border: none;
        display: flex;
        align-items: center;
        color: var(--color-light-text);
        transition: var(--transition-normal);
        cursor: pointer;
        padding: 8px;
        border-radius: 8px;
    }
    
    .floating-logout-btn:hover {
        color: #dc3545;
        background: rgba(220, 53, 69, 0.1);
    }
    
    /* Mobile responsive */
    @media (max-width: 768px) {
        .floating-menu {
            width: 100%;
            max-width: 350px;
            left: 0;
            top: 80px;
            height: calc(100vh - 80px);
            border-radius: 0;
            transform: translateX(-100%);
        }
        
        .floating-menu.collapsed {
            width: 70px;
            transform: translateX(0);
        }
    }
    
    /* Prevent body scroll when menu is open */
    body.menu-open {
        overflow: hidden;
    }
</style>

<!-- Floating Menu -->
<div class="floating-menu collapsed" id="floating-menu">
    <!-- Menu Header -->
    <div class="floating-menu-header">
        <div class="menu-logo-container">
            <div style="display: flex; flex-direction: column; align-items: center;">
                <img src="{{ asset('favicon10.png') }}" alt="AQUAFEEL Logo" class="floating-menu-logo">
                <small class="menu-logo-text" style="color: var(--color-light-text); font-size: 0.75rem; margin-top: 4px;">Business Center</small>
            </div>
        </div>
        <button class="menu-close-btn" onclick="toggleFloatingMenu()" aria-label="Close menu">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 18px; height: 18px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    
    <!-- Menu Content -->
    <div class="floating-menu-content">
        <ul class="floating-menu-list">
            <li>
                <a href="{{ route('dashboard') }}" class="floating-menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="floating-menu-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    <span class="floating-menu-text">@lang('translation.home_title')</span>
                </a>
            </li>
            
            <li>
                <a href="{{ route('shop') }}" class="floating-menu-item {{ request()->routeIs('shop') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="floating-menu-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                    <span class="floating-menu-text">@lang('translation.leads_title')</span>
                </a>
            </li>
            
            <li>
                <a href="#floating-projects-submenu" class="floating-menu-item floating-dropdown-toggle {{ request()->routeIs('account*') ? 'active' : '' }}" data-bs-toggle="collapse" aria-expanded="{{ request()->routeIs('account*') ? 'true' : 'false' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="floating-menu-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                    </svg>
                    <span class="floating-menu-text">@lang('translation.projects_title')</span>
                </a>
                <ul class="floating-submenu collapse {{ request()->routeIs('account*') ? 'show' : '' }}" id="floating-projects-submenu">
                    <li>
                        <a href="{{ route('account') }}/teamprojects" class="floating-submenu-item {{ request()->is('*/teamprojects') ? 'active' : '' }}">
                            @lang('translation.projects_submenu_title_team')
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('account') }}/ownprojects" class="floating-submenu-item {{ request()->is('*/ownprojects') ? 'active' : '' }}">
                            @lang('translation.projects_submenu_title_own')
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('account') }}/teamonlyprojects" class="floating-submenu-item {{ request()->is('*/teamonlyprojects') ? 'active' : '' }}">
                            @lang('translation.projects_submenu_title_team_only')
                        </a>
                    </li>
                </ul>
            </li>
            
            <li>
                <a href="#floating-reports-submenu" class="floating-menu-item floating-dropdown-toggle {{ request()->routeIs('report*') ? 'active' : '' }}" data-bs-toggle="collapse" aria-expanded="{{ request()->routeIs('report*') ? 'true' : 'false' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="floating-menu-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                    </svg>
                    <span class="floating-menu-text">@lang('translation.report_title')</span>
                </a>
                <ul class="floating-submenu collapse {{ request()->routeIs('report*') ? 'show' : '' }}" id="floating-reports-submenu">
                    <li>
                        <a href="{{ route('report') }}" class="floating-submenu-item {{ request()->routeIs('report') && !request()->routeIs('report.*') ? 'active' : '' }}">
                            General
                        </a>
                    </li>
                    @if(count(session('misVentas', [])) > 0)
                    <li>
                        <a href="{{ route('report') }}?view=misventas" class="floating-submenu-item {{ request()->routeIs('report') && request()->query('view') == 'misventas' ? 'active' : '' }}" data-bs-toggle="modal" data-bs-target="#modalTable">
                            Mis ventas
                        </a>
                    </li>
                    @else
                    <li>
                        <a href="#" class="floating-submenu-item disabled" style="color: #adb5bd; cursor: not-allowed;">
                            Mis ventas
                        </a>
                    </li>
                    @endif
                    
                    @if(count(session('ventasTeam', [])) > 0)
                    <li>
                        <a href="{{ route('report') }}?view=ventasequipo" class="floating-submenu-item {{ request()->routeIs('report') && request()->query('view') == 'ventasequipo' ? 'active' : '' }}" data-bs-toggle="modal" data-bs-target="#modalTeamTable">
                            Ventas de mi equipo
                        </a>
                    </li>
                    @else
                    <li>
                        <a href="#" class="floating-submenu-item disabled" style="color: #adb5bd; cursor: not-allowed;">
                            Ventas de mi equipo
                        </a>
                    </li>
                    @endif
                    
                    @if(Auth::check() && (Auth::user()->co_tipo_usuario == 3 || session('altoRol', false)))
                    <li>
                        <a href="{{ route('report.application') }}" class="floating-submenu-item {{ request()->routeIs('report.application') ? 'active' : '' }}">
                            Instalaciones
                        </a>
                    </li>
                    @else
                    <li>
                        <a href="#" class="floating-submenu-item disabled" style="color: #adb5bd; cursor: not-allowed;">
                            Instalaciones
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            
            <li>
                <a href="{{ route('report.team') }}" class="floating-menu-item {{ request()->routeIs('report.team') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="floating-menu-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                    <span class="floating-menu-text">@lang('translation.team_title')</span>
                </a>
            </li>
            
            <li>
                <a href="{{ route('ordenes.instalacion') }}" class="floating-menu-item {{ request()->routeIs('ordenes.instalacion') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="floating-menu-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="floating-menu-text">Órdenes de Instalación</span>
                </a>
            </li>
            
            <li>
                <a href="#floating-schemas-submenu" class="floating-menu-item floating-dropdown-toggle {{ request()->routeIs('verifications*') || request()->routeIs('expenses*') ? 'active' : '' }}" data-bs-toggle="collapse" aria-expanded="{{ request()->routeIs('verifications*') || request()->routeIs('expenses*') ? 'true' : 'false' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="floating-menu-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0l4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0l-5.571 3-5.571-3" />
                    </svg>
                    <span class="floating-menu-text">Mantenimiento</span>
                </a>
                <ul class="floating-submenu collapse {{ request()->routeIs('verifications*') || request()->routeIs('expenses*') ? 'show' : '' }}" id="floating-schemas-submenu">
                    <li>
                        <a href="{{ route('verifications.index') }}" class="floating-submenu-item {{ request()->routeIs('verifications*') ? 'active' : '' }}">
                            Verificaciones
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('expenses.index') }}" class="floating-submenu-item {{ request()->routeIs('expenses*') ? 'active' : '' }}">
                            Gastos
                        </a>
                    </li>
                </ul>
            </li>
            
            @if(Auth::check() && Auth::user()->co_tipo_usuario == 3)
            <li>
                <a href="{{ route('dashboard.team') }}" class="floating-menu-item {{ request()->routeIs('dashboard.team') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="floating-menu-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V19.5a2.25 2.25 0 002.25 2.25h.75m0-3H21" />
                    </svg>
                    <span class="floating-menu-text">@lang('translation.gestion_title')</span>
                </a>
            </li>
            @endif
            
            <li>
                <a href="{{ route('university') }}" class="floating-menu-item {{ request()->routeIs('university') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="floating-menu-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                    <span class="floating-menu-text">@lang('translation.business-school')</span>
                </a>
            </li>
            
            <li>
                <a href="https://aquafeelglobalstore.com/es" target="_blank" rel="noopener noreferrer" class="floating-menu-item">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="floating-menu-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                    <span class="floating-menu-text">@lang('translation.store_title')</span>
                </a>
            </li>
            
            <li>
                <a href="{{ route('event') }}" class="floating-menu-item {{ request()->routeIs('event') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="floating-menu-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5v-.008z" />
                    </svg>
                    <span class="floating-menu-text">@lang('translation.event_title')</span>
                </a>
            </li>
            
            <li>
                <a href="#floating-settings-submenu" class="floating-menu-item floating-dropdown-toggle {{ request()->routeIs('showupdatepassword') || request()->routeIs('setting.show') || request()->routeIs('users.index') ? 'active' : '' }}" data-bs-toggle="collapse" aria-expanded="{{ request()->routeIs('showupdatepassword') || request()->routeIs('setting.show') || request()->routeIs('users.index') ? 'true' : 'false' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="floating-menu-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="floating-menu-text">@lang('translation.settings_title')</span>
                </a>
                <ul class="floating-submenu collapse {{ request()->routeIs('showupdatepassword') || request()->routeIs('setting.show') || request()->routeIs('users.index') ? 'show' : '' }}" id="floating-settings-submenu">
                    <li>
                        <a href="{{ route('showupdatepassword') }}" class="floating-submenu-item {{ request()->routeIs('showupdatepassword') ? 'active' : '' }}">
                            Cambio de clave
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('setting.show') }}" class="floating-submenu-item {{ request()->routeIs('setting.show') ? 'active' : '' }}">
                            Ajuste de Perfil
                        </a>
                    </li>
                    @if(Auth::check() && Auth::user()->co_tipo_usuario == 3)
                    <li>
                        <a href="{{ route('users.index') }}" class="floating-submenu-item {{ request()->routeIs('users.index') ? 'active' : '' }}">
                            Administración De Usuarios
                        </a>
                    </li>
                    @endif
                    @if(Auth::check() && Auth::user()->co_tipo_usuario == 3)
                    <li>
                        <a href="{{ route('notifications.create') }}" class="floating-submenu-item {{ request()->routeIs('notifications.create') ? 'active' : '' }}">
                            Crear Notificación
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            
            <li>
                <a href="{{ route('notifications.index') }}" class="floating-menu-item {{ request()->routeIs('notifications.index') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="floating-menu-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                    <span class="floating-menu-text">Notificaciones</span>
                    <span class="floating-notification-badge" id="floating-notification-count" style="display: none">0</span>
                </a>
            </li>
        </ul>
    </div>
    
    <!-- User Section -->
    <div class="floating-user-section">
        @php
        $inicial = '';
        $name = '';
        if(Auth::check()) {
            if(Auth::user()->surname) {
                $inicial = ucwords(Auth::user()->surname[0]) . '.';
            }
            $name = ucwords(Auth::user()->name);
        }
        if (session()->has('rol_userlogin')) {
            $rol = session('rol_userlogin');
        } else {
            $rol = Auth::check() && Auth::user()->co_tipo_usuario == 3 ? "Administrador" : "Usuario";
        }
        @endphp
        
        <div class="floating-user-avatar">
            @if (Auth::user() && Auth::user()->image_path && file_exists(public_path('storage/' . Auth::user()->image_path)))
                <img src="{{ url('storage/'. Auth::user()->image_path) }}" alt="Perfil" style="width: 100%; height: 100%; object-fit: cover;">
            @else
                <span style="color: white; font-weight: 600;">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                </span>
            @endif
        </div>
        
        <div class="floating-user-info">
            <p class="floating-user-name">{{ $name }} {{ $inicial }}</p>
            <span class="floating-user-role">{{ $rol }}</span>
        </div>
        
        <div class="dropdown floating-user-dropdown">
            <button class="floating-logout-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                </svg>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="{{ route('setting.show') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="me-2" style="width: 16px; height: 16px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg> Perfil
                    </a>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="me-2" style="width: 16px; height: 16px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                            </svg> Cerrar sesión
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- Menu Backdrop -->
<div class="floating-menu-backdrop" id="menu-backdrop" onclick="toggleFloatingMenu()"></div>

@push('scripts')
<script>
// Enhanced floating menu functionality
function toggleFloatingMenu() {
    const menu = document.getElementById('floating-menu');
    const backdrop = document.getElementById('menu-backdrop');
    const body = document.body;
    
    // Toggle between collapsed and expanded states
    if (menu.classList.contains('collapsed')) {
        menu.classList.remove('collapsed');
        menu.classList.add('show');
        backdrop.classList.add('show');
        body.classList.add('menu-open');
    } else {
        menu.classList.remove('show');
        menu.classList.add('collapsed');
        backdrop.classList.remove('show');
        body.classList.remove('menu-open');
    }
    
    // Menu is now using inline SVG icons, no need for Feather initialization
}

// Close menu when clicking on menu items (for better UX)
document.addEventListener('DOMContentLoaded', function() {
    // Menu now uses inline SVG icons, no Feather initialization needed
    
    // Close menu when clicking on non-dropdown menu items (only when expanded)
    const menuItems = document.querySelectorAll('.floating-menu-item:not(.floating-dropdown-toggle)');
    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            // Don't close if it's an external link or if menu is collapsed
            const menu = document.getElementById('floating-menu');
            if (!this.getAttribute('href').startsWith('http') && !menu.classList.contains('collapsed')) {
                setTimeout(() => {
                    toggleFloatingMenu();
                }, 150);
            }
        });
    });
    
    // Handle dropdown toggles
    const dropdownToggles = document.querySelectorAll('.floating-dropdown-toggle');
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const target = document.querySelector(targetId);
            
            if (target) {
                // Toggle the collapse
                const isShown = target.classList.contains('show');
                target.classList.toggle('show');
                
                // Update aria-expanded
                this.setAttribute('aria-expanded', !isShown);
                
                // Toggle the arrow
                this.classList.toggle('collapsed', isShown);
            }
        });
    });
    
    // Enhanced notification handling for floating menu
    function updateFloatingNotificationCount() {
        fetch('{{ route('notifications.count') }}')
            .then(response => response.json())
            .then(data => {
                const badge = document.querySelector('#floating-notification-count');
                const newCount = data.count;

                if (newCount > 0) {
                    badge.textContent = newCount;
                    badge.style.display = 'block';
                } else {
                    badge.style.display = 'none';
                }
            })
            .catch(error => console.error('Error fetching notifications:', error));
    }
    
    // Update notifications every 10 seconds
    updateFloatingNotificationCount();
    setInterval(updateFloatingNotificationCount, 10000);
    
    // Close menu on escape key (only when expanded)
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const menu = document.getElementById('floating-menu');
            if (menu.classList.contains('show') && !menu.classList.contains('collapsed')) {
                toggleFloatingMenu();
            }
        }
    });
});
</script>
@endpush 