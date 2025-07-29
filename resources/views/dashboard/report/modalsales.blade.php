@push('css')

    <link href="{{ asset('vendor/data-tables-bootstrap5/datatables.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/data-picker-bootstrap5/gijgo.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('vendor/apexcharts-bundle/apexcharts.css') }}" rel="stylesheet" type="text/css" />
    
    <style>
        .margin-select {
            margin-top: 15px !important;
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
      <div class="modal fade" id="modalTable" tabindex="-1" aria-labelledby="ModalTableLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title">Mis Ventas</h4>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                    {{-- Contenido del modal --}}
                    <div class="container-fluid">
                      <div class="row">
                        {{-- colocamos el formulario --}}
                        <div class="col-12">
                            <form id="formVentas" class="needs-validation" method="" action="">
                              @csrf
                              <fieldset class="scheduler-border">
                                <legend class="scheduler-border background">@lang('translation.search_form')</legend>
                                    <div class="row row row-col-md-auto g-md-3 align-items-left">
                                  <div class="col-12 col-md-auto">
                                    <label for="colFormLabelS" class="form-label">@lang('translation.start_date_form')</label>
                                      <input id="date1" name="date1" type="text" class="form-control campo-date"
                                          aria-label="date"
                                          value=""
                                          placeholder="mm/dd/yyyy"
                                          required>                                  
                                  </div>
                                  <div class="col-12 col-md-auto">
                                    <label for="colFormLabelS" class="form-label">@lang('translation.end_date_form')</label>
                                      <input id="date2" type="text" name="date2" class="form-control campo-date"
                                          aria-label="date"
                                          value=""
                                          placeholder="mm/dd/yyyy"
                                          required>
                                  </div>
                                  <div class="col-12 col-md-auto margin-select">
                                    <label for="select_order" class="form-label">@lang('translation.order_by_date')</label>
                                    <select id="select_order" name="select_order" class="form-select" aria-label="Select">
                                        <option value="1" selected>Fecha de creación</option>
                                        <option value="2">Ciudad</option>
                                        <option value="3">Estado</option>
                                    </select>
                                   </div> 
                                   <div class="col-12 col-md-auto button margin-button-now">
                                    <button id="btnBuscarVentas" class="btn btn-primary btnRango" title="Buscar" type="submit">Buscar</button>&nbsp;
                                    <input type="button" id="btndownloadVentas" class="btn btn-primary btnRango" value="Descargar">
                                    </div>                                  
                                </div>
                              </fieldset>
                            </form>
                       </div>
                        </div> 
                    </div>   
                    <br />  
                    <div class="row">
                        {{--<div class="col-12 col-md-auto"> 
                            <h3 class="titulo_page mt-4">Total Ventas:</h3>
                        </div>
                        <div class="col-12 col-md-auto">    
                          <h3 id="h3totalVentas" class="titulo_page mt-4"></h3>
                        </div> --}}
                        <div class="d-flex align-items-center">  <!-- Agregamos d-flex y align-items-center -->
                            <h5 class="mt-4 me-2 fw-bold">Total Ventas:</h5>  <!-- Agregamos me-2 para margen derecho -->
                            <h5 id="h3totalVentas" class="mt-4 fw-bold"></h5>
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
                                <tbody id="tablaDatosVentas">
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
        const today1 = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
          $('#modalTable').on('shown.bs.modal', function () {
            $('#date1').datepicker({
                uiLibrary: 'bootstrap5',
                maxDate: function() {
                    return $('#date2').val();
                }
            });
        
            $('#date2').datepicker({
                uiLibrary: 'bootstrap5',
                minDate: function() {
                    return $('#date1').val();
                }
            });

                $.ajaxSetup({
                    headers: {
                      'X-CSRF-TOKEN': "{{ csrf_token() }}" 
                  }
                });
                $.ajax({
                  url:  "{{ route('report.sales')}}", 
                  type: 'GET',
                  dataType: "json",
                  data: {},
                  success: function(data) {
                    let tbodyDatosVentas = document.getElementById('tablaDatosVentas');
                    tbodyDatosVentas.innerHTML = "";
                    document.getElementById('h3totalVentas').innerHTML = data.data[0].total_periodo;

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
                    
                    Swal.fire({
                        title: 'Información', // Título de la alerta
                        text: xhr.responseText.msg, // Texto de la alerta
                        icon: 'info', // Icono de la alerta (success, error, warning, question)
                        confirmButtonText: 'Aceptar' // Texto del botón de confirmación
                    });
                  }
                
                });
          });         
          $('#modalTable').on('hidden.bs.modal', function () {
                 const formModalTable = this.querySelector('form'); 
                 formModalTable.classList.remove('was-validated');
                 formModalTable.reset();                        
            });
        });
</script>

<script>
   
  $(function(){
         
   $('#btnBuscarVentas').click(function (e) {
    let form = document.getElementById('formVentas');
     form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }else{
                event.preventDefault();
            }
            form.classList.add('was-validated');
            }, false);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}" 
            }
        });
        $.ajax({
            url:  "{{ route('report.sales')}}", 
            type: 'GET',
            dataType: "json",
            data: $('#formVentas').serialize(),
            success: function(data) {
                
                    let tbodyDatosVentas = document.getElementById('tablaDatosVentas');
                    tbodyDatosVentas.innerHTML = "";
                    document.getElementById('h3totalVentas').innerHTML = data.data[0].total_periodo;
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
                        title: 'Información', // Título de la alerta
                        text: 'No tiene ventas para el periodo indicado', // Texto de la alerta
                        icon: 'info', // Icono de la alerta (success, error, warning, question)
                        confirmButtonText: 'Aceptar' // Texto del botón de confirmación
                    });
                  }
                
                });
     
    }); 
    
  });  
      
</script>
<script>
    $(function(){
        $("#btndownloadVentas").click(function(event) {
            let swDownload = false;
            const startDate = $("#date1").val();
            const endDate = $("#date2").val();
            let validarStartDate = validarFecha(startDate);

            let validarEndDate = validarFecha(endDate);
            if((startDate =="" && endDate =="") || (validarStartDate == true && validarEndDate==true))
            {
                let form = $("#formVentas");
                let oldUrl = form.attr("action");
                let oldMethod = form.attr("method");
                let url = "{{route('report.exportsales')}}"; 
                let method = "GET"; 
                form.attr("action", url);
                form.attr("method", method);
                form.submit();
                form.attr("action", oldUrl);
                form.attr("method", oldMethod);
            }
            else
            {
                Swal.fire({
                        title: 'Información', // Título de la alerta
                        text: 'Debe ingresar un rango de fecha válido', // Texto de la alerta
                        icon: 'info', // Icono de la alerta (success, error, warning, question)
                        confirmButtonText: 'Aceptar' // Texto del botón de confirmación
                });
                $("#date1").val('');
                $("#date2").val('');
            }
        });
    });
    
</script>    
@endpush 