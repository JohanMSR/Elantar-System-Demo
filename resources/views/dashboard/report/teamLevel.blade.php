@extends('layouts.master')

@section('title')
    @lang('Reportes - Centro de Negocio')
@endsection

@push('css')
    <link href="{{ asset('vendor/data-tables-bootstrap5/datatables.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/data-picker-bootstrap5/gijgo.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('vendor/apexcharts-bundle/apexcharts.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jstree/3.3.8/themes/default/style.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <style>
       html, body {
            height: 100%;
            background-color: #F1F8FF;
        }
        
        * {
            margin: 0;
            padding: 0;
        }
       
        .titulo_page {
            font-weight: 800;
            font-size: 20px;
            line-height: 27px;
        }

        /* Estilos para el organigrama */
        #organigrama {
            width: 100%;
            position: relative;
            padding-bottom: 40px;
            margin-bottom: 20px;
        }

        .org-chart-wrapper {
            max-height: 90vh;
            min-height: 700px;
            width: 100%;
            position: relative;
            cursor: grab;
            overflow: hidden;
            margin-bottom: 30px;
        }
        
        .org-chart-wrapper:active {
            cursor: grabbing;
        }

        .zoom-controls {
            position: absolute;
            top: 10px;
            right: 10px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            z-index: 1000;
            padding: 8px;
        }

        /* Ajustar posición en dispositivos móviles */
        @media (max-width: 768px) {
            .zoom-controls {
                top: auto;
                bottom: 60px;
                right: 10px;
            }
        }

        .zoom-btn {
            border: none;
            background: #f0f7ff;
            width: 34px;
            height: 34px;
            border-radius: 6px;
            margin: 0 3px;
            cursor: pointer;
            transition: all 0.2s;
            color: #0056b3;
        }

        .zoom-btn:hover {
            background: #e0eeff;
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .zoom-info {
            display: inline-block;
            padding: 0 10px;
            font-size: 14px;
            font-weight: 500;
            color: #444;
        }

        .org-chart {
            display: inline-block;
            min-width: 100%;
            transform-origin: 0 0;
            transition: transform 0.1s linear;
            padding: 20px;
            will-change: transform;
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
            transform-style: flat;
        }

        .org-chart ul {
            padding-top: 30px;
            position: relative;
            transition: all 0.3s;
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }

        .org-chart li {
            float: left;
            text-align: center;
            list-style-type: none;
            position: relative;
            padding: 30px 5px 0 5px;
            transition: all 0.3s;
            margin: 0 5px;
        }

        .org-chart li::before, .org-chart li::after {
            content: '';
            position: absolute;
            top: 0;
            height: 30px;
            z-index: 1;
        }

        .org-chart li::before {
            border-top: 2px solid #ccc;
            right: 50%;
            width: 50%;
            margin-right: -1px;
        }

        .org-chart li::after {
            border-top: 2px solid #ccc;
            border-left: 2px solid #ccc;
            left: 50%;
            width: 50%;
            margin-left: -1px;
        }

        .org-chart li:only-child::after, .org-chart li:only-child::before {
            display: none;
        }

        .org-chart li:only-child {
            padding-top: 0;
        }

        .org-chart li:first-child::before, .org-chart li:last-child::after {
            border: 0 none;
        }

        .org-chart li:last-child::before {
            border-right: 2px solid #ccc;
            border-radius: 0 5px 0 0;
        }

        .org-chart li:first-child::after {
            border-radius: 5px 0 0 0;
        }

        .org-chart ul ul::before {
            content: '';
            position: absolute;
            top: -1px; /* Conectar exactamente con la línea superior */
            left: 50%;
            border-left: 2px solid #ccc;
            height: 31px; /* Ligeramente mayor para garantizar conexión */
            width: 0;
            z-index: 0;
            margin-left: -1px; /* Centrar perfectamente la línea vertical */
        }

        .org-chart li .node {
            border: 2px solid #bdccdb;
            padding: 10px;
            border-radius: 5px;
            background-color: white;
            display: inline-block;
            min-width: 180px;
            min-height: 60px;
            position: relative;
            transition: all 0.2s;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            cursor: pointer;
            z-index: 2;
            margin-bottom: 20px; /* Añadir espacio debajo del nodo */
        }

        .org-chart li .node:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            transform: translateY(-3px);
        }
        
        .org-chart li .node .toggle-icon {
            position: absolute;
            bottom: -12px;
            left: 50%;
            transform: translateX(-50%);
            width: 24px;
            height: 24px;
            background-color: #f0f7ff;
            border: 2px solid #bdccdb;
            border-radius: 50%;
            z-index: 3;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .org-chart li .node .toggle-icon:hover {
            background-color: #e6f0ff;
        }

        .org-chart li .node.ceo {
            background-color: #e6f0ff;
            border-color: #b3d1ff;
        }

        .org-chart li .node.vp {
            background-color: #f0f7ff;
            border-color: #c6dfff;
        }

        .org-chart li .node.manager {
            background-color: #f5faff;
        }

        .org-chart li .node.employee {
            background-color: #fbfdff;
        }

        .org-chart li .node .avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #ddd;
            margin: 0 auto 10px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .org-chart li .node .avatar img {
            width: 100%;
            height: auto;
        }

        .org-chart li .node .name {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .org-chart li .node .title {
            font-size: 12px;
            color: #666;
        }

        .org-chart li .node .office {
            font-size: 11px;
            color: #888;
            font-style: italic;
            margin-top: 5px;
        }
        
        .org-chart li ul {
            transition: all 0.3s;
            overflow: hidden;
            position: relative;
        }
        
        .org-chart li ul.collapsed {
            height: 0 !important;
            padding-top: 0;
            opacity: 0;
            margin-top: 0;
            min-height: 0;
            overflow: hidden;
            visibility: hidden;
            transition: none !important;
        }

        /* Mejora para arreglar alineaciones */
        .org-chart ul.level-1,
        .org-chart ul.level-2,
        .org-chart ul.level-3,
        .org-chart ul.level-4 {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            width: 100%;
            position: relative;
        }

        .org-chart ul.level-1 > li,
        .org-chart ul.level-2 > li,
        .org-chart ul.level-3 > li,
        .org-chart ul.level-4 > li {
            flex: 0 1 auto;
            position: relative;
        }

        /* Estilos para grupos con muchos hijos */
        .org-chart li.has-many-children {
            min-width: 400px;
        }
        
        /* Estilo para conexión invisible cuando colapsado */
        .org-chart li ul.collapsed::before {
            opacity: 0;
            height: 0;
            visibility: hidden;
        }
        
        /* Corregir conexiones en móviles */
        @media (max-width: 768px) {
            .org-chart ul {
                padding-top: 20px;
            }
            
            .org-chart li {
                padding: 20px 5px 0 5px;
                margin: 0 5px;
            }
            
            .org-chart li::before, 
            .org-chart li::after,
            .org-chart ul ul::before {
                height: 20px;
            }
            
            .org-chart ul.many-children-container,
            .org-chart ul.single-child-container {
                padding-top: 25px;
            }
            
            .org-chart ul.many-children-container::before,
            .org-chart li.has-many-children > ul::before,
            .org-chart ul.single-child-container::before {
                height: 25px;
            }
            
            .org-chart li.only-child {
                padding-top: 25px;
            }
            
            .org-chart li .node {
                min-width: 120px;
                padding: 5px;
                margin-bottom: 10px; /* Margen inferior adaptado para móviles */
            }
            
            .org-chart li .node .avatar {
                width: 30px;
                height: 30px;
            }
            
            .org-chart li .node .name {
                font-size: 12px;
            }
            
            .org-chart li .node .title {
                font-size: 10px;
            }
            
            .org-chart li .node .office {
                font-size: 9px;
            }
            
            .org-chart li .node .toggle-icon {
                width: 18px;
                height: 18px;
                font-size: 10px;
                bottom: -10px;
            }
        }

        /* Ocultar encabezado en móviles */
        @media (max-width: 600px) {
            #principal-head {
                display: none;
            }
        }

        /* Mensaje para optimizar vista en móviles */
        .mobile-hint {
            display: none;
            text-align: center;
            padding: 10px;
            background-color: #f0f7ff;
            margin-bottom: 15px;
            border-radius: 8px;
            color: #0056b3;
            font-weight: 500;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        @media (max-width: 768px) {
            .mobile-hint {
                display: block;
            }
            
            #organigrama {
                padding-bottom: 60px; /* Mayor espacio en móviles */
            }
            
            .org-chart-wrapper {
                min-height: 600px; /* Altura adaptada para móviles */
                margin-bottom: 45px; /* Mayor margen inferior en móviles */
            }
            
            .org-btn {
                margin-right: 8px;
                padding: 6px 12px;
            }
        }
        
        /* Estilos para los controles adicionales */
        .org-controls {
            margin-bottom: 15px;
            display: flex;
            justify-content: flex-start;
            flex-wrap: wrap;
        }
        
        /* Centrar controles en móviles */
        @media (max-width: 768px) {
            .org-controls {
                justify-content: center;
            }
        }
        
        .org-btn {
            background: #f0f7ff;
            border: 1px solid #c6dfff;
            border-radius: 8px;
            padding: 8px 15px;
            margin-right: 12px;
            margin-bottom: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            color: #0056b3;
            display: flex;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        .org-btn:hover {
            background: #e0eeff;
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .org-btn i {
            margin-right: 8px;
        }

        /* Estilo para el botón en el encabezado */
        .header-download-btn {
            background: transparent;
            color: #ffffff;
            border: 1px solid #ffffff;
            border-radius: 5px;
            padding: 8px 15px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            white-space: nowrap;
            height: 38px;
        }

        .header-download-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .header-download-btn i {
            margin-right: 5px;
        }

        .header-btn-container {
            display: flex;
            align-items: center;
        }

        @media (max-width: 992px) {
            .header-btn-container {
                width: 100%;
                margin-top: 15px;
                justify-content: center;
            }
        }

        /* Estilos mejorados para los diferentes tipos de hijos */
        .org-chart li.no-children {
            /* No necesita ajustes especiales */
        }
        
        .org-chart li.single-child::before {
            border-top: 2px solid #ccc;
            width: 50%;
        }
        
        .org-chart li.single-child::after {
            border-left: 2px solid #ccc;
            border-top: none;
            height: 20px;
        }
        
        .org-chart ul.level-1 > li:only-child,
        .org-chart ul.level-2 > li:only-child,
        .org-chart ul.level-3 > li:only-child,
        .org-chart ul.level-4 > li:only-child {
            padding-top: 20px;
        }
        
        /* Mejora visual para líneas conectoras */
        .org-chart ul::before {
            background-color: transparent;
            z-index: 1;
        }
        
        /* Mejoras para múltiples elementos en fila */
        .org-chart li.has-many-children {
            min-width: 400px;
        }
        
        /* Forzar que los nodos estén por encima de las líneas */
        .org-chart li .node {
            position: relative;
            z-index: 2;
        }
        
        /* Mejorar la visualización de líneas durante transiciones */
        .org-chart ul.collapsed {
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        
        /* Corregir líneas para último elemento en grupo */
        .org-chart ul li:last-child::before {
            border-right: 2px solid #ccc;
            border-radius: 0 5px 0 0;
            z-index: 1;
        }
        
        /* Corregir espacios para líneas en dispositivos pequeños */
        @media (max-width: 768px) {
            .org-chart li {
                margin: 0 5px;
                padding: 15px 5px 0 5px;
            }
        }

        /* Corrección para líneas cortadas */
        .org-chart li {
            float: left;
            text-align: center;
            list-style-type: none;
            position: relative;
            padding: 20px 5px 0 5px;
            transition: all 0.3s;
            margin: 0 5px;
        }

        /* Ajustes para evitar saltos en las líneas */
        .org-chart li::before, .org-chart li::after,
        .org-chart ul ul::before {
            box-sizing: border-box;
            pointer-events: none;
        }
        
        /* Asegurar conexión continua entre niveles */
        .org-chart ul::before {
            top: -1px; /* Ajuste para evitar pequeños saltos */
        }
        
        /* Asegurar que los pseudo-elementos se muestran correctamente */
        .org-chart li, 
        .org-chart ul {
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
        }
        
        /* Corrección para rendimiento y evitar pixelación */
        .org-chart li::before, 
        .org-chart li::after,
        .org-chart ul::before {
            will-change: transform;
            transform: translateZ(0);
        }

        /* Corrección de líneas para diferentes casos */
        /* Caso de un solo hijo */
        .org-chart ul.single-child-container::before {
            height: 32px;
        }
        
        .org-chart li.only-child {
            padding-top: 32px;
        }
        
        /* Estilos para el primer y último hijo */
        .org-chart li.first-child::before {
            border-width: 0;
        }
        
        .org-chart li.last-child::after {
            border-width: 0;
        }
        
        /* Mejora para el último hijo de un grupo */
        .org-chart li.last-child::before {
            border-right: 2px solid #ccc;
            border-radius: 0 5px 0 0;
        }
        
        /* Mejora para el primer hijo de un grupo */
        .org-chart li.first-child::after {
            border-radius: 5px 0 0 0;
        }
        
        /* Caso de muchos hijos, mejorar el espaciado y líneas */
        .org-chart ul.many-children-container {
            padding-top: 35px;
        }
        
        .org-chart ul.many-children-container::before {
            height: 35px;
        }
        
        /* Corrección para nodos con líneas distantes */
        .org-chart li.has-many-children > ul::before {
            height: 35px;
        }
        
        /* Ajustes para líneas horizontales en grupos grandes */
        .org-chart ul.many-children-container > li::before,
        .org-chart ul.many-children-container > li::after {
            width: 51%;
        }

        /* Solucionar problema de compatibilidad con navegadores */
        .org-chart {
            transform-style: flat;
        }
        
        /* Evitar problemas de redondeo que causan líneas cortadas */
        .org-chart ul::before,
        .org-chart li::before,
        .org-chart li::after {
            transform: translate3d(0,0,0);
            -webkit-transform: translate3d(0,0,0);
            -moz-transform: translate3d(0,0,0);
            -ms-transform: translate3d(0,0,0);
        }
        
        /* Corregir problema específico de líneas horizontales */
        .org-chart li::before {
            margin-right: -1px;
        }
        
        .org-chart li::after {
            margin-left: -1px;
        }
        
        /* Corrección para líneas verticales */
        .org-chart ul::before {
            margin-left: -1px;
        }
        
        /* Asegurar que no haya saltos en las transiciones */
        .org-chart li ul.collapsed {
            transition: none !important;
        }

        /* Evitar solapamiento de toggle icons con líneas */
        .org-chart ul ul::before {
            z-index: 0; /* Asegurar que las líneas queden debajo de los botones */
        }

        /* Corrección precisa para conexiones entre líneas y tarjetas */
        .org-chart ul::before {
            left: 50%;
            margin-left: -1px;
            z-index: 0;
            top: 0;
            height: 30px;
        }
        
        .org-chart li::before,
        .org-chart li::after {
            top: 0;
            height: 30px;
            z-index: 1;
        }
        
        /* Solucionar líneas cortadas con mayor precisión */
        .org-chart ul.level-1 > li::after,
        .org-chart ul.level-2 > li::after,
        .org-chart ul.level-3 > li::after,
        .org-chart ul.level-4 > li::after {
            height: 31px; /* Ligeramente mayor para evitar cortes */
        }

        /* Ajuste fino para asegurar líneas perfectamente conectadas */
        .org-chart ul ul::before {
            height: 32px; /* Un píxel más para garantizar continuidad */
        }
        
        /* Asegurar que las líneas sean del mismo grosor para evitar discontinuidades */
        .org-chart li::before, 
        .org-chart li::after, 
        .org-chart ul ul::before {
            border-width: 2px; /* Estandarizar grosor de líneas */
        }
        
        /* Compensar pixel perfecto para esquinas */
        .org-chart li:last-child::before {
            border-right-width: 2px;
        }
        
        /* Cuidar las conexiones en dispositivos de alta resolución */
        @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
            .org-chart li::before, 
            .org-chart li::after, 
            .org-chart ul ul::before {
                border-width: 2px; /* Mantener grosor visible en pantallas retina */
            }
            
            /* Perfeccionar conexiones */
            .org-chart ul ul::before {
                margin-left: -1px; /* Ajuste crítico para pantallas de alta resolución */
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        @php
            $teamTitle = __('translation.team_title');
        @endphp
        
        <x-page-header :title="$teamTitle" icon="users">
            <div class="header-btn-container">
                <form method="POST" action="{{route('report.exportTeam')}}">
                    @csrf
                    <button class="header-download-btn" title="Descargar" type="submit">
                        <i class="fas fa-download"></i> Descargar Equipo
                    </button>
                </form>
            </div>
            <span class="update-dot" style="display: none; color: #28a745; font-size: 12px; margin-left: 5px;">•</span>
        </x-page-header>
        <br>
        <div class="container-fluid bg-light">
            <div class="row justify-content-center">
                <div class="col-12 mx-auto" id="organigrama">
                    <div class="mobile-hint">
                        <i class="fas fa-info-circle"></i> Usa los controles de zoom o pellizca para ajustar la vista
                    </div>
                    
                    <div class="org-controls">
                        <button class="org-btn expand-all">
                            <i class="fas fa-expand-arrows-alt"></i> Expandir todo
                        </button>
                        <button class="org-btn collapse-all">
                            <i class="fas fa-compress-arrows-alt"></i> Colapsar todo
                        </button>
                    </div>
                    
                    <div class="zoom-controls">
                        <button class="zoom-btn zoom-out" title="Alejar"><i class="fas fa-search-minus"></i></button>
                        <span class="zoom-info">100%</span>
                        <button class="zoom-btn zoom-in" title="Acercar"><i class="fas fa-search-plus"></i></button>
                        <button class="zoom-btn zoom-reset" title="Restablecer"><i class="fas fa-sync-alt"></i></button>
                    </div>
                    
                    <div class="org-chart-wrapper">
                        <div id="org-chart-container" class="org-chart"></div>
                    </div>
                </div>
            </div>
            <br><br>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('vendor/jquery-3.7.1.min.js') }}"></script>    
    <script src="{{ asset('vendor/echarts/echarts.min.js') }}"></script>    
    <script src="//cdnjs.cloudflare.com/ajax/libs/jstree/3.3.8/jstree.min.js"></script>
    <script>
        // Optimización: envolver en IIFE para evitar contaminación del ámbito global
        (function() {
            // Función para determinar el rol visual de un usuario
            function determinarRol(rol) {
                rol = rol.toLowerCase();
                if (rol.includes('director') || rol.includes('ceo')) {
                    return 'ceo';
                } else if (rol.includes('vicepresidente') || rol.includes('vp') || rol.includes('vice president')) {
                    return 'vp';
                } else if (rol.includes('manager') || rol.includes('gerente') || rol.includes('supervisor')) {
                    return 'manager';
                } else {
                    return 'employee';
                }
            }

            // Función para contar hijos directos de un usuario
            function contarHijos(datos, coUsuario) {
                return datos.filter(usuario => usuario.co_usuario_padre === coUsuario).length;
            }

            // Función para crear el HTML del organigrama de forma recursiva
            function crearOrganigrama(datos, coUsuarioPadre = 0, nivel = 1, colapsado = true) {
                // Optimización: caché de resultados para mejorar rendimiento
                const cache = {};
                const cacheKey = `${coUsuarioPadre}-${nivel}-${colapsado}`;
                
                if (cache[cacheKey]) {
                    return cache[cacheKey];
                }
                
                const hijosDirectos = datos.filter(usuario => usuario.co_usuario_padre === coUsuarioPadre);
                
                if (hijosDirectos.length === 0) return '';
                
                // Solo el primer nivel está expandido por defecto
                const claseColapsado = (nivel === 1 || !colapsado) ? '' : 'collapsed';
                
                // Añadir clase específica para el número de hijos (mejora las líneas)
                const claseNumHijos = hijosDirectos.length === 1 ? 'single-child-container' : 
                                     (hijosDirectos.length > 4 ? 'many-children-container' : 'normal-children-container');
                
                let html = `<ul class="level-${nivel} ${claseColapsado} ${claseNumHijos}">`;
                
                for (let i = 0; i < hijosDirectos.length; i++) {
                    const usuario = hijosDirectos[i];
                    if (usuario.oficina == null)
                        usuario.oficina = "Sin Oficina Asignada";
                    
                    const rolClase = determinarRol(usuario.rol);
                    const cantidadHijos = contarHijos(datos, usuario.co_usuario);
                    const claseMuchos = cantidadHijos > 4 ? 'has-many-children' : '';
                    const usuarioId = `usuario-${usuario.co_usuario}`;
                    
                    // Añadir clases para posición relativa (mejora líneas)
                    const esPrimero = i === 0 ? 'first-child' : '';
                    const esUltimo = i === hijosDirectos.length - 1 ? 'last-child' : '';
                    const esUnico = hijosDirectos.length === 1 ? 'only-child' : '';
                    
                    html += `<li class="${claseMuchos} ${esPrimero} ${esUltimo} ${esUnico}" id="${usuarioId}">`;
                    html += `<div class="node ${rolClase}" data-usuario-id="${usuario.co_usuario}">`;
                    html += `<div class="avatar"><img src="{{ asset('vendor/feather-icons/icons/user.svg') }}" alt="Usuario"></div>`;
                    html += `<div class="name">${usuario.tx_primer_nombre} ${usuario.tx_primer_apellido}</div>`;
                    html += `<div class="title">${usuario.rol}</div>`;
                    html += `<div class="office">${usuario.oficina}</div>`;
                    
                    // Agregar botón de toggle solo si tiene hijos
                    if (cantidadHijos > 0) {
                        html += `<div class="toggle-icon" data-usuario-id="${usuario.co_usuario}">`;
                        html += `<i class="fas ${claseColapsado ? 'fa-plus' : 'fa-minus'}"></i>`;
                        html += `</div>`;
                    }
                    
                    html += '</div>';
                    
                    // Verificar si tiene hijos
                    if (cantidadHijos > 0) {
                        html += crearOrganigrama(datos, usuario.co_usuario, nivel + 1, colapsado);
                    }
                    
                    html += '</li>';
                }
                
                html += '</ul>';
                
                // Guardar en caché para futuras llamadas
                cache[cacheKey] = html;
                
                return html;
            }
            
            // Hacer disponible la función al ámbito global
            window.crearOrganigrama = crearOrganigrama;
        })();
    </script>    
    <script>
        // Datos iniciales y variables globales (optimizado para rendimiento)
        const datos = {!! json_encode($team) !!};
        const padre = datos[0].co_usuario_padre;
        
        // Variables para control de datos
        let datosActuales = JSON.stringify(datos);
        let indicadorVisible = false;
        let zoomLevel = 1;
        let colapsadoPorDefecto = true;
        
        // Variables para el arrastre
        let isDragging = false;
        let startPosition = { x: 0, y: 0 };
        let currentTranslate = { x: 0, y: 0 };
        let lastTranslate = { x: 0, y: 0 };
        let rafId = null; // RequestAnimationFrame ID para animación suave
        
        // Función para inicializar el organigrama de manera más eficiente
        function initializeOrgChart(data, reset = false) {
            // Cancelar cualquier animación pendiente
            if (rafId) {
                cancelAnimationFrame(rafId);
                rafId = null;
            }
            
            const orgChart = document.getElementById('org-chart-container');
            
            // Crear el HTML del organigrama una sola vez
            const orgHTML = crearOrganigrama(data, padre, 1, colapsadoPorDefecto);
            orgChart.innerHTML = orgHTML;
            
            // Si es una reinicialización completa, resetear todo
            if (reset) {
                zoomLevel = 1;
                currentTranslate = { x: 0, y: 0 };
                
                // Centrar el organigrama después de renderizarlo
                setTimeout(centrarOrganigrama, 200);
            }
            
            // Mantener el nivel de zoom y posición después de la actualización
            applyZoom(zoomLevel);
            applyTranslate(currentTranslate.x, currentTranslate.y);
            
            // Reinicializar event listeners para los toggles
            initializeToggles();
        }
        
        // Función para inicializar los toggles de expansión/colapso
        function initializeToggles() {
            // Optimización: usar delegación de eventos para reducir listeners
            const orgChart = document.getElementById('org-chart-container');
            
            // Eliminar eventos previos si existen
            const oldClone = orgChart.cloneNode(true);
            orgChart.parentNode.replaceChild(oldClone, orgChart);
            
            // Añadir evento delegado para clicks en nodos y toggles
            oldClone.addEventListener('click', function(e) {
                // Detectar clicks en toggle-icon
                const toggleIcon = e.target.closest('.toggle-icon');
                if (toggleIcon) {
                    e.stopPropagation();
                    const usuarioId = toggleIcon.getAttribute('data-usuario-id');
                    toggleChildren(usuarioId);
                    return;
                }
                
                // Detectar clicks en nodos
                const node = e.target.closest('.node');
                if (node) {
                    const usuarioId = node.getAttribute('data-usuario-id');
                    const iconToggle = node.querySelector('.toggle-icon');
                    if (iconToggle) {
                        toggleChildren(usuarioId);
                    }
                }
            });
        }
        
        // Función optimizada para expandir/colapsar los hijos de un nodo
        function toggleChildren(usuarioId) {
            const parent = document.querySelector(`[data-usuario-id="${usuarioId}"]`);
            if (!parent) return;
            
            const li = parent.closest('li');
            const childrenUl = li.querySelector('ul');
            const toggleIcon = parent.querySelector('.toggle-icon i');
            
            if (childrenUl) {
                childrenUl.classList.toggle('collapsed');
                
                // Actualizar icono
                if (toggleIcon) {
                    if (childrenUl.classList.contains('collapsed')) {
                        toggleIcon.classList.remove('fa-minus');
                        toggleIcon.classList.add('fa-plus');
                    } else {
                        toggleIcon.classList.remove('fa-plus');
                        toggleIcon.classList.add('fa-minus');
                    }
                }
                
                // Optimización: usar classList.add/remove en lugar de forEach
                if (childrenUl.classList.contains('collapsed')) {
                    const subLists = childrenUl.querySelectorAll('ul');
                    const subIcons = childrenUl.querySelectorAll('.toggle-icon i');
                    
                    // Colapsar todos los sub-elementos
                    for (let i = 0; i < subLists.length; i++) {
                        subLists[i].classList.add('collapsed');
                    }
                    
                    // Actualizar todos los iconos
                    for (let i = 0; i < subIcons.length; i++) {
                        subIcons[i].classList.remove('fa-minus');
                        subIcons[i].classList.add('fa-plus');
                    }
                }
            }
        }
        
        // Función para expandir todos los nodos con mejor rendimiento
        function expandAll() {
            const allUls = document.querySelectorAll('.org-chart ul');
            const allIcons = document.querySelectorAll('.toggle-icon i');
            
            // Actualizar clases en bloques para mejor rendimiento
            for (let i = 0; i < allUls.length; i++) {
                allUls[i].classList.remove('collapsed');
            }
            
            for (let i = 0; i < allIcons.length; i++) {
                allIcons[i].classList.remove('fa-plus');
                allIcons[i].classList.add('fa-minus');
            }
        }
        
        // Función para colapsar todos los nodos excepto el primer nivel con mejor rendimiento
        function collapseAll() {
            const subUls = document.querySelectorAll('.org-chart ul:not(.level-1)');
            const allIcons = document.querySelectorAll('.toggle-icon i');
            
            // Actualizar clases en bloques para mejor rendimiento
            for (let i = 0; i < subUls.length; i++) {
                subUls[i].classList.add('collapsed');
            }
            
            for (let i = 0; i < allIcons.length; i++) {
                allIcons[i].classList.remove('fa-minus');
                allIcons[i].classList.add('fa-plus');
            }
        }
        
        // Función para aplicar traslación con límites para evitar que se salga de la vista
        function applyTranslate(x, y) {
            const orgChart = document.getElementById('org-chart-container');
            const wrapper = document.querySelector('.org-chart-wrapper');
            
            // Obtener las dimensiones del organigrama
            const chartRect = orgChart.getBoundingClientRect();
            const wrapperRect = wrapper.getBoundingClientRect();
            
            // Calcular límites máximos de desplazamiento con margen más amplio
            let minX, maxX, minY, maxY;
            
            if (chartRect.width * zoomLevel > wrapperRect.width) {
                // Si el organigrama es más ancho que el contenedor
                const maxOffset = chartRect.width * zoomLevel - wrapperRect.width;
                minX = -maxOffset - 200;
                maxX = 200;
            } else {
                // Si el organigrama es menos ancho que el contenedor
                minX = -200;
                maxX = wrapperRect.width - chartRect.width * zoomLevel + 200;
            }
            
            if (chartRect.height * zoomLevel > wrapperRect.height) {
                // Si el organigrama es más alto que el contenedor
                const maxOffset = chartRect.height * zoomLevel - wrapperRect.height;
                minY = -maxOffset - 200;
                maxY = 200;
            } else {
                // Si el organigrama es menos alto que el contenedor
                minY = -200;
                maxY = wrapperRect.height - chartRect.height * zoomLevel + 200;
            }
            
            // Aplicar límites al desplazamiento
            const limitedX = Math.max(minX, Math.min(maxX, x));
            const limitedY = Math.max(minY, Math.min(maxY, y));
            
            // Usar requestAnimationFrame para hacer la animación más suave
            if (rafId) {
                cancelAnimationFrame(rafId);
            }
            
            rafId = requestAnimationFrame(() => {
                // Aplicar la transformación utilizando translate3d para forzar aceleración por hardware
                orgChart.style.transform = `scale(${zoomLevel}) translate3d(${limitedX}px, ${limitedY}px, 0)`;
                rafId = null;
            });
            
            // Actualizar la posición actual
            currentTranslate = { x: limitedX, y: limitedY };
        }
        
        // Funciones para el control de zoom con mejor rendimiento
        function applyZoom(level) {
            const orgChart = document.getElementById('org-chart-container');
            
            // Usar requestAnimationFrame para animación más suave
            if (rafId) {
                cancelAnimationFrame(rafId);
            }
            
            rafId = requestAnimationFrame(() => {
                orgChart.style.transform = `scale(${level}) translate3d(${currentTranslate.x}px, ${currentTranslate.y}px, 0)`;
                document.querySelector('.zoom-info').textContent = `${Math.round(level * 100)}%`;
                rafId = null;
            });
            
            // Guardar el nivel actual para mantenerlo en actualizaciones
            zoomLevel = level;
        }
        
        // Función para centrar el organigrama en la vista con mejor rendimiento
        function centrarOrganigrama() {
            const wrapper = document.querySelector('.org-chart-wrapper');
            const orgChart = document.getElementById('org-chart-container');
            const rootNode = orgChart.querySelector('.level-1 > li > .node');
            
            if (rootNode) {
                // Obtener la posición del nodo raíz
                const rootRect = rootNode.getBoundingClientRect();
                const wrapperRect = wrapper.getBoundingClientRect();
                
                // Calcular desplazamiento para centrar
                const offsetX = (wrapperRect.width / 2 - rootRect.left - rootRect.width / 2) / zoomLevel;
                // Desplazar ligeramente hacia arriba para mostrar mejor la jerarquía
                const offsetY = 20 / zoomLevel; 
                
                // Aplicar traslación para centrar horizontalmente y ajustar verticalmente
                currentTranslate = { x: offsetX, y: offsetY };
                applyTranslate(offsetX, offsetY);
                
                // Agregar botón de "Volver al centro" si no existe
                if (!document.querySelector('.center-btn')) {
                    const centerBtn = document.createElement('button');
                    centerBtn.className = 'zoom-btn center-btn';
                    centerBtn.title = 'Volver al centro';
                    centerBtn.innerHTML = '<i class="fas fa-crosshairs"></i>';
                    document.querySelector('.zoom-controls').appendChild(centerBtn);
                    
                    centerBtn.addEventListener('click', centrarOrganigrama);
                }
            }
        }
        
        // Inicializar el organigrama con los datos iniciales
        $(function() {
            // Configurar tamaño inicial del contenedor para mejor visualización
            const wrapper = document.querySelector('.org-chart-wrapper');
            const windowHeight = window.innerHeight;
            // Utilizar el 90% de la altura de la ventana
            wrapper.style.maxHeight = `${windowHeight * 0.9}px`;
            
            initializeOrgChart(datos, true);
            
            // Configurar controles de zoom
            $('.zoom-in').on('click', function() {
                zoomLevel = Math.min(zoomLevel + 0.1, 3);
                applyZoom(zoomLevel);
            });
            
            $('.zoom-out').on('click', function() {
                zoomLevel = Math.max(zoomLevel - 0.1, 0.3);
                applyZoom(zoomLevel);
            });
            
            $('.zoom-reset').on('click', function() {
                zoomLevel = 1;
                currentTranslate = { x: 0, y: 0 };
                applyTranslate(0, 0);
                applyZoom(1);
                // Centrar después de resetear
                setTimeout(centrarOrganigrama, 50);
            });
            
            // Configurar controles para expandir/colapsar
            $('.expand-all').on('click', function() {
                expandAll();
            });
            
            $('.collapse-all').on('click', function() {
                collapseAll();
            });
            
            // Configurar controles para arrastrar con el ratón (optimizado)
            wrapper.addEventListener('mousedown', function(e) {
                if (e.target === this || e.target.classList.contains('org-chart') || 
                    (e.target.closest('.org-chart') && !e.target.closest('.node') && !e.target.closest('.toggle-icon'))) {
                    isDragging = true;
                    
                    // Guardar la posición inicial del clic
                    startPosition = {
                        x: e.clientX,
                        y: e.clientY
                    };
                    
                    // Guardar las coordenadas de traslación actuales
                    lastTranslate = {
                        x: currentTranslate.x,
                        y: currentTranslate.y
                    };
                    
                    wrapper.style.cursor = 'grabbing';
                    e.preventDefault(); // Prevenir selección de texto al arrastrar
                }
            });
            
            wrapper.addEventListener('mousemove', function(e) {
                if (isDragging) {
                    // Calcular diferencia desde el punto inicial del arrastre
                    const deltaX = (e.clientX - startPosition.x) / zoomLevel;
                    const deltaY = (e.clientY - startPosition.y) / zoomLevel;
                    
                    // Usar las coordenadas guardadas como base y sumar el desplazamiento
                    const newX = lastTranslate.x + deltaX;
                    const newY = lastTranslate.y + deltaY;
                    
                    // Actualizar la posición actual
                    currentTranslate.x = newX;
                    currentTranslate.y = newY;
                    
                    // Aplicar la transformación directamente
                    const orgChart = document.getElementById('org-chart-container');
                    orgChart.style.transform = `scale(${zoomLevel}) translate3d(${newX}px, ${newY}px, 0)`;
                }
            });
            
            // Mejorar manejo de eventos mouse/touch para finalizar arrastre
            const endDrag = function() {
                if (isDragging) {
                    isDragging = false;
                    wrapper.style.cursor = 'grab';
                    
                    // Cancelar cualquier animación pendiente
                    if (rafId) {
                        cancelAnimationFrame(rafId);
                        rafId = null;
                    }
                }
            };
            
            wrapper.addEventListener('mouseup', endDrag);
            wrapper.addEventListener('mouseleave', endDrag);
            document.addEventListener('mouseup', endDrag);
            
            // Soporte para pellizco en móviles (optimizado)
            if (typeof Hammer !== 'undefined') {
                const hammer = new Hammer(wrapper);
                
                // Mejorar la configuración de Hammer
                hammer.get('pinch').set({ enable: true, threshold: 0.1 });
                hammer.get('pan').set({ 
                    direction: Hammer.DIRECTION_ALL,
                    threshold: 0,
                    pointers: 1
                });
                
                // Optimizar evento de zoom
                hammer.on('pinch', function(e) {
                    if (rafId) {
                        cancelAnimationFrame(rafId);
                    }
                    
                    rafId = requestAnimationFrame(() => {
                        const newZoom = Math.min(Math.max(zoomLevel * e.scale, 0.3), 3);
                        zoomLevel = newZoom;
                        applyZoom(zoomLevel);
                        rafId = null;
                    });
                });
                
                // Optimizar evento de desplazamiento
                hammer.on('panstart', function(e) {
                    // Guardar la posición inicial del toque
                    startPosition = {
                        x: e.center.x,
                        y: e.center.y
                    };
                    
                    // Guardar las coordenadas de traslación actuales
                    lastTranslate = {
                        x: currentTranslate.x,
                        y: currentTranslate.y
                    };
                });
                
                // Optimizar evento de desplazamiento
                hammer.on('pan', function(e) {
                    if (e.isFinal) return; // Evitar procesamiento en el evento final
                    
                    // Calcular diferencia desde el punto inicial del arrastre
                    const deltaX = (e.center.x - startPosition.x) / zoomLevel;
                    const deltaY = (e.center.y - startPosition.y) / zoomLevel;
                    
                    // Usar las coordenadas guardadas como base y sumar el desplazamiento
                    const newX = lastTranslate.x + deltaX;
                    const newY = lastTranslate.y + deltaY;
                    
                    // Actualizar la posición actual
                    currentTranslate.x = newX;
                    currentTranslate.y = newY;
                    
                    // Aplicar la transformación directamente
                    const orgChart = document.getElementById('org-chart-container');
                    orgChart.style.transform = `scale(${zoomLevel}) translate3d(${newX}px, ${newY}px, 0)`;
                });
                
                hammer.on('panend', function() {
                    // No es necesario hacer nada más aquí
                });
                
                // Detectar doble toque para centrar en móviles
                hammer.on('doubletap', function(e) {
                    centrarOrganigrama();
                });
            }
            
            // Crear botón flotante de home para recuperación rápida
            const homeBtn = document.createElement('button');
            homeBtn.className = 'zoom-btn';
            homeBtn.title = 'Volver al inicio';
            homeBtn.innerHTML = '<i class="fas fa-home"></i>';
            homeBtn.style.position = 'absolute';
            homeBtn.style.bottom = '15px';
            homeBtn.style.right = '15px';
            homeBtn.style.zIndex = '1000';
            homeBtn.style.width = '45px';
            homeBtn.style.height = '45px';
            homeBtn.style.backgroundColor = '#f0f7ff';
            homeBtn.style.color = '#0056b3';
            homeBtn.style.borderRadius = '50%';
            homeBtn.style.boxShadow = '0 3px 8px rgba(0,0,0,0.2)';
            homeBtn.style.display = 'flex';
            homeBtn.style.alignItems = 'center';
            homeBtn.style.justifyContent = 'center';
            homeBtn.style.fontSize = '18px';
            
            homeBtn.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-3px)';
                this.style.boxShadow = '0 5px 12px rgba(0,0,0,0.25)';
            });
            
            homeBtn.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 3px 8px rgba(0,0,0,0.2)';
            });
            
            homeBtn.addEventListener('click', function() {
                zoomLevel = 1;
                applyZoom(1);
                centrarOrganigrama();
            });
            
            // Ajustar posición en móviles
            function adjustHomeButtonPosition() {
                if (window.innerWidth <= 768) {
                    homeBtn.style.bottom = '120px'; // Posición más alta en móviles
                } else {
                    homeBtn.style.bottom = '15px'; // Posición normal en escritorio
                }
            }
            
            // Ajustar inicialmente y cuando cambie el tamaño de la ventana
            adjustHomeButtonPosition();
            window.addEventListener('resize', adjustHomeButtonPosition);
            
            document.querySelector('.org-chart-wrapper').appendChild(homeBtn);
            
            // Ajustar tamaño del contenedor al cambiar tamaño de ventana
            $(window).on('resize', function() {
                const windowHeight = window.innerHeight;
                // Utilizar un porcentaje mayor del alto de la ventana
                wrapper.style.maxHeight = `${windowHeight * 0.9}px`;
                
                // Re-centrar el organigrama después de redimensionar
                setTimeout(centrarOrganigrama, 200);
            });
            
            // Configurar actualización periódica cada 30 segundos
            setInterval(updateTeamData, 30000);
        });
        
        // Función para comprobar si hay cambios en los datos
        function hayDataNueva(datosOriginales, datosNuevos) {
            return datosOriginales !== datosNuevos;
        }
        
        // Función para actualizar los datos del equipo
        function updateTeamData() {
            $.ajax({
                url: "{{ route('report.team.data') }}",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    if (data && data.length > 0) {
                        // Convertir a string para comparar
                        const datosNuevos = JSON.stringify(data);
                        
                        // Verificar si hay cambios reales
                        if (hayDataNueva(datosActuales, datosNuevos)) {
                            // Actualizar datos actuales
                            datosActuales = datosNuevos;
                            
                            // Actualizar el organigrama con los nuevos datos
                            initializeOrgChart(data);
                            
                            // Si el indicador no está visible, mostrarlo
                            if (!indicadorVisible) {
                                indicadorVisible = true;
                                $('.update-dot').fadeIn(300);
                                
                                // Programar para ocultar el indicador después de 10 segundos
                                setTimeout(function() {
                                    $('.update-dot').fadeOut(800);
                                    indicadorVisible = false;
                                }, 10000);
                            }
                        }
                    }
                },
                error: function(error) {
                    console.error("Error al actualizar los datos del equipo:", error);
                }
            });
        }
    </script>
    
    <!-- Incluir Hammer.js para soporte de gestos en dispositivos móviles -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js"></script>
@endpush
