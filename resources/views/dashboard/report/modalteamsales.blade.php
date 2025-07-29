@push('css')
    <link href="{{ asset('vendor/data-tables-bootstrap5/datatables.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/data-picker-bootstrap5/gijgo.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('vendor/apexcharts-bundle/apexcharts.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .margin-select-1 {
            margin-top: 4px !important;
        }
        .margin-select option:hover {
             background-color: blue;
        }

        .margin-button-now{
            margin-top: 46px !important;
        }

        legend.background{
            background-color: white;
        }

    </style>
@endpush

<div class="modal fade" id="modalTeamTable" tabindex="-1" aria-labelledby="ModalTableLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title">Ventas de mi Equipo</h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
                {{-- Contenido del modal --}}
                <div class="container-fluid">
                  <div class="row">
                    {{-- colocamos el formulario --}}
                    <div class="col-12">
                       <form id="formTeamVentas" class="needs-validation" method="" action="">
                          @csrf
                          <fieldset class="scheduler-border">
                            <legend class="scheduler-border background">@lang('translation.search_form')</legend>
                          <div class="row row row-col-md-auto g-md-3 align-items-center">
                              <div class="col-12 col-md-auto">
                                <label for="colFormLabelS" class="form-label">@lang('translation.start_date_form')</label>
                                  <input id="date3" name="date1" type="text" class="form-control campo-date"
                                      aria-label="date"
                                      placeholder="mm/dd/yyyy"
                                      required />
                              
                              </div>
                              <div class="col-12 col-md-auto">
                                <label for="colFormLabelS" class="form-label">@lang('translation.end_date_form')</label>
                                  <input id="date4" type="text" name="date2" class="form-control campo-date"
                                      aria-label="date"
                                      placeholder="mm/dd/yyyy"
                                      required>
                              </div>
                              <div class="col-12 col-md-auto margin-select-1">
                                <label for="select_order" class="form-label ">@lang('translation.order_by_date')</label>
                                <select id="select_order" name="select_order" class="form-select" aria-label="Select">
                                    <option value="1" selected>Fecha de creación</option>
                                    <option value="2">Ciudad</option>
                                    <option value="3">Estado</option>
                                </select>
                               </div>                                 
                              <div class="col-12 col-md-auto button margin-button-now">
                                  <button id="btnTeamBuscarVentas" class="btn btn-primary btnRango" title="Buscar" type="submit">Buscar</button>&nbsp; 
                                  <input type="button" id="btndownloadTeamVentas" class="btn btn-primary btnRango" value="Descargar">
                              </div>
                            </div>
                          </fieldset>
                        </form>
                   </div>
                    </div> 
                </div>   
                <br />  
                <div class="row">
                   {{-- <div class="col-12 col-md-auto"> 
                        <h3 class="titulo_page mt-4">Total Ventas:</h3>
                    </div>
                    <div class="col-12 col-md-auto">    
                      <h3 id="h3totalTeamVentas" class="titulo_page mt-4"></h3>
                    </div> --}}
                    <div class="d-flex align-items-center">  <!-- Agregamos d-flex y align-items-center -->
                        <h5 class="mt-4 me-2 fw-bold">Total Ventas:</h5>  <!-- Agregamos me-2 para margen derecho -->
                        <h5 id="h3totalTeamVentas" class="mt-4 fw-bold"></h5>
                    </div>
                </div>
                   <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                            <table
                                id="table" 
                                class="table rounded-3 overflow-hidden table-hover table-bordered align-middle"
                                data-toggle="table"
                                data-height="345">
                            <thead>
                                <tr class="table-primary custom-row align-middle text-center">
                                    <th class="col-1">
                                        <div class="row">
                                            <div class="col-12">ID</div> 
                                        </div>
                                        <div class="row">
                                            <div class="col-12">Nombre</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">Apellido</div>
                                        </div>
                                    </th>
                                    <th class="col-1">Número de Teléfono</th>
                                    <th class="col-1">Agendador</th>
                                    <th class="col-1">Analista</th>
                                    <th class="col-2">
                                        <div class="row">
                                            <div class="col-12">Estado</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">Cuidad</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">Dirección</div>
                                        </div>
                                    </th>
                                    <th class="col-2">Estatus Actual</th>
                                    <th class="col-1">Fecha de Creación</th>          
                                    <th class="col-1">Precio Total</th>
                                </tr>
                            </thead>
                            <tbody id="tablaTeamDatosVentas">
                            </tbody>                  
                            </table>
                            </div>
                        </div>

                    </div>     
                
            </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              </div>
        </div>
    </div>
  </div>


