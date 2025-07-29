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

        .margin-input{
            margin-bottom: 1em;
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
        
        /* Ampliar específicamente el select de analistas */
        #select_analyst {
            min-width: 180px;
            width: auto !important;
        }
        
        /* Asegurar que los selects siempre estén por encima de otros elementos */
        .form-select {
            position: relative;
            z-index: 100;
        }
        
        /* Asegurar que el datepicker quede por debajo de los dropdowns cuando estos están abiertos */
        .gj-datepicker-bootstrap {
            z-index: 50;
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
                <!-- Aplicaciones Instaladas Por Analistas -->
                <div class="col-12 fade-in">
                    <div class="dashboard-card">
                        <div class="card-header-custom">
                            <div>
                                <h5 class="card-title">Aplicaciones Instaladas Por Analistas</h5>
                                <p class="card-subtitle" id="divFechaInstalled">Desde {{$startDate}} Hasta {{$endDate}}</p>
                            </div>
                            <a href="{{route('report.application')}}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-sync-alt"></i>
                            </a>
                        </div>
                        
                        <form id="formInstalled" class="needs-validation mb-3" method="" action="" novalidate>
                            @csrf
                            <div class="row g-3 align-items-center d-flex align-items-stretch">    
                                <div class="col-12 col-md-auto d-flex align-items-center justify-content-center">
                                    <label for="startDate" class="form-label mb-0">@lang('translation.start_date_form')</label>
                                </div>                        
                                <div class="col-12 col-md-auto d-flex align-items-center justify-content-center">
                                    <input id="startDate" name="startDate" type="text" class="form-control campo-date"
                                        placeholder="mm/dd/yyyy" aria-label="startDate" value="" required data-unique="nombre-campo" autocomplete="off" readonly>
                                </div>
                                <div class="col-12 col-md-auto d-flex align-items-center justify-content-center">
                                    <label for="endDate" class="form-label mb-0">@lang('translation.end_date_form')</label>
                                </div>
                                <div class="col-12 col-md-auto d-flex align-items-center justify-content-center">
                                    <input id="endDate" type="text" name="endDate" class="form-control campo-date"
                                        placeholder="mm/dd/yyyy" aria-label="endDate" value="" required data-unique="nombre-campo3" autocomplete="off" readonly>
                                </div>
                            </div>
                            <div class="row g-3 mt-2">
                                <div class="col-auto d-flex align-items-center mb-2 mb-md-0">
                                    <select id="select_analyst" name="select_analyst" class="form-select">
                                        <option value="0">Todos los Analistas</option>
                                        @if(isset($instalaciones) && !empty($instalaciones))                                                 
                                                @foreach($instalaciones as $analyst)
                                                    <option value="{{$analyst->co_usuario}}"> 
                                                        {{$analyst->analista}}
                                                    </option>    
                                                @endforeach                                            
                                        @endif        
                                    </select>
                                </div>
                                <div class="col-auto d-flex align-items-center">
                                    <button id="btnInstalled" class="btn btn-primary" title="Buscar" type="button">Buscar</button>
                                </div>
                                <div class="col-auto d-flex align-items-center">
                                    <input type="button" id="btndownloadInstalled" class="btn btn-primary" value="Descargar">
                                </div>   
                            </div>
                        </form>
                        
                        @php
                            // Definimos los encabezados para la tabla
                            $headers = [
                                ['title' => 'Analista', 'class' => 'col-3'],
                                ['title' => 'Oficina', 'class' => 'col-2'],
                                ['title' => 'Código Aplicaciones', 'class' => 'col-2'],
                                ['title' => 'Aplicaciones Instaladas', 'class' => 'col-2'],
                                ['title' => 'Monto', 'class' => 'col-3']
                            ];
                            
                            $emptyMessage = count($instalaciones) > 0 ? 'No hay datos disponibles' : 'No existen instalaciones en el período seleccionado';
                        @endphp
                        
                        <div style="max-height: 450px; overflow-y: auto;">
                            <x-data-table 
                                :headers="$headers" 
                                :data="$instalaciones" 
                                :perPage="count($instalaciones)"
                                :currentPage="1" 
                                :totalItems="count($instalaciones)"
                                tableId="instalaciones-table"
                                :showPagination="false"
                                :showPerPageSelector="false"
                                :emptyMessage="$emptyMessage">
                                
                                @php
                                    $totalVentas = 0;
                                    $totalMonto = 0;
                                @endphp
                                
                                @foreach($instalaciones as $item)
                                    @php
                                        $totalVentas += $item->ventas;
                                        $totalMonto += $item->monto;
                                    @endphp
                                    <tr class="text-center">
                                        <td>{{ $item->analista }}</td>
                                        <td>{{ $item->oficina }}</td>
                                        <td>{{ $item->co_aplicaciones }}</td>
                                        <td>{{ $item->ventas }}</td>
                                        <td>{{ Number::format($item->monto) }}</td>
                                    </tr>
                                @endforeach
                                
                                @if(count($instalaciones) > 0)
                                    <tr class="text-center table-primary">
                                        <td></td>
                                        <td></td>
                                        <td><strong>Total</strong></td>
                                        <td><strong>{{ $totalVentas }}</strong></td>
                                        <td><strong>{{ Number::format($totalMonto) }}</strong></td>
                                    </tr>
                                @endif
                            </x-data-table>
                        </div>
                    </div>
                </div>

                <!-- Aplicaciones Instaladas Por Mes -->
                <div class="col-12 fade-in">
                    <div class="dashboard-card">
                        <div class="card-header-custom">
                            <div>
                                <h5 class="card-title">Aplicaciones Instaladas Por Mes</h5>                    
                                <p class="card-subtitle" id="divFechaApplicaton">Desde {{$startDateApp}} Hasta {{$endDateApp}}</p>
                            </div>
                        </div>
                        
                        <form class="needs-validation mb-3" id="formApplication" method="" action="">
                            @csrf
                            <div class="row g-3 align-items-center d-flex align-items-stretch">    
                                <div class="col-12 col-md-auto d-flex align-items-center justify-content-center">
                                    <label for="startDate2" class="form-label mb-0">@lang('translation.start_date_form')</label>
                                </div>                        
                                <div class="col-12 col-md-auto d-flex align-items-center justify-content-center">
                                    <input id="startDate2" name="startDate" type="text" class="form-control campo-date"
                                       placeholder="mm/dd/yyyy" aria-label="startDate" data-unique="nombre-campo4" autocomplete="off" readonly>
                                </div>
                                <div class="col-12 col-md-auto d-flex align-items-center justify-content-center">
                                    <label for="endDate2" class="form-label mb-0">@lang('translation.end_date_form')</label>
                                </div>
                                <div class="col-12 col-md-auto d-flex align-items-center justify-content-center">
                                    <input id="endDate2" type="text" name="endDate" class="form-control campo-date"
                                    placeholder="mm/dd/yyyy" aria-label="endDate" data-unique="nombre-campo5" autocomplete="off" readonly>
                                </div>
                            </div>
                            <div class="row g-3 mt-2">
                                <div class="col-auto d-flex align-items-center mb-2 mb-md-0">
                                    <select id="select_type" name="select_type" class="form-select">
                                        <option value="1" selected>Ventas</option>
                                        <option value="2" >Monto</option>                                
                                    </select>  
                                </div>
                                <div class="col-auto d-flex align-items-center">
                                    <button id="submitBar" class="btn btn-primary" title="Buscar" type="submit">Buscar</button>
                                </div>
                                <div class="col-auto d-flex align-items-center">
                                    <input type="button" id="btndownloadApplication" class="btn btn-primary" value="Descargar">
                                </div>  
                            </div>
                        </form>
                        
                        <div class="row">
                            <div class="col">
                                <div id="grafico_bar" style="width: 100%; height: 400px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
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
        //grafica bar
        let app = {};

        const chartDom = document.getElementById('grafico_bar');
        const myChart = echarts.init(chartDom);

        let option;

        option = {
            baseOption:{ 
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'cross',
                    crossStyle: {
                        color: '#999'
                    }
                }
            },
            toolbox: {
                feature: {
                    magicType: {
                        show: true,
                        type: ['line', 'bar']
                    },
                    restore: {
                        show: true
                    },
                    saveAsImage: {
                        show: true
                    }
                }
            },
            legend: {
                top: 10,
                orient: 'horizontal',
                left: 'center',
                data: [] /* '{{ $leyenda }}' */
            },
            xAxis: [{
                type: 'category',
                data: [
                    @foreach ($meses as $item)
                        '{{ $item }}',
                    @endforeach
                ],
                axisPointer: {
                    type: 'shadow'
                }
            }],
            yAxis: [{
                type: 'value',
                name: @if($type == "1" || $type=="") 'Cantidad' @else 'Monto' @endif,
            }],
            series: [
                {
                    name: '{{ $leyenda }}',
                    type: 'bar',
                    tooltip: {
                        valueFormatter: function(value) {
                            @if($type == "1"  || $type == "")
                                return '#' + value;
                            @elseif($type == "2" )  
                                return '$' + value;   
                            @endif    
                        }
                    },
                    data: [
                        @foreach ($data as $item)
                            '{{ $item }}',
                        @endforeach
                    ],
                    itemStyle: {
                                            color: function(params) {
                                                const colors = ['#4687e6', '#13c0e6', '#8ce04f', '#FFA500'];
                                                return colors[params.dataIndex % colors.length];
                                            },
                                            borderRadius: 4
                                        },
                }

            ]
            },
            media:[
                {
                query:
                {
                    maxWidth: 541
                },
                option:
                {
                    tooltip: {
                        confine: true, 
                        trigger: 'axis',
                        textStyle: {
                            fontSize: 10,
                            maxWidth: 150
                        },
                        position: function (point, params, dom, rect, size) {
                            return [point[0], point[1] - 10];
                        }
                    },
                    toolbox: {
                      feature: {
                            magicType: {
                                show: true,
                                type: ['line','bar']
                        },
                        restore: {
                            show: false
                        },
                        saveAsImage: {
                            show: true
                        }
                    }
                    },
                    xAxis: {
                        axisLabel: {
                            rotate: 45, 
                            fontSize: 10,
                            interval: 0
                        }
                    },

                },                
            },
            {
                query:
                {
                    maxWidth: 376
                },
                option:
                {
                    tooltip: {
                        confine: true, 
                        trigger: 'axis',
                        textStyle: {
                            fontSize: 10,
                            maxWidth: 150
                        },
                        position: function (point, params, dom, rect, size) {
                            return [point[0], point[1] - 10];
                        }
                    },
                    toolbox: {
                      feature: {
                            magicType: {
                                show: true,
                                type: ['bar']
                        },
                        restore: {
                            show: false
                        },
                        saveAsImage: {
                            show: true
                        }
                    }
                    },
                    xAxis: {
                        axisLabel: {
                            rotate: 45, 
                            fontSize: 10,
                            interval: 0
                        }
                    },

                },                
            },
            ]
           
        };
        // Escucha el evento de cambio de tamaño de la ventana
        window.addEventListener('resize', () => {
            // Actualiza la opción de la gráfica con los nuevos valores
            myChart.setOption(option);
            window.addEventListener('resize', myChart.resize);
        });

        option && myChart.setOption(option);
    </script>
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
            
            $('#startDate2').datepicker({
                uiLibrary: 'bootstrap5',
                maxDate: function() {
                    return $('#endDate2').val();
                }
            });
            
            $('#endDate2').datepicker({
                uiLibrary: 'bootstrap5',
                minDate: function() {
                    return $('#startDate2').val();
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

            // Inicialización de la gráfica de barras si existe
            const chartDom = document.getElementById('grafico_bar');
            if (chartDom) {
                const myChart = echarts.init(chartDom);
                
                // Manejador de redimensionamiento
                window.addEventListener('resize', function() {
                    myChart.resize();
                });

                function updateBar()
                {
                        
                        let startDate = $('#startDate2').val();            
                        let endDate = $('#endDate2').val();
                        let dataType = $('#select_type').val();
                        
                        $.ajax({
                            url: "{{ route('report.application.bar') }}",
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
                                // Actualizar fecha                                
                                if(data.startDate || data.endDate){
                                    $('#divFechaApplicaton').text('Desde ' + data.startDate + ' Hasta ' + data.endDate);
                                    $('#startDate2').val(data.startDate);            
                                    $('#endDate2').val(data.endDate);            
                                }
                                
                                // Configuración de la gráfica
                                let option = {
                                    tooltip: {
                                        trigger: 'axis',
                                        axisPointer: {
                                            type: 'shadow'
                                        },
                                        formatter: function(params) {
                                            if (dataType === '1') {
                                                return params[0].name + ': ' + params[0].value;
                                            } else {
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
                                        data: data.meses.length > 0 ? data.meses : ['Sin datos'],
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
                                                return value.length > 8 ? value.substring(0, 7) + '...' : value;
                                            }
                                        }
                                    }],
                                    yAxis: [{
                                        type: 'value',
                                        name: dataType === '1' ? 'Cantidad' : 'Monto'
                                    }],
                                    series: [{
                                        name: 'Aplicaciones por mes',
                                        type: 'bar',
                                        barWidth: '60%',
                                        itemStyle: {
                                            color: function(params) {
                                                const colors = ['#4687e6', '#13c0e6', '#8ce04f', '#FFA500'];
                                                return colors[params.dataIndex % colors.length];
                                            },
                                            borderRadius: 4
                                        },
                                        data: data.data.length > 0 ? data.data : [0]
                                    }]
                                };
                                
                                myChart.setOption(option);
                            },
                            error: function(xhr, status, error) {
                                console.error('Error en la solicitud AJAX:', status, error);
                                alert('Error al cargar los datos. Por favor, inténtelo de nuevo.');
                            }
                        });
                                
                }
                
                // Manejador para el botón de búsqueda
                $('#submitBar').click(function(e) {
                    e.preventDefault();
                    //if ($('#formApplication').valid()) {
                        let startDate = $('#startDate2').val();            
                        let endDate = $('#endDate2').val();
                        let dataType = $('#select_type').val();
                        
                        $.ajax({
                            url: "{{ route('report.application.bar') }}",
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
                                // Actualizar fecha
                                if(data.startDate || data.endDate){
                                    $('#divFechaApplicaton').text('Desde ' + data.startDate + ' Hasta ' + data.endDate);
                                    $('#startDate2').val(data.startDate);            
                                    $('#endDate2').val(data.endDate);            
                                }
                                
                                // Configuración de la gráfica
                                let option = {
                                    tooltip: {
                                        trigger: 'axis',
                                        axisPointer: {
                                            type: 'shadow'
                                        },
                                        formatter: function(params) {
                                            if (dataType === '1') {
                                                return params[0].name + ': ' + params[0].value;
                                            } else {
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
                                        data: data.meses.length > 0 ? data.meses : ['Sin datos'],
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
                                                return value.length > 8 ? value.substring(0, 7) + '...' : value;
                                            }
                                        }
                                    }],
                                    yAxis: [{
                                        type: 'value',
                                        name: dataType === '1' ? 'Cantidad' : 'Monto'
                                    }],
                                    series: [{
                                        name: 'Aplicaciones por mes',
                                        type: 'bar',
                                        barWidth: '60%',
                                        itemStyle: {
                                            color: function(params) {
                                                const colors = ['#4687e6', '#13c0e6', '#8ce04f', '#FFA500'];
                                                return colors[params.dataIndex % colors.length];
                                            },
                                            borderRadius: 4
                                        },
                                        data: data.data.length > 0 ? data.data : [0]
                                    }]
                                };
                                
                                myChart.setOption(option);
                            },
                            error: function(xhr, status, error) {
                                console.error('Error en la solicitud AJAX:', status, error);
                                alert('Error al cargar los datos. Por favor, inténtelo de nuevo.');
                            }
                        });
                    //}
                });

                
                
                // Manejador para la primera tabla
                $('#btnInstalled').click(function(e) {
                    e.preventDefault();
                    let startDate = $('#startDate').val();            
                    let endDate = $('#endDate').val();
                    let codeAnalyst = $('#select_analyst').val();                    
                    $.ajax({
                        url: "{{ route('report.installed.app') }}",
                        type: 'POST',
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        data: {
                            'startDate': startDate,
                            'endDate': endDate,
                            'code_analyst': codeAnalyst
                        },
                        success: function(data) {
                            // Actualizar fecha                            
                            $('#divFechaInstalled').text('Desde ' + data.startDate + ' Hasta ' + data.endDate);
                            $('#startDate').val(data.startDate);
                            $('#endDate').val(data.endDate);
                            // Ocultar tabla actual y mostrar la nueva con los datos
                            if (data.data && data.data.length > 0) {                                                                
                                // Bloquear tabla durante la actualización
                                blockTable('instalaciones-tableLoading');
                                
                                setTimeout(function() {
                                    // Reconstruir la tabla con los nuevos datos
                                    let tableBody = $('#instalaciones-tableLoading table tbody');
                                    tableBody.empty();
                                    
                                    let totalVentas = 0;
                                    let totalMonto = 0;
                                    
                                    // Agregar filas con datos
                                    $.each(data.data, function(index, item) {
                                        totalVentas += parseInt(item.ventas);
                                        totalMonto += parseFloat(item.monto);
                                        
                                        let row = `<tr class="text-center">
                                            <td>${item.analista}</td>
                                            <td>${item.oficina}</td>
                                            <td>${item.co_aplicaciones}</td>
                                            <td>${item.ventas}</td>
                                            <td>${new Intl.NumberFormat('en-US').format(item.monto)}</td>
                                        </tr>`;                                        
                                        tableBody.append(row);
                                    });
                                    
                                    // Agregar fila de totales
                                    let totalRow = `<tr class="text-center table-primary">
                                        <td></td>
                                        <td></td>
                                        <td><strong>Total</strong></td>
                                        <td><strong>${totalVentas}</strong></td>
                                        <td><strong>${new Intl.NumberFormat('en-US').format(totalMonto)}</strong></td>
                                    </tr>`;
                                    
                                    tableBody.append(totalRow);
                                    
                                    // Desbloquear tabla
                                    unblockTable('instalaciones-table');
                                    let selectAnalyst = $('#select_analyst');
                                    const oldAnalyst = selectAnalyst.val();
                                    selectAnalyst.empty();
                                    let option =`
                                        <option value= "0">
                                            Todos los Analistas
                                        </option>`;         
                                    selectAnalyst.append(option);
                                    $.each(data.data, function(index, item) {
                                        
                                        option =`
                                            <option value= "${item.co_usuario}" ${oldAnalyst && item.co_usuario == oldAnalyst ? 'selected' : ''}>
                                                ${item.analista}
                                            </option>`;                                           
                                        selectAnalyst.append(option);
                                    });
                                }, 300);
                            } else {
                                // Si no hay datos, mostrar mensaje
                                blockTable('instalaciones-table');
                                
                                setTimeout(function() {
                                    let tableBody = $('#instalaciones-table table tbody');
                                    tableBody.html(`<tr><td colspan="5" class="text-center">No existen instalaciones en el período seleccionado</td></tr>`);
                                    unblockTable('instalaciones-table');
                                }, 300);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error en la solicitud AJAX:', status, error);
                            alert('Error al cargar los datos. Por favor, inténtelo de nuevo.');
                        }
                    });
                });
                
                // Manejadores para botones de descarga
                $('#btndownloadInstalled').click(function() {
                    let startDate = $('#startDate').val();            
                    let endDate = $('#endDate').val();
                    let codeAnalyst = $('#select_analyst').val();
                    
                    window.location.href = "{{ route('report.installed.export') }}" +
                        "?startDate=" + startDate + 
                        "&endDate=" + endDate + 
                        "&code_analyst=" + codeAnalyst;
                });
                
                $('#btndownloadApplication').click(function() {
                    let startDate = $('#startDate2').val();            
                    let endDate = $('#endDate2').val();
                    let dataType = $('#select_type').val();
                    
                    window.location.href = "{{ route('report.application.export') }}" +
                        "?startDate=" + startDate + 
                        "&endDate=" + endDate + 
                        "&type=" + dataType;
                });
            }
            const selectType = document.getElementById('select_type');
       
            selectType.addEventListener('change', (e) => {
                e.preventDefault();
                updateBar();
            });
        });
    </script>
@endpush
