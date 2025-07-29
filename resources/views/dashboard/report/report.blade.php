@extends('layouts.master')

@section('title')
    @lang('Reportes - Centro de Negocio')
@endsection

@push('css')
    <link href="{{ asset('vendor/data-tables-bootstrap5/datatables.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/data-picker-bootstrap5/gijgo.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('vendor/apexcharts-bundle/apexcharts.css') }}" rel="stylesheet" type="text/css" />
    <style>
       
       html,
        body {
            height: 100%;
            background-color: #F1F8FF;
        }
        @media (max-width: 600px) {
            #principal-head {
            display: none;
            }
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

        .campo-date {
            width: 150px !important;
            height: 38px !important;
        }
        
        .btnRango{
            background-color: #318ce7;
            margin-bottom: 1em;
        }

        .titulo_torta {
            font-weight: 800;
            font-size: 18px;
            line-height: 27px;
            font-family: 'Montserrat', sans-serif;
            text-align: center;
        }
        
        /* Estilos para las tarjetas al estilo de home.blade.php */
        .dashboard-card {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(19, 192, 230, 0.1);
            padding: 1.5rem;
            height: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
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
            font-family: "MontserratBold", sans-serif;
            font-size: 1.1rem;
            color: #495057;
            margin-bottom: 0.25rem;
        }

        .card-subtitle {
            font-family: "Montserrat", sans-serif;
            font-size: 0.85rem;
            color: #6c757d;
        }

        .chart-container {
            width: 100%;
            height: 400px;
        }
        
        .fade-in {
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
        
        /* Estilos para la lista de equipo */
        .team-list {
            list-style: none;
            padding: 0;
            margin: 0;
            max-height: 400px;
            overflow-y: auto;
        }

        .team-item {
            display: flex;
            align-items: center;
            padding: 0.5rem 0.75rem;
            margin-bottom: 0.5rem;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .team-item:hover {
            background-color: rgba(19, 192, 230, 0.05);
        }

        .team-color {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            margin-right: 0.75rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .team-name {
            font-size: 0.9rem;
            color: #495057;
        }
        
        /* Centrado de iconos gj-icon */
        .gj-icon {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        
        [role="right-icon"] .gj-icon,
        [role="expander"] .gj-icon,
        button .gj-icon {
            position: absolute !important;
            top: 50% !important;
            left: 50% !important;
            transform: translate(-50%, -50%) !important;
        }
        
        /* Estilos para alinear los formularios */
        .form-select, .btn, .form-control, .campo-date {
            height: 38px !important; /* Altura fija para todos los elementos de formulario */
            box-sizing: border-box !important;
            line-height: normal !important;
            font-size: 0.9rem !important;
            padding: 0.375rem 0.75rem !important;
            margin: 0 !important;
            border-radius: 0.25rem !important;
            vertical-align: middle !important;
            display: inline-block !important;
        }
        
        /* Estilo específico para el segundo grupo de controles */
        .row.g-3.mt-2 {
            margin-top: 0.75rem !important;
        }
        
        .row.g-3.mt-2 .form-select,
        .row.g-3.mt-2 .btn {
            min-width: 100px;
            max-width: 160px;
            width: auto !important;
        }
        
        /* Alineación específica para controles en la segunda fila */
        .row.g-3.mt-2 .col-6 {
            display: flex;
            justify-content: flex-start;
        }
        
        .form-label {
            margin: 0 !important;
            padding: 0 !important;
            white-space: nowrap;
            line-height: 38px !important;
            font-size: 0.9rem !important;
            display: inline-flex !important;
            align-items: center !important;
            height: 38px !important;
        }
        
        /* Asegurar que todos los contenedores flex tengan la misma altura */
        .row.g-3.align-items-center > div {
            min-height: 38px !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }
        
        /* Ajustes específicos para el datepicker */
        .gj-datepicker-bootstrap [role=right-icon] button {
            height: 38px !important;
            padding: 0.375rem 0.75rem !important;
        }
        
        /* Estilos para mejor alineación en dispositivos móviles */
        @media (max-width: 768px) {
            .row.g-3.mt-2 {
                margin-top: 1rem !important;
            }
            
            .mb-2 {
                margin-bottom: 0.5rem !important;
            }
            
            .row.g-3.mt-2 .col-12 {
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        @php
            $reportTitle = __('translation.report_title');
        @endphp
        
        <x-page-header :title="$reportTitle" icon="bar-chart" />
        <br>
        <div class="container-fluid bg-light">
            <div class="row g-4">
                <!-- Gráfica de barras -->
                <div class="col-12 fade-in">
                    <div class="dashboard-card">
                        <div class="card-header-custom">
                            <div>
                                <h5 class="card-title">Ventas Mensuales</h5>
                                <p class="card-subtitle">{{ $leyenda ?? 'Últimos 12 meses' }}</p>
                            </div>
                            <button class="btn btn-sm btn-outline-secondary" id="refreshBar">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                        
                        <form class="needs-validation mb-3" method="" action="">
                            @csrf
                            <div class="row g-3 align-items-center d-flex align-items-stretch">    
                                <div class="col-12 col-md-auto d-flex align-items-center justify-content-center">
                                    <label for="startDate" class="form-label mb-0">@lang('translation.start_date_form')</label>
                                </div>                        
                                <div class="col-12 col-md-auto d-flex align-items-center justify-content-center">
                                    <input id="startDate" name="startDate" type="text" class="form-control campo-date"
                                    placeholder="mm/dd/yyyy" aria-label="startDate" readonly>
                                </div>
                                <div class="col-12 col-md-auto d-flex align-items-center justify-content-center">
                                    <label for="endDate" class="form-label mb-0">@lang('translation.end_date_form')</label>
                                </div>
                                <div class="col-12 col-md-auto d-flex align-items-center justify-content-center">
                                    <input id="endDate" type="text" name="endDate" class="form-control campo-date"
                                    placeholder="mm/dd/yyyy" aria-label="endDate" readonly>
                                </div>
                            </div>
                            <div class="row g-3 mt-2">
                                <div class="col-auto d-flex align-items-center mb-2 mb-md-0">
                                    <select id="data_type" class="form-select" style="width: 120px;">
                                        <option value="2">Monto</option>
                                        <option value="1">Cantidad</option>
                                    </select>
                                </div>
                                <div class="col-auto d-flex align-items-center">
                                    <button id="submitBar" class="btn btn-primary" style="width: 120px;" title="Buscar"
                                    type="submit">Buscar</button>
                                </div>
                            </div>
                        </form>
                        
                        <div class="chart-container">
                            <div id="grafico_bar" style="width: 100%; height: 100%;"></div>
                        </div>
                    </div>
                </div>

                <!-- Gráfica de torta -->
                <div class="col-12 fade-in">
                    <div class="dashboard-card">
                        <div class="card-header-custom">
                            <div>
                                <h5 class="card-title">Ventas de Mi equipo</h5>
                                <p class="card-subtitle" id="titulo_torta">{{ $leyenda_torta ?? 'Últimos 3 meses' }}</p>
                            </div>
                            <button class="btn btn-sm btn-outline-secondary" id="refreshPie">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>

                        <form class="needs-validation mb-3" method="" action="">
                            @csrf
                            <div class="row g-3 align-items-center d-flex align-items-stretch">    
                                <div class="col-12 col-md-auto d-flex align-items-center justify-content-center">
                                    <label for="startDate3" class="form-label mb-0">@lang('translation.start_date_form')</label>
                                </div>                        
                                <div class="col-12 col-md-auto d-flex align-items-center justify-content-center">
                                    <input id="startDate3" name="startDate3" type="text" class="form-control campo-date"
                                    placeholder="mm/dd/yyyy" aria-label="startDate3" readonly>
                                </div>
                                <div class="col-12 col-md-auto d-flex align-items-center justify-content-center">
                                    <label for="endDate3" class="form-label mb-0">@lang('translation.end_date_form')</label>
                                </div>
                                <div class="col-12 col-md-auto d-flex align-items-center justify-content-center">
                                    <input id="endDate3" type="text" name="endDate3" class="form-control campo-date"
                                    placeholder="mm/dd/yyyy" aria-label="endDate3" readonly>
                                </div>
                            </div>
                            <div class="row g-3 mt-2">
                                <div class="col-auto d-flex align-items-center">
                                    <button id="submitPie" class="btn btn-primary" style="width: 120px;" title="Buscar"
                                    type="submit">Buscar</button>
                                </div>
                            </div>
                        </form>
                        
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="chart-container">
                                    <div id="grafico_pie" style="width: 100%; height: 100%;"></div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <ul class="team-list" id="team-list">
                                    @if(isset($data_torta) && count($data_torta) > 0)
                                        @foreach($data_torta as $key => $item)
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
            </div>
            
            <div class="row gx-5">
                @include('dashboard.report.modalsales')
            </div>   
            <div class="row gx-5">
                @include('dashboard.report.modalteamsales')
            </div>                                           
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('vendor/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('vendor/data-tables-bootstrap5/datatables.min.js') }}"></script>
    <script src="{{ asset('vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('vendor/data-picker-bootstrap5/gijgo.min.js') }}"></script>
    <script src="{{ asset('vendor/moment/moment.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Datepickers
            const today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
            $('#startDate').datepicker({
                uiLibrary: 'bootstrap5',
                maxDate: function() {
                    return $('#endDate').val();
                }
            });
            
            $('#endDate').datepicker({
                uiLibrary: 'bootstrap5',
                minDate: function() {
                    return $('#startDate').val();
                }
            });

            const today3 = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
            $('#startDate3').datepicker({
                uiLibrary: 'bootstrap5',
                maxDate: function() {
                    return $('#endDate3').val();
                }
            });

            $('#endDate3').datepicker({
                uiLibrary: 'bootstrap5',
                minDate: function() {
                    return $('#startDate3').val();
                }
            });

            // Alertas
            @if (!empty($message_error))
                Swal.fire({
                    icon: 'warning',
                    title: 'Ups..',
                    html: '{!! $message_error !!}</br>Contacte al administrador',
                    showConfirmButton: true,
                    buttonsStyling: false,
                    showCloseButton: true,
                    customClass: {
                        confirmButton: 'btn btn-primary mb-1',
                    }
                });
            @endif

            // Inicialización de la gráfica de barras
            const chartDom = document.getElementById('grafico_bar');
            if (chartDom) {
                const myChart = echarts.init(chartDom);

                // Datos para la primera gráfica
                const meses = [
                    @foreach ($meses as $item)
                        '{{ $item }}',
                    @endforeach
                ];
                
                const data = [
                    @foreach ($data as $item)
                        '{{ $item }}',
                    @endforeach
                ];

                // Configuración de la gráfica de barras
                let option = {
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: {
                            type: 'shadow'
                        },
                        formatter: function(params) {
                            // Verificar el tipo de dato para mostrar o no el signo $
                            const dataType = $('#data_type').val();
                            if (dataType === '1') {
                                // Para cantidad (sin signo $)
                                return params[0].name + ': ' + params[0].value;
                            } else {
                                // Para monto (con signo $)
                                return params[0].name + ': $' + params[0].value;
                            }
                        },
                        backgroundColor: 'rgba(255, 255, 255, 0.9)',
                        borderColor: '#ccc',
                        borderWidth: 1,
                        padding: 10,
                        textStyle: {
                            color: '#333',
                            fontSize: 14
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
                        type: 'value',
                        name: 'Monto'
                    }],
                    series: [{
                        name: '{{ $leyenda }}',
                        type: 'bar',
                        barWidth: '60%',
                        itemStyle: {
                            color: function(params) {
                                const colors = ['#4687e6', '#13c0e6', '#8ce04f', '#FFA500'];
                                return colors[params.dataIndex % colors.length];
                            },
                            borderRadius: 4
                        },
                        data: data.length > 0 ? data : [0]
                    }]
                };

                // Inicializar la gráfica de barras
                myChart.setOption(option);
                
                // Manejador de redimensionamiento
                window.addEventListener('resize', function() {
                    myChart.resize();
                });

                // Inicialización de la gráfica de torta
                const dom2 = document.getElementById('grafico_pie');
                if (dom2) {
                    const myChart2 = echarts.init(dom2);

                    // Preparar datos para la gráfica de torta
                    const chartData = [];
                    const colors = ['#4687e6', '#13c0e6', '#8ce04f', '#FFA500', '#C0C0C0', '#FF6B6B', '#9775FA', '#63E6BE', '#74C0FC', '#B197FC'];
                    
                    @foreach ($data_torta as $index => $item)
                        chartData.push({
                            value: {{ $item->value }},
                            name: '{{ $item->name }}',
                            itemStyle: {
                                color: colors[{{ $index }} % colors.length]
                            }
                        });
                    @endforeach
                    
                    if (chartData.length === 0) {
                        chartData.push({
                            value: 100,
                            name: 'Sin datos',
                            itemStyle: { color: '#C0C0C0' }
                        });
                    }

                    // Configuración de la gráfica de torta
                    let option2 = {
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
                        series: [{
                            name: 'Ventas de Mi equipo',
                            type: 'pie',
                            radius: ['40%', '70%'],
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
                            data: chartData
                        }]
                    };

                    // Inicializar la gráfica de torta
                    myChart2.setOption(option2);
                    
                    // Agregar animación avanzada para cambios entre elementos
                    document.getElementById('grafico_pie').addEventListener('mousedown', function() {
                        const tooltipEl = document.querySelector('.echarts-tooltip');
                        if (tooltipEl) {
                            tooltipEl.style.transition = 'all 0.4s cubic-bezier(0.23, 1, 0.32, 1)';
                            tooltipEl.style.opacity = '0';
                            setTimeout(() => {
                                tooltipEl.style.opacity = '1';
                            }, 50);
                        }
                    });
                    
                    // Manejador de redimensionamiento
                    window.addEventListener('resize', function() {
                        myChart2.resize();
                    });
                    
                    // AJAX para la gráfica de torta
                    $('#submitPie').click(function(e){
                        e.preventDefault();
                        let startDate = $('#startDate3').val();            
                        let endDate = $('#endDate3').val();
                        
                        $.ajax({
                            url: "{{ route('report.pie')}}",
                            type: 'POST',
                            dataType: "json",
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            },
                            data: {
                                'startDate': startDate,
                                'endDate': endDate
                            },
                            success: function(data) {
                                let fechaInicio = startDate;
                                let fechaFin = endDate;
                                
                                if(startDate =="" && endDate !=""){
                                    fechaFin = endDate;
                                    let hoy = moment(endDate);
                                    fechaInicio = hoy.subtract(1, 'year')
                                    fechaInicio = fechaInicio.format('MM/DD/YYYY');
                                    $('#startDate3').val(fechaInicio);
                                } else if(startDate !="" && endDate ==""){
                                    fechaInicio = startDate;
                                    let hoy = moment(startDate);
                                    fechaFin = hoy.add(1, 'year')
                                    fechaFin = fechaFin.format('MM/DD/YYYY');
                                    $('#endDate3').val(fechaFin);
                                }
                                
                                // Actualizar leyenda y datos
                                if(data.data_torta.length > 0){
                                    if(startDate =="" && endDate =="")
                                        data.leyenda_torta = `${data.leyenda_torta} últimos 3 meses`;
                                    else
                                        data.leyenda_torta = `${data.leyenda_torta} desde ${fechaInicio} hasta ${fechaFin}`;
                                }
                                
                                $('#titulo_torta').text(data.leyenda_torta);
                                
                                // Preparar datos para la gráfica actualizada
                                const chartData = [];
                                const colors = ['#4687e6', '#13c0e6', '#8ce04f', '#FFA500', '#C0C0C0', '#FF6B6B', '#9775FA', '#63E6BE', '#74C0FC', '#B197FC'];
                                
                                if (data.data_torta && data.data_torta.length > 0) {
                                    data.data_torta.forEach((item, index) => {
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
                                
                                // Actualizar la configuración con los nuevos datos
                                option2.series[0].data = chartData;
                                myChart2.setOption(option2);
                                
                                // Actualizar la lista de identificadores
                                const teamList = document.getElementById('team-list');
                                if (teamList) {
                                    let listHtml = '';
                                    
                                    if (data.data_torta && data.data_torta.length > 0) {
                                        data.data_torta.forEach((item, index) => {
                                            const colorIndex = index % colors.length;
                                            listHtml += `
                                            <li class="team-item">
                                                <span class="team-color" style="background-color: ${colors[colorIndex]};"></span>
                                                <span class="team-name">${item.name}</span>
                                            </li>`;
                                        });
                                    } else {
                                        listHtml += `
                                        <li class="team-item">
                                            <span class="team-color" style="background-color: #C0C0C0;"></span>
                                            <span class="team-name">Sin datos disponibles</span>
                                        </li>`;
                                    }
                                    
                                    teamList.innerHTML = listHtml;
                                }
                            },
                            error: function(xhr, status) {
                                console.error('Error en la solicitud AJAX:', status);
                                alert('Error al cargar los datos. Por favor, inténtelo de nuevo.');
                            }
                        });
                    });
                }
                
                // Manejar el redimensionamiento
                window.addEventListener('resize', function() {
                    myChart.resize();
                    myChart2.resize();
                });
                
                // Evento change para el selector de tipo de datos
                $('#data_type').on('change', function() {
                    $('#submitBar').click();
                });
                
                // AJAX para la gráfica de barras
                $('#submitBar').click(function(e) {
                    e.preventDefault();
                    let startDate = $('#startDate').val();            
                    let endDate = $('#endDate').val();
                    let dataType = $('#data_type').val();
                    
                    console.log('Enviando petición con tipo:', dataType);
                    
                    $.ajax({
                        url: "{{ route('report.bar')}}",
                        type: 'POST',
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        data: {
                            'startDate': startDate,
                            'endDate': endDate,
                            'type': dataType
                        },
                        success: function(data) {
                            console.log('Respuesta recibida:', data);
                            
                            // Actualizar la configuración con los nuevos datos
                            option.xAxis[0].data = data.meses.length > 0 ? data.meses : ['Sin datos'];
                            option.series[0].data = data.data.length > 0 ? data.data : [0];
                            option.series[0].name = data.leyenda;
                            
                            // Actualizar el nombre del eje Y según el tipo de datos
                            option.yAxis[0].name = dataType == "1" ? 'Cantidad' : 'Monto';
                            
                            // Actualizar el formatter del tooltip según el tipo de datos
                            option.tooltip.formatter = function(params) {
                                if (dataType === '1') {
                                    // Para cantidad (sin signo $)
                                    return params[0].name + ': ' + params[0].value;
                                } else {
                                    // Para monto (con signo $)
                                    return params[0].name + ': $' + params[0].value;
                                }
                            };
                            
                            myChart.setOption(option);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error en la solicitud AJAX:', status, error);
                            console.log('Respuesta de error:', xhr.responseText);
                            alert('Error al cargar los datos. Por favor, inténtelo de nuevo.');
                        }
                    });
                });
                
                // Botón de refrescar para la gráfica de barras
                $('#refreshBar').click(function() {
                    $('#startDate').val('');
                    $('#endDate').val('');
                    $('#submitBar').click();
                });
                
                // Botón de refrescar para la gráfica de torta
                $('#refreshPie').click(function() {
                    $('#startDate3').val('');
                    $('#endDate3').val('');
                    $('#submitPie').click();
                });
            }
        });
    </script>
@endpush