@push('scripts')
 
<script>
    
      $(function(){
          $('#modalTeamTable').on('shown.bs.modal', function () {
                
                $('#date3').datepicker({
                    uiLibrary: 'bootstrap5',
                    maxDate: function() {
                        return $('#date4').val();
                    }
                });
        
                $('#date4').datepicker({
                    uiLibrary: 'bootstrap5',
                    minDate: function() {
                        return $('#date3').val();
                    }
                });

                                
                $.ajaxSetup({
                    headers: {
                      'X-CSRF-TOKEN': "{{ csrf_token() }}" 
                  }
                });
                $.ajax({
                  url:  "{{ route('report.teamsales')}}", 
                  type: 'GET',
                  dataType: "json",
                  data: {},
                  success: function(data) {
                    let tbodyDatosVentas = document.getElementById('tablaTeamDatosVentas');
                    tbodyDatosVentas.innerHTML = "";
                    document.getElementById('h3totalTeamVentas').innerHTML = data.data[0].total_periodo;
                    //console.log(data.consulta);
                    data.data.forEach((item, index)=>{
                          const str = item.fe_activacion_estatus_mas_reciente;
                            let fecha_rf2 = "---";
                            if (str !== null && str !== "" ){
                                const fecha = str.substring(0,10);
                                fecha_rf2 = moment(fecha).format("MM/DD/YYYY");                               
                            }

                            const str2 = item.fe_creacion;
                            const fecha2 = str2.substring(0,10);
                            const fechaf = moment(fecha2).format("MM/DD/YYYY"); 

                            let estatus_mas_reciente = "---";
                            if (item.estatus_mas_reciente !== null && item.estatus_mas_reciente !== "" ){ 
                                estatus_mas_reciente = item.estatus_mas_reciente;
                            }

                            let estado_app = "";
                            if (item.tx_estado !== null && item.tx_estado !== "" ){
                                estado_app = item.tx_estado;
                            }

                            let ciudad_app = "";
                            if (item.tx_ciudad !== null && item.tx_ciudad !== "" ){
                                ciudad_app = item.tx_ciudad;
                            }

                            let direccion_app = "";
                            if (item.tx_direccion1 !== null && item.tx_direccion1 !== "" ){
                                direccion_app = item.tx_direccion1;
                            }

                            let direccion_app2 = "";
                            if (item.tx_direccion2 !== null && item.tx_direccion2 !== "" ){
                                direccion_app2 = item.tx_direccion2;
                            } 

                            let filaHTM = '<tr class="text-center">';
                            
                            filaHTM+=`<td class="table-secondary">`;
                            filaHTM+=`<div class="row">`;
                            filaHTM+=`<div class="col-12">${item.co_aplicacion}</div>`;
                            filaHTM+=`</div>`;
                            filaHTM+=`<div class="row">`;        
                            filaHTM+=`<div class="col-12">${item.tx_primer_nombre}</div>`;
                            filaHTM+=`</div>`;                                    
                            filaHTM+=`<div class="row">`;
                            filaHTM+=`<div class="col-12">${item.tx_primer_apellido}</div>`;
                            filaHTM+=`</div>`;
                            filaHTM+=`</td>`;
                            filaHTM+=`<td>${item.tx_telefono}</td>`;
                            if(item.settername == null || item.settername=='')
                                item.settername ='';
                            filaHTM+=`<td>${item.settername}</td>`;
                            filaHTM+=`<td>${item.ownername}</td>`;
                            filaHTM+=`<td class="">`;
                            filaHTM+=`<div class="row">`;
                            filaHTM+=`<div class="col-12">${estado_app}</div>`;
                            filaHTM+=`</div>`;
                            filaHTM+=`<div class="row">`
                            filaHTM+=`<div class="col-12">${ciudad_app}</div>`;
                            filaHTM+=`</div>`;
                            filaHTM+=`<div class="row">`;
                            filaHTM+=`<div class="col-12">${direccion_app}, ${direccion_app2}</div>`;
                            filaHTM+=`</div>`;
                            filaHTM+=`</td>`;
                            filaHTM+=`<td class="table-success">`;
                            filaHTM+=`<div class="row">`;
                            filaHTM+=`<div class="col-12 text-center">${fecha_rf2}</div>`;
                            filaHTM+=`</div>`;
                            filaHTM+=`<div class="row">`;
                            filaHTM+=`<div class="col-12 text-center">${estatus_mas_reciente}</div>`;
                            filaHTM+=`</div>`;
                            filaHTM+=`<div class="row">`;
                            filaHTM+=`<div class="col-12 text-center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg></div>`;
                            filaHTM+=`</div>`;
                            filaHTM+=`</td>`;
                            filaHTM+=`<td class="table-success">`;
                            filaHTM+=`<div class="row">`;
                            filaHTM+=`<div class="col-12 text-center">${fechaf}</div>`;
                            filaHTM+=`</div>`;
                            filaHTM+=`<div class="row">`;
                            filaHTM+=`<div class="col-12 text-center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg></div>`;
                            filaHTM+=`</div>`;
                            filaHTM+=`</td>`;
                            filaHTM+=`<td class="table-success">`;
                            filaHTM+=`<div class="row">`;
                            filaHTM+=`<div class="col-12 text-center">${item.nu_precio_total}</div>`;
                            filaHTM+=`</div>`;                            
                            filaHTM+=`</td>`;
                            filaHTM+=`</tr>`;                            
                        $(tbodyDatosVentas).append(filaHTM);                                       

                    });
                    
                  },
                  error: function(xhr, status){
                    console.log(xhr);
                    Swal.fire({
                        title: 'Información', 
                        text: xhr.responseJSON.msg, 
                        icon: 'info', 
                        confirmButtonText: 'Aceptar' 
                    });
                  }
                
                });
          });         
          $('#modalTeamTable').on('hidden.bs.modal', function () {
             const formModalTeamTable = this.querySelector('form'); 
             formModalTeamTable.classList.remove('was-validated');
             formModalTeamTable.reset();       
            });

        });
        
