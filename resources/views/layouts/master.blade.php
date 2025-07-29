{{-- Layout maestro para las pantallas del dashboard --}}
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | {{ env('APP_NAME') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="description" content="Aquafeel es una organización comprometida en proporcionar agua potable, 
limpia y segura a nuestras comunidades, al tiempo que facilita el ahorro de 
dinero y promueve la sostenibilidad y la salud.">
    <meta name="author" content="AQUAFEEL Global">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App favicon -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="icon" sizes="512x512" href="{{ asset('/icono.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('/icono.png') }}">
    <link rel="shortcut icon" href="{{ asset('icono.png') }}">
    <!-- Styles css -->
    @include('layouts.partials.styles')
    <link href="{{ asset('vendor/data-tables-bootstrap5/datatables.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/data-picker-bootstrap5/gijgo.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('vendor/jquery-3.7.1.min.js') }}"></script>
    <style>
        /* Mont Font Family - Same as login */
        @font-face {
            font-family: 'Mont';
            src: url('{{ asset("font/Mont Font Family/Mont-Regular.ttf") }}') format('truetype');
            font-weight: 400;
            font-style: normal;
        }
        
        @font-face {
            font-family: 'Mont';
            src: url('{{ asset("font/Mont Font Family/Mont-Bold.ttf") }}') format('truetype');
            font-weight: 700;
            font-style: normal;
        }
        
        @font-face {
            font-family: 'Mont';
            src: url('{{ asset("font/Mont Font Family/Mont-Light.ttf") }}') format('truetype');
            font-weight: 300;
            font-style: normal;
        }
        
        @font-face {
            font-family: 'Mont';
            src: url('{{ asset("font/Mont Font Family/Mont-SemiBold.ttf") }}') format('truetype');
            font-weight: 600;
            font-style: normal;
        }

        :root {
            --color-primary: #002270;
            --color-primary-dark: #000a29;
            --color-secondary: #002270;
            --color-secondary-dark: #000a29;
            --color-accent: #3b82f6;
            --color-accent-dark: #1e40af;
            --color-dark: #000a29;
            --color-text: #495057;
            --color-light-text: #6c757d;
            --color-border: #eaeaea;
            --color-input-bg: #ffffff;
            --color-input-bg-hover: #f8f9fa;
            --shadow-card: 0 12px 30px rgba(0, 34, 112, 0.25);
            --shadow-btn: 0 5px 15px rgba(0, 34, 112, 0.3);
            --shadow-input: 0 2px 4px rgba(0, 0, 0, 0.05);
            --transition-normal: all 0.3s ease;
            --transition-slow: all 0.5s ease;
            --transition-fast: all 0.2s ease;
            --radius-sm: 4px;
            --radius-md: 8px;
            --radius-lg: 15px;
        }
        
        html, body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        
        body {
            background-color: #F1F8FF;
            font-family: 'Mont', 'Helvetica', 'Arial', sans-serif;
            color: var(--color-text);
            min-height: 100vh;
        }
        
        /* Apply Mont font to all elements */
        * {
            font-family: 'Mont', 'Helvetica', 'Arial', sans-serif;
        }
        
        /* Navbar top styles */
        .top-navbar {
            background: linear-gradient(135deg, #002270 0%, #002270 90%);
            background-image: radial-gradient(at 100% 100%,#000a29 0,transparent 50%), radial-gradient(at 0 0,#000a29 0,transparent 50%);
            background-color: rgb(30, 58, 138);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            height: 80px;
        }
        
        .navbar-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 100%;
            padding: 0 1.5rem;
            position: relative;
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.25rem;
            gap: 0.75rem;
        }
        
        .navbar-logo {
            height: 30px;
            width: auto;
            max-width: none;
            object-fit: contain;
        }
        
        .navbar-center {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1;
            border-radius: 30px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 0.75rem 1.5rem;
        }
        
        .page-title {
            color: white;
            font-size: 1.3rem;
            justify-content: center;
            text-align: center;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            white-space: nowrap;
        }
        
        .page-title-icon {
            width: 28px;
            height: 28px;
            color: rgba(255, 255, 255, 0.9);
        }
        
        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .menu-toggle-btn {
            background: rgba(255, 255, 255, 0.15);
            border: none;
            color: white;
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .menu-toggle-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: scale(1.05);
        }
        
        .user-nav-section {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: white;
        }
        
        .user-nav-avatar {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .user-nav-info {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }
        
        .user-nav-name {
            font-weight: 600;
            font-size: 0.9rem;
            margin: 0;
        }
        
        .user-nav-role {
            font-size: 0.75rem;
            opacity: 0.8;
            margin: 0;
        }
        
        /* Main content area */
        .main-container {
            margin-top: 80px;
            margin-left: 80px; /* Space for collapsed menu */
            min-height: calc(100vh - 80px);
            padding: 1.5rem;
            transition: margin-left var(--transition-normal);
        }
        
        /* When menu is expanded, add more margin */
        body.menu-open .main-container {
            margin-left: 320px;
        }
        
        /* Mobile responsive */
        @media (max-width: 768px) {
            .navbar-center {
                display: none;
            }
            
            .user-nav-info {
                display: none;
            }
            
            .main-container {
                margin-left: 70px; /* Space for collapsed menu on mobile */
                padding: 1rem;
            }
            
            /* When menu is expanded on mobile, no margin (full overlay) */
            body.menu-open .main-container {
                margin-left: 0;
            }
            
            .navbar-brand span {
                display: none !important;
            }
            
            .navbar-content {
                justify-content: space-between;
            }
        }
        
        @media (max-width: 992px) and (min-width: 769px) {
            .page-title {
                font-size: 1.25rem;
            }
        }
        
        @media (max-width: 576px) {
            .navbar-content {
                padding: 0 1rem;
            }
            
            .main-container {
                margin-left: 70px; /* Space for collapsed menu */
                padding: 0.75rem;
            }
        }
    </style>
</head>

<body>
    <!-- Top Navbar -->
    <nav class="top-navbar">
        <div class="navbar-content">
            <!-- Left side - Menu Toggle + Brand -->
            <div class="d-flex align-items-center gap-3">
                <!-- Menu Toggle Button -->
                <button class="menu-toggle-btn" onclick="toggleFloatingMenu()" aria-label="Toggle menu">
                    <i data-feather="menu" style="width: 24px; height: 24px;"></i>
                </button>
                
                <!-- Brand -->
                <div class="navbar-brand">
                    <img src="{{ asset('favicon10.png') }}" alt="AQUAFEEL Logo" class="navbar-logo">
                </div>
            </div>
            
            <!-- Center - Page Title -->
            <div class="navbar-center">
                <h1 class="page-title" id="page-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="page-title-icon" style="margin-top: -3px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    <span id="page-title-text">@lang('translation.home_title')</span>
                </h1>
            </div>
            
            <!-- Right side - User Section -->
            <div class="navbar-actions">
                <!-- User Section -->
                <div class="user-nav-section">
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
                    
                    <div class="user-nav-info d-none d-md-flex">
                        <p class="user-nav-name">{{ $name }} {{ $inicial }}</p>
                        <span class="user-nav-role">{{ $rol }}</span>
                    </div>
                    
                    <div class="user-nav-avatar">
                        @if (Auth::user() && Auth::user()->image_path && file_exists(public_path('storage/' . Auth::user()->image_path)))
                            <img src="{{ url('storage/'. Auth::user()->image_path) }}" alt="Perfil" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <span style="color: white; font-weight: 600;">
                                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Floating Menu (converted from sidebar) -->
    @include('layouts.partials.menu')
    
    <!-- Main Content Area -->
    <div class="main-container">
        <div class="container-fluid">
            @yield('content')
        </div>
    </div>
    
    <!-- Script js -->
    @include('layouts.partials.scripts')
    <script>
        // Function to toggle floating menu
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
        }
        
        // Function to update page title dynamically
        function updatePageTitle(title, icon = 'home') {
            const titleElement = document.getElementById('page-title-text');
            const iconElement = document.querySelector('.page-title-icon');
            
            if (titleElement) titleElement.textContent = title;
            if (iconElement) iconElement.setAttribute('data-feather', icon);
            
            // Re-initialize feather icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        }
        
        // Make updatePageTitle globally available
        window.updatePageTitle = updatePageTitle;
        
        // Prevent horizontal scroll
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize feather icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
            
            const preventHorizontalScroll = function(e) {
                const target = e.target;
                const isInTable = target.closest('.table-responsive') || 
                                 target.closest('.table') || 
                                 target.closest('.tabla-informe');
                
                if (isInTable) {
                    return;
                }
                
                if (e.touches && e.touches.length > 0) {
                    const touch = e.touches[0];
                    const startX = touch.clientX;
                    const startY = touch.clientY;
                    
                    const handleTouchMove = function(e) {
                        if (e.touches && e.touches.length > 0) {
                            const touch = e.touches[0];
                            const deltaX = Math.abs(touch.clientX - startX);
                            const deltaY = Math.abs(touch.clientY - startY);
                            
                            if (deltaX > deltaY) {
                                e.preventDefault();
                            }
                        }
                    };
                    
                    document.addEventListener('touchmove', handleTouchMove, { passive: false });
                    document.addEventListener('touchend', function() {
                        document.removeEventListener('touchmove', handleTouchMove);
                    }, { once: true });
                }
            };
            
            document.addEventListener('touchstart', preventHorizontalScroll, { passive: false });
        });
    </script>
</body>

</html>