</script>

<script>
   
  $(function(){
         
   $('#btnTeamBuscarVentas').click(function () {
    let form1 = document.getElementById('formTeamVentas');
     form1.addEventListener('submit', function(event) {
            if (!form1.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }else{
                event.preventDefault();
            }
            form1.classList.add('was-validated');
            }, false);    

     $.ajaxSetup({
        headers: {
           'X-CSRF-TOKEN': "{{ csrf_token() }}" 
        }
      });
      $.ajax({
        url:  "{{ route('report.teamsales')}}",  
        type: 'GET',
        dataType: "json",
        data: $('#formTeamVentas').serialize(),
        success: function(data) {
                    let tbodyDatosVentas = document.getElementById('tablaTeamDatosVentas');
                    tbodyDatosVentas.innerHTML = "";
                    document.getElementById('h3totalTeamVentas').innerHTML = data.data[0].total_periodo;
                    data.data.forEach((item, index)=>{
                        
                          const str = item.fe_activacion_estatus_mas_reciente;
                            let fecha_rf2 = "---";
                            if (str !== null && str !== "" ){
                                const fecha = str.substring(0,10);
                                fecha_rf2 = moment(fecha).format("MM/DD/YYYY");                               
                            }

                            const str2 = item.fe_creacion;
                            const fecha2 = str2.substring(0,10);
                            const fechaf = moment(fecha2).format("MM/DD/YYYY"); 

                            let estatus_mas_reciente = "---";
                            if (item.estatus_mas_reciente !== null && item.estatus_mas_reciente !== "" ){ 
                                estatus_mas_reciente = item.estatus_mas_reciente;
                            }

                            let estado_app = "";
                            if (item.tx_estado !== null && item.tx_estado !== "" ){
                                estado_app = item.tx_estado;
                            }

                            let ciudad_app = "";
                            if (item.tx_ciudad !== null && item.tx_ciudad !== "" ){
                                ciudad_app = item.tx_ciudad;
                            }

                            let direccion_app = "";
                            if (item.tx_direccion1 !== null && item.tx_direccion1 !== "" ){
                                direccion_app = item.tx_direccion1;
                            }

                            let direccion_app2 = "";
                            if (item.tx_direccion2 !== null && item.tx_direccion2 !== "" ){
                                direccion_app2 = item.tx_direccion2;
                            } 

                            let filaHTM = '<tr class="text-center">';
                            //----------------------
                            filaHTM+=`<td class="table-secondary">`;
                            filaHTM+=`<div class="row">`;
                            filaHTM+=`<div class="col-12">${item.co_aplicacion}</div>`;
                            filaHTM+=`</div>`;
                            filaHTM+=`<div class="row">`;        
                            filaHTM+=`<div class="col-12">${item.tx_primer_nombre}</div>`;
                            filaHTM+=`</div>`;                                    
                            filaHTM+=`<div class="row">`;
                            filaHTM+=`<div class="col-12">${item.tx_primer_apellido}</div>`;
                            filaHTM+=`</div>`;
                            filaHTM+=`</td>`;
                            filaHTM+=`<td>${item.tx_telefono}</td>`;
                            if(item.settername == null)
                                item.settername = '';
                            filaHTM+=`<td>${item.settername}</td>`;
                            filaHTM+=`<td>${item.ownername}</td>`;
                            filaHTM+=`<td class="">`;
                            filaHTM+=`<div class="row">`;
                            filaHTM+=`<div class="col-12">${estado_app}</div>`;
                            filaHTM+=`</div>`;
                            filaHTM+=`<div class="row">`
                            filaHTM+=`<div class="col-12">${ciudad_app}</div>`;
                            filaHTM+=`</div>`;
                            filaHTM+=`<div class="row">`;
                            filaHTM+=`<div class="col-12">${direccion_app}, ${direccion_app2}</div>`;
                            filaHTM+=`</div>`;
                            filaHTM+=`</td>`;
                            filaHTM+=`<td class="table-success">`;
                            filaHTM+=`<div class="row">`;
                            filaHTM+=`<div class="col-12 text-center">${fecha_rf2}</div>`;
                            filaHTM+=`</div>`;
                            filaHTM+=`<div class="row">`;
                            filaHTM+=`<div class="col-12 text-center">${estatus_mas_reciente}</div>`;
                            filaHTM+=`</div>`;
                            filaHTM+=`<div class="row">`;
                            filaHTM+=`<div class="col-12 text-center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg></div>`;
                            filaHTM+=`</div>`;
                            filaHTM+=`</td>`;
                            filaHTM+=`<td class="table-success">`;
                            filaHTM+=`<div class="row">`;
                            filaHTM+=`<div class="col-12 text-center">${fechaf}</div>`;
                            filaHTM+=`</div>`;
                            filaHTM+=`<div class="row">`;
                            filaHTM+=`<div class="col-12 text-center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg></div>`;
                            filaHTM+=`</div>`;
                            filaHTM+=`</td>`;
                            filaHTM+=`<td class="table-success">`;
                            filaHTM+=`<div class="row">`;
                            filaHTM+=`<div class="col-12 text-center">${item.nu_precio_total}</div>`;
                            filaHTM+=`</div>`;                            
                            filaHTM+=`</td>`;
                            filaHTM+=`</tr>`;                            
                        $(tbodyDatosVentas).append(filaHTM);                                       

                   });
                   
                    
                  },
                  error: function(xhr, status){
                    Swal.fire({
                        title: 'Información', 
                        text: 'No tiene ventas para el periodo indicado', 
                        icon: 'info', 
                        confirmButtonText: 'Aceptar' 
                    });
                   
                  }
                
                });
    }); 

  });    
  //
</script>
<script>
    $(function(){
        $("#btndownloadTeamVentas").click(function(event) {
            let swDownload = false;
            const startDate = $("#date3").val();
            const endDate = $("#date4").val();
            let validarStartDate = validarFecha(startDate);

            let validarEndDate = validarFecha(endDate);
            if((startDate =="" && endDate =="") || (validarStartDate == true && validarEndDate==true))
            {
                let form1 = $("#formTeamVentas");
                let oldUrl1 = form1.attr("action");
                let oldUrlMethod1 = form1.attr("method");
                let url1 = "{{route('report.exportsalesteam')}}"; 
                let method1 = "GET"; 
                form1.attr("action", url1);
                form1.attr("method", method1);
                form1.submit();
                form1.attr("action", oldUrl1);
                form1.attr("method", oldUrlMethod1);
            }
            else
            {
                Swal.fire({
                        title: 'Información', // Título de la alerta
                        text: 'Debe ingresar un rango de fecha válido', // Texto de la alerta
                        icon: 'info', // Icono de la alerta (success, error, warning, question)
                        confirmButtonText: 'Aceptar' // Texto del botón de confirmación
                });
                $("#date3").val('');
                $("#date4").val('');
            }

            
        });
    });
    
</script>    
@endpush 