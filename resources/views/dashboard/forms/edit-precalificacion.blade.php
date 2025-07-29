@extends('layouts.master')

@section('title')
    @lang('translation.general_info_form') - @lang('translation.business-center')
@endsection

@section('content')
<style>
      .form-group {
        position: relative; /* Permite que los elementos hijos se posicionen en relación a este contenedor */
        margin-bottom: 20px; /* Espacio entre los grupos de formulario */
    }

    .fixed-label {        
        position: absolute; /* Posiciona el label de manera absoluta */
        bottom: 10; /* Coloca el label en la parte inferior del contenedor */
        left: 25; /* Coloca el label en la esquina izquierda */
        font-size: 12px; /* Tamaño de fuente del label */
        color: #6c757d; /* Color del texto del label */
        pointer-events: none; /* Evita que el label interfiera con la interacción del input/select */
        margin-bottom: 10px;
    }
 
      .no-rounded-border {
        border-radius: 0; /* Elimina el redondeo de los bordes */
        border: 1px solid #ced4da; /* Establece un borde sólido */
        padding: 8px; /* Agrega espacio interno */
    }

    .no-rounded-border:focus {
        box-shadow: none; /* Elimina el efecto de sombra al hacer foco */
        border-color: #80bdff; /* Cambia el color del borde al hacer foco */
    }
    .form-label::after {
        content: " *";
        color: red;
        display: none;
    }
    
    .form-label[for]:has(+ input[required])::after,
    .form-label[for]:has(+ select[required])::after,
    .form-label[for]:has(+ textarea[required])::after {
        display: inline;
    }

    .form-label.no-required::after {
        display: none !important;
    }

    .form-label {
        font-size: 18px;
    }
    .required {
        color: red; /* Cambia el color del asterisco a rojo */    
    }
    .row.mb-3 { /* O el selector del contenedor padre del canvas Y los botones */
    display: flex;
    flex-direction: column; /* Apila verticalmente */
}

.text-end { /* Contenedor de los botones */
    order: 2;  /* Los botones van después del canvas */
    margin-top: 20px; /* Espacio extra si lo necesitas */
}

#signature_container, #signature_cosigner_container {
    order: 1; /* El canvas va primero */
    margin-bottom: 20px; /* Espacio entre canvas y botones */
}

.fixed-label-signature {
    position: absolute;
    top: 8;    
    left: 25px; /* Ajusta según tu diseño */
    font-size: 12px;
    color: #6c757d;
    pointer-events: none; /* Evita que el label interfiera con el canvas */    
}

/* Media query para ocultar en móvil */
@media (max-width: 768px) { /* Ajusta el breakpoint según tu diseño */
    .fixed-label-signature {
        display: none;        
    }
}



/* Media query para pantallas pequeñas (opcional, solo si necesitas ajustes) */
#signature_canvas_cliente, #signature_canvas_cosigner {
        height: 150px;    
        max-height: 150px; /* Altura por defecto para pantallas grandes */
        width: 100%;
        max-width: 800px; /* Ancho máximo para pantallas grandes */
        margin: 0 auto; /* Centrar el canvas */
    }
@media (max-width: 768px) {
    #signature_canvas_cliente, #signature_canvas_cosigner {
        height: 200px; /* Altura para móviles */
        max-height: 200px;
        width: 100%;
        min-width: 250px; /* Ancho mínimo en móviles */
        margin: 0 auto;
    }
}
.decimal-input{
    text-align: left;
}
</style>
@php
    use Carbon\Carbon;
        if (session()->exists('success_register')) {
            $registroMensaje = session('success_register');
        } else if(session()->exists('error_f')){
            $registroMensaje = session('error_f');           
        }

        
@endphp
<div class="container-fluid">
   {{-- <div>
        <a href="{{ route('forms.reporte-precalificacion') }}" class="btn btn-primary">Generar Reporte</a>
    </div>--}}
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <br><br>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Pre-calificación</h4>
                </div>
                <div class="card-body">
                    
                    <!-- Agregar este bloque para mostrar errores -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <!-- Fin del bloque de errores -->
                    
                    <form id="generalInfoForm" method="POST" action="{{ route('forms.general-info.update') }}" enctype="multipart/form-data">
                        @csrf
                        <!-- General Information -->
                        <!-- Colocamos los input hidden aqui -->
                        <input type="hidden" id="co_qb_setter" name="co_qb_setter" value={{old('co_qb_setter',$aplicacion->co_qb_setter)}}>
                        <input type="hidden" id="co_qb_owner" name="co_qb_owner" value={{old('co_qb_owner',$aplicacion->co_qb_owner)}}>
                        <input type="hidden" id="co_cliente" name="co_cliente" value={{old('co_cliente',$aplicacion->co_cliente)}}>
                        <input type="hidden" id="co_aplicacion" name="co_aplicacion" value={{old('co_aplicacion',$aplicacion->co_aplicacion)}}>
                        <input type="hidden" id="tx_url_img_signature_c1_old" name="tx_url_img_signature_c1_old" value={{old('tx_url_img_signature_c1_old',$aplicacion->tx_url_img_signature_c1 ?? '')}}>
                        <input type="hidden" id="tx_url_img_signature_c2_old" name="tx_url_img_signature_c2_old" value={{old('tx_url_img_signature_c2_old',$aplicacion->tx_url_img_signature_c2 ?? '')}}>
                        <input type="hidden" id="tx_url_img_photoid_c1_old" name="tx_url_img_photoid_c1_old" value={{old('tx_url_img_photoid_c1_old',$aplicacion->tx_url_img_photoid_c1 ?? '')}}>
                        <input type="hidden" id="tx_url_img_photoid_c2_old" name="tx_url_img_photoid_c2_old" value={{old('tx_url_img_photoid_c2_old',$aplicacion->tx_url_img_photoid_c2 ?? '')}}>
                        <input type="hidden" id="co_qb_id_proyecto" name="co_qb_id_proyecto" value={{old('co_qb_id_proyecto',$aplicacion->co_qb_id_proyecto ?? '')}}>
                        <input type="hidden" id="urldestination" name="urldestination" value={{old('urldestination',$urldestination ?? 'account')}}>
                        <input type="hidden" id="tx_url_orden_trabajo" name="tx_url_orden_trabajo" value={{old('tx_url_orden_trabajo',$aplicacion->tx_url_orden_trabajo ?? '')}}>
                        <!-- Final de los hidden --->
                        <div class="row mb-4">
                            <div class="col-12 text-center">

                                <h5 class="border-bottom pb-2">General Information</h5>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-4">
                                    <label for="co_idioma" class="form-label">Primary Language</label>
                                    <select class="form-select @error('co_idioma') is-invalid @enderror" 
                                            id="co_idioma" 
                                            name="co_idioma" 
                                            required>                                        
                                        <option value="1" {{ old('co_idioma', $aplicacion->co_idioma ?? '') == '1' ? 'selected' : '' }}>Español</option>
                                        <option value="2" {{ old('co_idioma', $aplicacion->co_idioma ?? '') == '2' ? 'selected' : '' }}>Ingles</option>
                                    </select>
                                    <label class="fixed-label">Idioma Principal</label>
                                    {{--
                                    @error('co_idioma')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    --}}
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="co_metodo_pago_proyecto" class="form-label">Payment Method</label>
                                    <select class="form-select @error('co_metodo_pago_proyecto') is-invalid @enderror" 
                                            id="co_metodo_pago_proyecto" 
                                            name="co_metodo_pago_proyecto" 
                                            required>
                                            @foreach ($metodosPago as $metodoPago)
                                            <option value="{{ $metodoPago->co_metodo_pago }}" 
                                                {{ old('co_metodo_pago_proyecto', $aplicacion->co_metodo_pago_proyecto ?? '') == $metodoPago->co_metodo_pago ? 'selected' : '' }}>
                                                {{ $metodoPago->tx_nombre }}
                                            </option>
                                            @endforeach                                                                                 
                                        </select>
                                    <label class="fixed-label">Método de Pago</label>
                                    <input type="hidden" name="tx_metodo_pago" id="tx_metodo_pago" value="{{old('tx_metodo_pago',$aplicacion->tx_metodo_pago ?? '')}}">
                                </div>
                            </div>
                            <div class="col-12 form-group" id="container-cosigner">
                                <div class="mb-3">
                                    <label for="co_signer" class="form-label">Is there a Co-signer?</label>
                                    <select class="form-select" 
                                            id="co_signer" 
                                            name="bo_co_signer" 
                                            required>                                            
                                            <option value=""></option>                                            
                                            <option value="yes" 
                                                {{ old('bo_co_signer', $aplicacion->bo_co_signer ? 'yes' : '') == 'yes' ? 'selected' : '' }}>
                                                Yes
                                            </option>
                                            <option value="no" 
                                                {{ old('bo_co_signer', $aplicacion->bo_co_signer === false ? 'no' : '') == 'no' ? 'selected' : '' }}>
                                                No
                                            </option>
                                        
                                    </select>
                                    <label class="fixed-label">¿Hay un co-firmante?</label>
                                </div>
                            </div>
                        </div>
                        <!-- Financial Information -->
                        <div class="row mb-4">
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="nu_precio_total" class="form-label">Total System Price</label>
                                    <input type="text" class="form-control decimal-input" 
                                           id="nu_precio_total" 
                                           name="nu_precio_total" 
                                           value="{{ old('nu_precio_total', $aplicacion->nu_precio_total ?? '8490') }}" 
                                           required>
                                    <label class="fixed-label">Precio Total del Sistema</label>
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="nu_down_payment" class="form-label">Down Payment</label>
                                    <input type="text" class="form-control decimal-input @error('nu_down_payment') is-invalid @enderror" 
                                           id="nu_down_payment" 
                                           name="nu_down_payment" 
                                           value="{{ old('nu_down_payment', $aplicacion->nu_down_payment ?? '0.00') }}" 
                                           >
                                    <label class="fixed-label">Depósito</label>

                                    {{--       
                                    @error('nu_down_payment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    --}}
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="co_metodo_down_payment" class="form-label">Down Payment Method</label>
                                    <select class="form-select @error('co_metodo_down_payment') is-invalid @enderror" 
                                            id="co_metodo_down_payment" 
                                            name="co_metodo_down_payment" 
                                            >
                                            <option value=""></option>
                                        @foreach ($metodosPago as $metodoPago)
                                            @if ($metodoPago->co_metodo_pago != 2)
                                                <option value="{{ $metodoPago->co_metodo_pago }}" {{ old('co_metodo_down_payment', $aplicacion->co_metodo_down_payment ?? '') == $metodoPago->co_metodo_pago ? 'selected' : '' }}>
                                                    {{ $metodoPago->tx_nombre }}
                                                </option>
                                            @endif

                                        @endforeach                                    
                                    </select>
                                    <label class="fixed-label">Método de Depósito</label>
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="nu_precio_financiado" class="form-label">Total Amount Financed</label>
                                    <input type="text" class="form-control @error('nu_precio_financiado') is-invalid @enderror" 
                                           id="nu_precio_financiado" 
                                           name="nu_precio_financiado" 
                                           value="{{ old('nu_precio_financiado', $aplicacion->nu_precio_financiado ?? '') }}" 
                                           readonly>
                                        <label class="fixed-label">Importe Total Financiado</label>

                                    {{--       
                                    @error('nu_precio_financiado')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    --}}
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="co_producto" class="form-label">Promotions Included</label>
                                    <select class="form-select @error('co_producto') is-invalid @enderror" 
                                            id="co_producto" 
                                            name="co_producto">
                                            <option value=""></option>
                                        @foreach ($productosPromotions as $productoPromotion)
                                            <option value="{{ $productoPromotion->co_producto }}" {{ old('co_producto', $aplicacion->co_producto ?? '') == $productoPromotion->co_producto ? 'selected' : '' }}>
                                                {{ $productoPromotion->tx_nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label class="fixed-label">Promociones Incluidas</label>
                                    {{--       
                                    @error('co_producto')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    --}}
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="nu_cantidad_adultos" class="form-label">Adults at Home</label>
                                    <select class="form-select @error('nu_cantidad_adultos') is-invalid @enderror" 
                                            id="nu_cantidad_adultos" 
                                            name="nu_cantidad_adultos" 
                                            required>
                                            <option value=""></option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}" {{ old('nu_cantidad_adultos', $aplicacion->nu_cantidad_adultos ?? '') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                    <label class="fixed-label">Adultos en Casa</label>

                                    {{--       
                                    @error('nu_cantidad_adultos')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    --}}
                                </div>
                            </div>
                        </div>

                        <!-- Household Information -->
                        <div class="row mb-4">
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="nu_cantidad_ninos" class="form-label">Children at Home</label>
                                    <select class="form-select @error('nu_cantidad_ninos') is-invalid @enderror" 
                                            id="nu_cantidad_ninos" 
                                            name="nu_cantidad_ninos" 
                                            required>
                                            <option value=""></option>
                                            @for ($i = 0; $i <= 10; $i++)
                                            <option value="{{ $i }}" {{ old('nu_cantidad_ninos', $aplicacion->nu_cantidad_ninos ?? '') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor                                        

                                    </select>
                                    <label class="fixed-label">Niños en Casa</label>
                                    {{--       
                                    @error('nu_cantidad_ninos')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    --}}       
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="co_tipo_agua" class="form-label">Water Source</label>
                                    <select class="form-select @error('co_tipo_agua') is-invalid @enderror" 
                                            id="co_tipo_agua" 
                                            name="co_tipo_agua" 
                                            required>
                                            <option value=""></option>
                                        @foreach ($tiposAgua as $tipoAgua)
                                            <option value="{{ $tipoAgua->co_tipo_agua }}" {{ old('co_tipo_agua', $aplicacion->co_tipo_agua ?? '') == $tipoAgua->co_tipo_agua ? 'selected' : '' }}>
                                                {{ $tipoAgua->tx_tipo_agua }}
                                            </option>

                                        @endforeach                                        
                                    </select>
                                    <label class="fixed-label">Tipo de Agua</label>
                                    {{--       
                                    @error('co_tipo_agua')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    --}}
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    @php
                                        if($aplicacion->fe_instalacion){
                                            $fechaActual = Carbon::parse($aplicacion->fe_instalacion)->format('m/d/Y');
                                            $aplicacion->fe_instalacion = $fechaActual;
                                        }
                                    @endphp
                                    <label for="fe_instalacion" class="form-label">Estimated Install Date (mm/dd/yyyy) <span class="required">*</span></label>
                                    <input type="text" class="form-control no-rounded-border @error('fe_instalacion') is-invalid @enderror" 
                                           id="fe_instalacion" 
                                           name="fe_instalacion" 
                                           value="{{ old('fe_instalacion', $aplicacion->fe_instalacion ?? '') }}" 
                                           required>

                                    <label class="fixed-label">Fecha estimada de Instalación (mm/dd/yyyy)</label>
                                    {{--       
                                    @error('fe_instalacion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    --}}
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="tx_hora_instalacion" class="form-label">Estimated Install Time</label>
                                    <select class="form-select @error('tx_hora_instalacion') is-invalid @enderror" 
                                            id="tx_hora_instalacion" 
                                            name="tx_hora_instalacion" 
                                            required>
                                            <option value=""></option>
                                            <option value="08:00 am to 12:00 pm" {{ old('tx_hora_instalacion', $aplicacion->tx_hora_instalacion ?? '') == '08:00 am to 12:00 pm' ? 'selected' : '' }}>08:00 am to 12:00 pm</option>
                                            <option value="01:00 pm to 06:00 pm" {{ old('tx_hora_instalacion', $aplicacion->tx_hora_instalacion ?? '') == '01:00 pm to 06:00 pm' ? 'selected' : '' }}>01:00 pm to 06:00 pm</option>
                                    </select>
                                    <label class="fixed-label">Tiempo estimado de Instalación</label>

                                    {{--       
                                    @error('tx_hora_instalacion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    --}}
                                </div>
                            </div>
                        </div>

                        <!-- New Title for Primary Customer -->
                        <div class="row mb-4">
                            <div class="col-12 text-center">
                                <h5 class="border-bottom pb-2">Primary Customer / Cliente Principal</h5>
                            </div>
                        </div>
                        <!-- Primary Customer Information -->
                        <div class="row mb-4">
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="tx_primer_nombre_c1" class="form-label">First Name</label>
                                    <input type="text" class="form-control @error('tx_primer_nombre_c1') is-invalid @enderror" 
                                           id="tx_primer_nombre_c1" 
                                           name="tx_primer_nombre_c1" 
                                           value="{{ old('tx_primer_nombre_c1', $aplicacion->tx_primer_nombre_c1 ?? '') }}" 
                                           required>
                                           <label class="fixed-label">Primer Nombre</label>    
                                    {{--       
                                    @error('tx_primer_nombre_c1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    --}}
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="tx_inicial_segundo_c1" class="form-label">Middle Initial</label>
                                    <input type="text" class="form-control @error('tx_inicial_segundo_c1') is-invalid @enderror" 
                                           id="tx_inicial_segundo_c1" 
                                           name="tx_inicial_segundo_c1" 
                                           maxlength="1"
                                           value="{{ old('tx_inicial_segundo_c1', $aplicacion->tx_inicial_segundo_c1 ?? '') }}">
                                    <label class="fixed-label">Inicial del 2do Nombre</label>
                                    {{--       
                                    @error('tx_inicial_segundo_c1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    --}}
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="tx_apellido_c1" class="form-label">Last Name</label>
                                    <input type="text" class="form-control @error('tx_apellido_c1') is-invalid @enderror" 
                                           id="tx_apellido_c1" 
                                           name="tx_apellido_c1"
                                           value="{{ old('tx_apellido_c1', $aplicacion->tx_apellido_c1 ?? '') }}"
                                            required>
                                    <label class="fixed-label">Apellido</label>
                                    {{--           
                                    @error('tx_apellido_c1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    --}}
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="tx_telefono_c1" class="form-label">Phone</label>
                                    <input type="text" class="form-control @error('tx_telefono_c1') is-invalid @enderror" 
                                           id="tx_telefono_c1" 
                                           name="tx_telefono_c1" 
                                           value="{{ old('tx_telefono_c1', $aplicacion->tx_telefono_c1 ?? '') }}"
                                           required>
                                    <label class="fixed-label">Teléfono</label>
                                    {{--       
                                    @error('tx_telefono_c1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    --}}
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="tx_email_c1" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('tx_email_c1') is-invalid @enderror"
                                           id="tx_email_c1"
                                           name="tx_email_c1"
                                           value="{{ old('tx_email_c1', $aplicacion->tx_email_c1 ?? '') }}"
                                           required>
                                    <label class="fixed-label">Correo Electrónico</label>                                    
                                </div>
                            </div>
                            {{--       
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="confirm_email" class="form-label">Confirm Email</label>
                                    <input type="email" class="form-control @error('confirm_email') is-invalid @enderror" 
                                           id="confirm_email" 
                                           name="confirm_email"
                                           value="{{ old('confirm_email',$aplicacion->tx_email_c1 ?? '') }}"
                                           required>    
                                    <label class="fixed-label">Confirmar Correo Electrónico</label>


                                    
                                    @error('confirm_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror   
                                    
                                </div>
                            </div>
                            --}}
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="tx_direccion1_c1" class="form-label">Address</label>
                                    <input type="text" class="form-control @error('tx_direccion1_c1') is-invalid @enderror"
                                           id="tx_direccion1_c1"
                                           name="tx_direccion1_c1"
                                           value="{{ old('tx_direccion1_c1', $aplicacion->tx_direccion1_c1 ?? '') }}"
                                           required>
                                    <label class="fixed-label">Dirección Linea 1</label>
                                    
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <!--<label for="tx_direccion2_c1" class="form-label">Address Line 2</label>-->
                                    <input type="text" class="form-control @error('tx_direccion2_c1') is-invalid @enderror"
                                           id="tx_direccion2_c1"
                                           name="tx_direccion2_c1"
                                           value="{{ old('tx_direccion2_c1', $aplicacion->tx_direccion2_c1 ?? '') }}">
                                    <label class="fixed-label">Dirección Linea 2</label>                                    
                                </div>
                            </div>
                            <!-- New Inputs for City, State, and Zip Code in the same line -->
                            <div class="col-md-4 form-group">
                                <div class="mb-3">
                                    <label for="tx_ciudad_c1" class="form-label">City</label>
                                    <input type="text" class="form-control @error('tx_ciudad_c1') is-invalid @enderror" 
                                           id="tx_ciudad_c1" 
                                           name="tx_ciudad_c1"
                                           value="{{ old('tx_ciudad_c1', $aplicacion->tx_ciudad_c1 ?? '') }}"
                                           required>
                                        <label class="fixed-label">City</label>
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <div class="mb-3">
                                    <label for="co_estado_c1" class="form-label">State</label>
                                    <select class="form-select @error('co_estado_c1') is-invalid @enderror" 
                                            id="co_estado_c1" 
                                            name="co_estado_c1" 
                                            required>
                                            <option value=""></option>
                                        @foreach ($estados as $estado)
                                        {{--<option value="{{ $estado->co_estado }}" {{ old('co_estado_c1', $aplicacion->tx_estado ?? '') == $estado->tx_nombre ? 'selected' : '' }}>--}}    
                                        <option value="{{ $estado->co_estado }}" {{ old('co_estado_c1', $aplicacion->co_estado_c1 ?? '') == $estado->co_estado ? 'selected' : '' }}>
                                                {{ $estado->tx_nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label class="fixed-label">Estate</label>                                    
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <div class="mb-3">
                                    <label for="tx_zip_c1" class="form-label">Zip Code</label>
                                    <input type="text" class="form-control @error('tx_zip_c1') is-invalid @enderror" 
                                           id="tx_zip_c1" 
                                           name="tx_zip_c1" 
                                           value="{{ old('tx_zip_c1', $aplicacion->tx_zip_c1 ?? '') }}"
                                           required>
                                    <label class="fixed-label">Zip Code</label>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="row mb-4">
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="fe_fecha_nacimiento_c1" class="form-label">Date of Birth (mm/dd/yyyy) <span class="required">*</span></label>
                                    @php
                                        if($aplicacion->fe_fecha_nacimiento_c1){
                                            $fechaActual = Carbon::parse($aplicacion->fe_fecha_nacimiento_c1)->format('m/d/Y');
                                            $aplicacion->fe_fecha_nacimiento_c1 = $fechaActual;
                                        }
                                    @endphp
                                    <input type="text" class="form-control no-rounded-border @error('fe_fecha_nacimiento_c1') is-invalid @enderror" 
                                           id="fe_fecha_nacimiento_c1" 
                                           name="fe_fecha_nacimiento_c1" 
                                           value="{{ old('fe_fecha_nacimiento_c1', $aplicacion->fe_fecha_nacimiento_c1 ?? '') }}"
                                           required>
                                    <label class="fixed-label">Fecha de Nacimiento (mm/dd/yyyy)</label>
                                </div>
                            </div>
                            <div class="col-12 form-group" id="container-social-c1">
                                <div class="mb-3">
                                    <label for="tx_social_security_number_c1" class="form-label">Social Security Number</label>
                                    <input type="text" class="form-control @error('tx_social_security_number_c1') is-invalid @enderror" 
                                           id="tx_social_security_number_c1" 
                                           name="tx_social_security_number_c1" 
                                           value="{{ old('tx_social_security_number_c1', $aplicacion->tx_social_security_number_c1 ?? '') }}"
                                           maxlength="9"
                                           required>

                                    <label class="fixed-label">Número de Seguro Social</label>
                                </div>
                            </div>
                            <div class="col-12 form-group" id="container-license-c1">
                                <div class="mb-3">
                                    <label for="tx_licencia_c1" class="form-label">Driver License or ID Number</label>
                                    <input type="text" class="form-control @error('tx_licencia_c1') is-invalid @enderror" 
                                           id="tx_licencia_c1"
                                           name="tx_licencia_c1"
                                           maxlength="250"
                                           value="{{ old('tx_licencia_c1', $aplicacion->tx_licencia_c1 ?? '') }}"
                                           required>

                                    <label class="fixed-label">Número de Licencia de Conducir o ID</label>
                                     {{--      
                                    @error('tx_licencia_c1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    --}}
                                </div>
                            </div>
                            <div class="col-12 form-group" id="container-fe-vencimiento-c1">
                                <div class="mb-3">
                                    <label for="fe_vencimto_licencia_c1" class="form-label">Expiration Date (mm/dd/yyyy) <span class="required">*</span></label>
                                    @php
                                        if($aplicacion->fe_vencimto_licencia_c1){
                                            $fechaActual = Carbon::parse($aplicacion->fe_vencimto_licencia_c1)->format('m/d/Y');
                                            $aplicacion->fe_vencimto_licencia_c1 = $fechaActual;
                                        }
                                    @endphp
                                    <input type="text" class="form-control no-rounded-border @error('fe_vencimto_licencia_c1') is-invalid @enderror" 
                                           id="fe_vencimto_licencia_c1"
                                           name="fe_vencimto_licencia_c1"
                                           value="{{ old('fe_vencimto_licencia_c1', $aplicacion->fe_vencimto_licencia_c1 ?? '') }}"
                                           required>

                                    <label class="fixed-label">Fecha de Vencimiento (mm/dd/yyyy)</label>
                                    {{--       
                                    @error('fe_vencimto_licencia_c1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    --}}
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="tx_url_img_photoid_c1" class="form-label">Upload Photo ID</label>
                                    <input type="file" class="form-control @error('tx_url_img_photoid_c1') is-invalid @enderror" 
                                           id="tx_url_img_photoid_c1" 
                                           name="tx_url_img_photoid_c1"
                                           accept="image/*" 
                                           value="{{ old('tx_url_img_photoid_c1',$aplicacion->tx_url_img_photoid_c1 ?? '') }}"                                          
                                           >
                                           @if(!empty($aplicacion->tx_url_img_photoid_c1))
                                                <div class="small text-muted" id="actual_photo_id_c1">
                                                    Archivo actual: {{ basename($aplicacion->tx_url_img_photoid_c1) }}
                                                </div>
                                            @endif 
                                    <label class="fixed-label">Subir Foto de ID</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-12">
                                {{--<div>
                                    <label for="check_cliente"><h5>Verified Primary Customer Details Above <span class="required">*</span></h5></label>
                                </div>--}}
                            </div>
                            <div class="col-12 form-group">
                              {{--  <div class="col-12">
                                    <input class="form-check-input" type="checkbox" id="check_cliente" name="check_cliente" required>
                                    <label class="form-check-label" for="check_cliente">
                                        Yes / Si
                                    </label>                                
                                </div>
                                <label class="fixed-label">Detalles del Cliente Principal Arriba Verificados</label>
                            </div>
                            --}}
                        </div>
                        <!-- Employment Information -->
                        <div class="row mb-4" id="container-continuacion-c1">
                            <div class="col-12 text-center">
                                <h5 class="border-bottom pb-2">Primary Customer/ Cliente Principal Cont.</h5>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="tx_nombre_trabajo_c1" class="form-label">Employer</label>
                                    <input type="text" class="form-control @error('tx_nombre_trabajo_c1') is-invalid @enderror" 
                                           id="tx_nombre_trabajo_c1" 
                                           name="tx_nombre_trabajo_c1" 
                                           value="{{ old('tx_nombre_trabajo_c1',$aplicacion->tx_nombre_trabajo_c1 ?? '') }}"
                                           required>
                                    <label class="fixed-label">Empleador</label>

                                    {{--       
                                    @error('tx_nombre_trabajo_c1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    --}}
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="nu_tiempo_trabajo_c1" class="form-label">Years with Employer</label>
                                    <input type="number" class="form-control @error('nu_tiempo_trabajo_c1') is-invalid @enderror" 
                                           id="nu_tiempo_trabajo_c1" 
                                           name="nu_tiempo_trabajo_c1" 
                                           value="{{ old('nu_tiempo_trabajo_c1', $aplicacion->nu_tiempo_trabajo_c1 ?? '') }}"
                                           min="0"
                                           required>
                                    <label class="fixed-label">Años Empleados</label>
                                    {{--       
                                    @error('nu_tiempo_trabajo_c1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    --}}
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="tx_puesto_c1" class="form-label">Position</label>
                                    <input type="text" class="form-control @error('tx_puesto_c1') is-invalid @enderror" 
                                           id="tx_puesto_c1" 
                                           name="tx_puesto_c1" 
                                           value="{{ old('tx_puesto_c1', $aplicacion->tx_puesto_c1 ?? '') }}"
                                           required>

                                    <label class="fixed-label">Puesto</label>
                                    {{--       
                                    @error('tx_puesto_c1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    --}}
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="nu_salario_mensual_c1" class="form-label">Monthly Salary ($)</label>
                                    <input type="text" class="form-control decimal-input @error('nu_salario_mensual_c1') is-invalid @enderror" 
                                           id="nu_salario_mensual_c1" 
                                           name="nu_salario_mensual_c1" 
                                           value="{{ old('nu_salario_mensual_c1', $aplicacion->nu_salario_mensual_c1 ?? '0.00') }}"                                           
                                           required>
                                    <label class="fixed-label">Salario Mensual</label>
                                    {{--       
                                    @error('nu_salario_mensual_c1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    --}}    
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="business_phone" class="form-label">Business Phone</label>
                                    <input type="tel" class="form-control @error('tx_tlf_trab_c1') is-invalid @enderror" 
                                           id="business_phone" 
                                           name="tx_tlf_trab_c1" 
                                           value="{{ old('tx_tlf_trab_c1', $aplicacion->tx_tlf_trab_c1 ?? '') }}" 
                                           >
                                    <label class="fixed-label">Teléfono del Trabajo</label>
                                    {{--       
                                    @error('tx_tlf_trab_c1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    --}}
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="tx_direc1_trab_c1" class="form-label">Business Address</label>
                                    <input type="text" class="form-control @error('tx_direc1_trab_c1') is-invalid @enderror" 
                                           id="tx_direc1_trab_c1" 
                                           name="tx_direc1_trab_c1" 
                                           value="{{ old('tx_direc1_trab_c1', $aplicacion->tx_direc1_trab_c1 ?? '') }}" 
                                           required>
                                    <label class="fixed-label">Address Line 1</label>
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <input type="text" class="form-control @error('tx_direc2_trab_c1') is-invalid @enderror" 
                                           id="business_address" 
                                           name="tx_direc2_trab_c1" 
                                           value="{{ old('tx_direc2_trab_c1', $aplicacion->tx_direc2_trab_c1 ?? '') }}" 
                                           >

                                    <label class="fixed-label">Address Line 2</label>
                                    {{--       
                                    @error('tx_direc2_trab_c1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror   
                                    --}}
                                </div>
                            </div>
                        <!--</div>-->
                            {{-- ubicacion del trabajo --}}
                            <div class="row mb-4">
                            <div class="col-md-4 form-group">
                                <div class="mb-3">
                                    <label for="tx_ciudad_trab_c1" class="form-label">Business City</label>
                                    <input type="text" class="form-control @error('tx_ciudad_trab_c1') is-invalid @enderror" 
                                           id="tx_ciudad_trab_c1" 
                                           name="tx_ciudad_trab_c1" 
                                           value="{{ old('tx_ciudad_trab_c1', $aplicacion->tx_ciudad_trab_c1 ?? '') }}" 
                                           required>

                                    <label class="fixed-label">City</label>                                    
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <div class="mb-3">
                                    <label for="co_estado_trab_c1" class="form-label">Business State</label>
                                    <select class="form-select @error('co_estado_trab_c1') is-invalid @enderror" 
                                            id="co_estado_trab_c1" 
                                            name="co_estado_trab_c1"
                                            required>
                                        <option value=""></option>
                                        @foreach ($estados as $estado)
                                            <option value="{{ $estado->co_estado }}" {{ old('co_estado_trab_c1', $aplicacion->co_estado_trab_c1 ?? '') == $estado->co_estado ? 'selected' : '' }}>
                                                {{ $estado->tx_nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label class="fixed-label">State</label>                                     
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <div class="mb-3">
                                    <label for="tx_zip_trab_c1" class="form-label">Business Zip</label>
                                    <input type="text" class="form-control @error('tx_zip_trab_c1') is-invalid @enderror" 
                                           id="tx_zip_trab_c1" 
                                           name="tx_zip_trab_c1" 
                                           value="{{ old('tx_zip_trab_c1', $aplicacion->tx_zip_trab_c1 ?? '') }}" 
                                           required>

                                    <label class="fixed-label">Zip Code</label>
                                    {{--       
                                    @error('tx_zip_trab_c1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    --}}
                                </div>
                            </div>
                            </div>
                            {{-- Ingreso alternativo --}}
                            <div class="row mb-4">
                            <div class="col-md-4 form-group">
                                <div class="mb-3">
                                    <label for="nu_ingresos_alter_c1" class="form-label">Alt Income Source</label>
                                    <input type="text" class="form-control decimal-input @error('nu_ingresos_alter_c1') is-invalid @enderror" 
                                           id="nu_ingresos_alter_c1" 
                                           name="nu_ingresos_alter_c1" 
                                           value="{{ old('nu_ingresos_alter_c1', $aplicacion->nu_ingresos_alter_c1 ?? '0.00') }}">
                                    <label class="fixed-label">Ingreso Alternativo</label>
                                    {{--       
                                    @error('nu_ingresos_alter_c1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    --}}
                                </div>
                            </div>
                            </div>
                        </div>
                        <!-- Inicia el contenedor del secundario -->
                        
                        
                        <!-- Secondary Customer/ Cliente Secundario -->
                        <div class="row mb-4 d-none" id="container-secondary">
                            <div class="row mb-4">
                                <div class="col-12 text-center">
                                    <h5 class="border-bottom pb-2">Secondary Customer/ Cliente Secundario</h5>
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="tx_relacion_c2_con_c1" class="form-label">Relationship to Primary Customer</label>
                                    <select class="form-select" 
                                            id="tx_relacion_c2_con_c1" 
                                            name="tx_relacion_c2_con_c1">
                                        <option value=""></option>                                        
                                        <option value="Spouse" {{ old('tx_relacion_c2_con_c1', $aplicacion->tx_relacion_c2_con_c1 ?? '') == 'Spouse' ? 'selected' : '' }}>Spouse</option>
                                        <option value="Sibiling" {{ old('tx_relacion_c2_con_c1', $aplicacion->tx_relacion_c2_con_c1 ?? '') == 'Sibiling' ? 'selected' : '' }}>Sibiling</option>
                                        <option value="Parent" {{ old('tx_relacion_c2_con_c1', $aplicacion->tx_relacion_c2_con_c1 ?? '') == 'Parent' ? 'selected' : '' }}>Parent</option>
                                        <option value="Child" {{ old('tx_relacion_c2_con_c1', $aplicacion->tx_relacion_c2_con_c1 ?? '') == 'Child' ? 'selected' : '' }}>Child</option>
                                        <option value="Friend" {{ old('tx_relacion_c2_con_c1', $aplicacion->tx_relacion_c2_con_c1 ?? '') == 'Friend' ? 'selected' : '' }}>Friend</option>                                        
                                    </select>
                                    <label class="fixed-label">Relación con el Cliente Principal</label>

                            </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="tx_primer_nombre_c2" class="form-label">S_ First Name</label>
                                    <input type="text" class="form-control @error('tx_primer_nombre_c2') is-invalid @enderror" 
                                           id="tx_primer_nombre_c2" 
                                           name="tx_primer_nombre_c2" 
                                           value="{{ old('tx_primer_nombre_c2', $aplicacion->tx_primer_nombre_c2 ?? '') }}" 
                                           >                                    

                                    <label class="fixed-label">Primer Nombre</label>
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="tx_inicial_segundo_c2" class="form-label">s_ Middle Initial</label>
                                    <input type="text" class="form-control @error('tx_inicial_segundo_c2') is-invalid @enderror" 
                                           id="tx_inicial_segundo_c2" 
                                           name="tx_inicial_segundo_c2" 
                                           maxlength="1"
                                           value="{{ old('tx_inicial_segundo_c2', $aplicacion->tx_inicial_segundo_c2 ?? '') }}">
                                    <label class="fixed-label">Inicial del 2do Nombre</label>
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="tx_apellido_c2" class="form-label">s_ Last Name</label>
                                    <input type="text" class="form-control @error('tx_apellido_c2') is-invalid @enderror" 
                                           id="tx_apellido_c2" 
                                           name="tx_apellido_c2"
                                           value="{{ old('tx_apellido_c2', $aplicacion->tx_apellido_c2 ?? '') }}"
                                           >

                                    <label class="fixed-label">Apellido</label>
                                    {{--           
                                    @error('tx_apellido_c1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    --}}
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="tx_telefono_c2" class="form-label">s_ Phone</label>
                                    <input type="text" class="form-control @error('tx_telefono_c2') is-invalid @enderror" 
                                           id="tx_telefono_c2" 
                                           name="tx_telefono_c2" 
                                           value="{{ old('tx_telefono_c2', $aplicacion->tx_telefono_c2 ?? '') }}"
                                           >

                                    <label class="fixed-label">Teléfono</label>
                                    {{--       
                                    @error('tx_telefono_c1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    --}}
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="tx_email_c2" class="form-label">s_ Email</label>
                                    <input type="email" class="form-control @error('tx_email_c2') is-invalid @enderror"
                                           id="tx_email_c2"
                                           name="tx_email_c2"
                                           value="{{ old('tx_email_c2', $aplicacion->tx_email_c2 ?? '') }}"
                                           >

                                    <label class="fixed-label">Email</label>
                                    {{--       
                                    @error('tx_email_c1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    --}}
                                </div>
                            </div>
                            {{--       
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="confirm_email_c2" class="form-label">s_ Confirm Email</label>
                                    <input type="email" class="form-control" 
                                           id="confirm_email_c2" 
                                           name="confirm_email_c2"
                                           value="{{ old('confirm_email_c2', $aplicacion->tx_email_c2 ?? '') }}"
                                           >

                                    <label class="fixed-label">Confirmar Email</label>
                                    
                                    @error('confirm_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror   
                                    
                                </div>
                            </div>
                            --}}
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    @php
                                        if($aplicacion->fe_fecha_nacimiento_c2){
                                            $fecha_nacimiento = Carbon::parse($aplicacion->fe_fecha_nacimiento_c2)->format('m/d/Y');
                                            $aplicacion->fe_fecha_nacimiento_c2 = $fecha_nacimiento;
                                        }
                                    @endphp
                                    <label for="fe_fecha_nacimiento_c2" class="form-label">s_ Date of Birth (mm/dd/yyyy)<span class="required">*</span></label>
                                    <input type="text" class="form-control no-rounded-border @error('fe_fecha_nacimiento_c2') is-invalid @enderror" 
                                           id="fe_fecha_nacimiento_c2" 
                                           name="fe_fecha_nacimiento_c2" 
                                           value="{{ old('fe_fecha_nacimiento_c2', $aplicacion->fe_fecha_nacimiento_c2 ?? '') }}"
                                           >
                                    <label class="fixed-label">Fecha de Nacimiento (mm/dd/yyyy)</label>
                                    {{--       
                                    @error('fe_fecha_nacimiento_c1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    --}}
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="tx_social_security_number_c2" class="form-label">s_ Social Security Number</label>
                                    <input type="text" class="form-control @error('tx_social_security_number_c2') is-invalid @enderror" 
                                           id="tx_social_security_number_c2" 
                                           name="tx_social_security_number_c2" 
                                           value="{{ old('tx_social_security_number_c2', $aplicacion->tx_social_security_number_c2 ?? '') }}"
                                           maxlength="9">                                    
                                    <label class="fixed-label">Número de Seguro Social</label>
                                </div>

                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="tx_licencia_c2" class="form-label">s_ Driver License or ID Number</label>
                                    <input type="text" class="form-control @error('tx_licencia_c2') is-invalid @enderror" 
                                           id="tx_licencia_c2"
                                           name="tx_licencia_c2"                                           
                                           maxlength="250"
                                           value="{{ old('tx_licencia_c2', $aplicacion->tx_licencia_c2 ?? '') }}"
                                           >                                     
                                    <label class="fixed-label">Número de Licencia de Conducir o ID</label>
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                @php
                                    if($aplicacion->fe_vencimto_licencia_c2){
                                        $fecha_vencimiento = Carbon::parse($aplicacion->fe_vencimto_licencia_c2)->format('m/d/Y');
                                        $aplicacion->fe_vencimto_licencia_c2 = $fecha_vencimiento;
                                    }
                                @endphp
                                <div class="mb-3">
                                    <label for="fe_vencimto_licencia_c2" class="form-label">s_ Expiration Date (mm/dd/yyyy)<span class="required">*</span></label>

                                    <input type="text" class="form-control no-rounded-border @error('fe_vencimto_licencia_c2') is-invalid @enderror" 
                                           id="fe_vencimto_licencia_c2"
                                           name="fe_vencimto_licencia_c2"
                                           value="{{ old('fe_vencimto_licencia_c2', $aplicacion->fe_vencimto_licencia_c2 ?? '') }}"
                                           >                                    
                                    <label class="fixed-label">Fecha de Vencimiento (mm/dd/yyyy)</label>
                                </div>

                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="photo_id_c2" class="form-label">s_ Upload Photo ID</label>
                                    <input type="file" class="form-control @error('tx_url_img_photoid_c2') is-invalid @enderror" 
                                           id="tx_url_img_photoid_c2" 
                                           name="tx_url_img_photoid_c2"
                                           accept="image/*"
                                           >   
                                           @if(!empty($aplicacion->tx_url_img_photoid_c2))
                                                <div class="small text-muted" id="actual_photo_id_c2">
                                                    Archivo actual: {{ basename($aplicacion->tx_url_img_photoid_c2) }}
                                                </div>
                                            @endif                                  
                                    <label class="fixed-label">Subir Foto de ID</label>
                                </div>
                            </div>

                            <!-- Employment Information -->
                            <div class="row mb-4">
                            <div class="col-12 text-center">
                                <h5 class="border-bottom pb-2">Secondary Customer/ Cliente Secundario Cont.</h5>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="tx_nombre_trabajo_c2" class="form-label">s_ Employer</label>
                                    <input type="text" class="form-control @error('tx_nombre_trabajo_c2') is-invalid @enderror" 
                                           id="tx_nombre_trabajo_c2" 
                                           name="tx_nombre_trabajo_c2" 
                                           value="{{ old('tx_nombre_trabajo_c2', $aplicacion->tx_nombre_trabajo_c2 ?? '') }}"
                                           >                                    

                                    <label class="fixed-label">Empleador</label>
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="nu_tiempo_trabajo_c2" class="form-label">s_ Years with Employer</label>
                                    <input type="number" class="form-control @error('nu_tiempo_trabajo_c2') is-invalid @enderror" 
                                           id="nu_tiempo_trabajo_c2" 
                                           name="nu_tiempo_trabajo_c2" 
                                           value="{{ old('nu_tiempo_trabajo_c2', $aplicacion->nu_tiempo_trabajo_c2 ?? '') }}"
                                           min="0">                               
                                    <label class="fixed-label">Años Empleados</label>
                                </div>

                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="tx_puesto_c2" class="form-label">s_ Position</label>
                                    <input type="text" class="form-control @error('tx_puesto_c2') is-invalid @enderror" 
                                           id="tx_puesto_c2" 
                                           name="tx_puesto_c2" 
                                           value="{{ old('tx_puesto_c2', $aplicacion->tx_puesto_c2 ?? '') }}"
                                           >

                                    <label class="fixed-label">Puesto</label>
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="nu_salario_mensual_c2" class="form-label">s_ Monthly Salary</label>
                                    <input type="text" class="form-control decimal-input @error('nu_salario_mensual_c2') is-invalid @enderror" 
                                           id="nu_salario_mensual_c2" 
                                           name="nu_salario_mensual_c2" 
                                           value="{{ old('nu_salario_mensual_c2', $aplicacion->nu_salario_mensual_c2 ?? '0.00') }}" 
                                           >

                                    <label class="fixed-label">Salario Mensual</label>
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="tx_tlf_trab_c2" class="form-label">s_ Business Phone</label>
                                    <input type="tel" class="form-control @error('tx_tlf_trab_c2') is-invalid @enderror" 
                                           id="tx_tlf_trab_c2" 
                                           name="tx_tlf_trab_c2" 
                                           value="{{ old('tx_tlf_trab_c2', $aplicacion->tx_tlf_trab_c2 ?? '') }}" 
                                           >

                                    <label class="fixed-label">Teléfono del Trabajo</label>
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="tx_direc1_trab_c2" class="form-label">s_ Business Address</label>
                                    <input type="text" class="form-control @error('tx_direc1_trab_c2') is-invalid @enderror" 
                                           id="tx_direc1_trab_c2" 
                                           name="tx_direc1_trab_c2" 
                                           value="{{ old('tx_direc1_trab_c2', $aplicacion->tx_direc1_trab_c2 ?? '') }}" 
                                           >

                                    <label class="fixed-label">Address Line 1</label>
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <input type="text" class="form-control @error('tx_direc2_trab_c2') is-invalid @enderror" 
                                           id="tx_direc2_trab_c2" 
                                           name="tx_direc2_trab_c2" 
                                           value="{{ old('tx_direc2_trab_c2', $aplicacion->tx_direc2_trab_c2 ?? '') }}" 
                                           >

                                    <label class="fixed-label">Address Line 2</label>
                                </div>
                            </div>
                            </div>
                            {{-- ubicacion del trabajo --}}
                            <div class="row mb-4">
                            <div class="col-md-4 form-group">
                                <div class="mb-3">
                                    <input type="text" class="form-control @error('tx_ciudad_trab_c2') is-invalid @enderror" 
                                           id="tx_ciudad_trab_c2" 
                                           name="tx_ciudad_trab_c2" 
                                           value="{{ old('tx_ciudad_trab_c2', $aplicacion->tx_ciudad_trab_c2 ?? '') }}" 
                                           >                                    

                                    <label class="fixed-label">City</label>
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <div class="mb-3">
                                    <select class="form-select @error('co_estado_trab_c2') is-invalid @enderror" 
                                            id="co_estado_trab_c2" 
                                            name="co_estado_trab_c2">
                                        <option value=""></option>
                                        @foreach ($estados as $estado)
                                            <option value="{{ $estado->co_estado }}" {{ old('co_estado_trab_c2', $aplicacion->co_estado_c1 ?? '') == $estado->co_estado ? 'selected' : '' }}>
                                                {{ $estado->tx_nombre }}
                                            </option>
                                        @endforeach


                                    </select>                                     
                                    <label class="fixed-label">State</label>
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <div class="mb-3">
                                    <input type="text" class="form-control @error('tx_zip_trab_c2') is-invalid @enderror" 
                                           id="tx_zip_trab_c2" 
                                           name="tx_zip_trab_c2" 
                                           value="{{ old('tx_zip_trab_c2', $aplicacion->tx_zip_trab_c2 ?? '') }}" 
                                           >                                   

                                    <label class="fixed-label">Zip Code</label>
                                </div>
                            </div>
                            </div>
                            {{-- Ingreso alternativo --}}
                            <div class="row mb-4">
                            <div class="col-md-4 form-group">
                                <div class="mb-3">
                                    <label for="nu_ingresos_alter_c2" class="form-label">s_ Alt Income Source</label>
                                    <input type="text" class="form-control decimal-input @error('nu_ingresos_alter_c2') is-invalid @enderror" 
                                           id="nu_ingresos_alter_c2" 
                                           name="nu_ingresos_alter_c2" 
                                           value="{{ old('nu_ingresos_alter_c2', $aplicacion->nu_ingresos_alter_c2 ?? '0.00') }}">                                    
                                    <label class="fixed-label">Ingreso Alternativo</label>
                                </div>
                            </div>
                            </div>
                    </div>
                        <!-- Finaliza el contenedor del secundario -->                                               

                        <!-- Finance Info / Información de la Hipoteca -->
                        <div class="row mb-4" id="container-finances">
                            <div class="col-12 text-center">
                                <h5 class="border-bottom pb-2">Finance Info / Información de la Hipoteca</h5>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="mortgage_status" class="form-label">Mortgage Status</label>
                                    <select class="form-select @error('tx_hipoteca_estatus') is-invalid @enderror" 
                                            id="mortgage_status" 
                                            name="tx_hipoteca_estatus">
                                            <option value=""></option>
                                            @foreach ($hipotecas as $hipoteca)
                                                <option value="{{ $hipoteca->tx_estatus }}" {{ old('tx_hipoteca_estatus', $aplicacion->tx_hipoteca_estatus ?? '') == $hipoteca->tx_estatus ? 'selected' : '' }}>
                                                {{ $hipoteca->tx_estatus }}

                                            </option>
                                            @endforeach
                                        
                                    </select>
                                    <label class="fixed-label">Estado de la Hipoteca</label>
                                    
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="mortgage_company" class="form-label no-required">Mortgage Company</label>
                                    {{----}}
                                    <input type="text" class="form-control @error('tx_hipoteca_company') is-invalid @enderror"
                                            id="mortgage_company" 
                                           name="tx_hipoteca_company" 
                                           value="{{ old('tx_hipoteca_company', $aplicacion->tx_hipoteca_company ?? '') }}">
                                    <label class="fixed-label">Compañía Hipotecaria</label>
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="mortgage_payment" class="form-label">Mortgage or Rent Payment</label>
                                    <input type="text" class="form-control decimal-input @error('nu_hipoteca_renta') is-invalid @enderror" 
                                           id="mortgage_payment" 
                                           name="nu_hipoteca_renta" 
                                           value="{{ old('nu_hipoteca_renta', $aplicacion->nu_hipoteca_renta ?? '0.00') }}" 
                                           required>

                                    <label class="fixed-label">Pago de Hipoteca o Alquiler</label>                                    
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    @php
                                        $tiempoArray = explode(' ',$aplicacion->tx_hipoteca_tiempo);//preg_split('/\s+/', trim($aplicacion->tx_hipoteca_tiempo));
                                        
                                        $aplicacion->nu_hipoteca_tiempo = trim($tiempoArray[0]);
                                        
                                    @endphp
                                    <label for="how_long_here" class="form-label">How Long Here</label>
                                    <select class="form-select @error('nu_hipoteca_tiempo') is-invalid @enderror" 
                                            id="how_long_here" 
                                            name="nu_hipoteca_tiempo">
                                        <option value=""></option>                                        
                                        @for ($i = 1; $i <= 11; $i++)
                                            <option value="{{ $i }}" {{ old('nu_hipoteca_tiempo', $aplicacion->nu_hipoteca_tiempo ?? '') == $i ? 'selected' : '' }}>{{ $i }} {{ $i == 1 ? 'Month' : 'Months' }}</option>
                                        @endfor
                                        @for ($i = 12; $i < 41; $i++)
                                            <option value="{{ $i }}" {{ old('nu_hipoteca_tiempo', $aplicacion->nu_hipoteca_tiempo ?? '') == $i ? 'selected' : '' }}>{{ ($i-11) }} {{ ($i == 12) ? 'Year' : 'Years' }}</option>
                                        @endfor
                                        <option value="{{ 41 }}" {{ old('nu_hipoteca_tiempo', $aplicacion->nu_hipoteca_tiempo ?? '') == 41 ? 'selected' : '' }}>{{ '+ 30 Years' }}</option>
                                    </select>                                    
                                    <label class="fixed-label">¿Cuanto Tiempo Aquí?</label>
                                    <input type="hidden" id="tx_hipoteca_tiempo" name="tx_hipoteca_tiempo" value="{{ old('tx_hipoteca_tiempo',$aplicacion->tx_hipoteca_tiempo ?? '') }}">                                    

                                </div>
                            </div>
                        </div>

                        <!-- References Section -->
                        <div class="row mb-4" id="container-references">
                            <div class="col-12 text-center">
                                <h5 class="border-bottom pb-2">Reference/ Referencia #1</h5>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="reference1_name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control @error('tx_ref1_nombre') is-invalid @enderror" 
                                           id="reference1_name" 
                                           name="tx_ref1_nombre" 
                                           value="{{ old('tx_ref1_nombre', $aplicacion->tx_ref1_nombre ?? '') }}" 
                                           required>                                    

                                </div>
                                <div class="mb-3">
                                    <label for="reference1_relationship" class="form-label">Relationship</label>
                                    <select class="form-select @error('tx_ref1_relacion') is-invalid @enderror" 
                                            id="reference1_relationship" 
                                            name="tx_ref1_relacion" 
                                            required>
                                        <option value=""></option>
                                        <option value="Friend" {{ old('tx_ref1_relacion', $aplicacion->tx_ref1_relacion ?? '') == 'Friend' ? 'selected' : '' }}>Friend</option>
                                        <option value="Co-worker" {{ old('tx_ref1_relacion', $aplicacion->tx_ref1_relacion ?? '') == 'Co-worker' ? 'selected' : '' }}>Co-worker</option>
                                        <option value="Family" {{ old('tx_ref1_relacion', $aplicacion->tx_ref1_relacion ?? '') == 'Family' ? 'selected' : '' }}>Family</option>
                                        <option value="Employer" {{ old('tx_ref1_relacion', $aplicacion->tx_ref1_relacion ?? '') == 'Employer' ? 'selected' : '' }}>Employer</option>                                        
                                    </select>
                                    
                                </div>
                                <div class="mb-3">
                                    <label for="reference1_phone" class="form-label">Reference Phone</label>
                                    <input type="tel" class="form-control @error('tx_ref1_telefono') is-invalid @enderror" 
                                           id="reference1_phone" 
                                           name="tx_ref1_telefono" 
                                           value="{{ old('tx_ref1_telefono', $aplicacion->tx_ref1_telefono ?? '') }}" 
                                           required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="col-12 text-center">
                                    <h5 class="border-bottom pb-2">Reference/ Referencia #2</h5>
                                </div>
                                <div class="mb-3">
                                    <label for="reference2_name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control @error('tx_ref2_nombre') is-invalid @enderror" 
                                           id="reference2_name" 
                                           name="tx_ref2_nombre" 
                                           value="{{ old('tx_ref2_nombre', $aplicacion->tx_ref2_nombre ?? '') }}" 
                                           required>                                    
                                </div>
                                <div class="mb-3">
                                    <label for="reference2_relationship" class="form-label">Relationship</label>
                                    <select class="form-select @error('tx_ref2_relacion') is-invalid @enderror" 
                                            id="reference2_relationship" 
                                            name="tx_ref2_relacion" 
                                            required>
                                        <option value=""></option>
                                        <option value="Friend" {{ old('tx_ref2_relacion', $aplicacion->tx_ref2_relacion ?? '') == 'Friend' ? 'selected' : '' }}>Friend</option>
                                        <option value="Co-worker" {{ old('tx_ref2_relacion', $aplicacion->tx_ref2_relacion ?? '') == 'Co-worker' ? 'selected' : '' }}>Co-worker</option>
                                        <option value="Family" {{ old('tx_ref2_relacion', $aplicacion->tx_ref2_relacion ?? '') == 'Family' ? 'selected' : '' }}>Family</option>
                                        <option value="Employer" {{ old('tx_ref2_relacion', $aplicacion->tx_ref2_relacion ?? '') == 'Employer' ? 'selected' : '' }}>Employer</option>                                        
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="reference2_phone" class="form-label">Reference Phone</label>
                                    <input type="tel" class="form-control @error('tx_ref2_telefono') is-invalid @enderror" 
                                           id="reference2_phone" 
                                           name="tx_ref2_telefono" 
                                           value="{{ old('tx_ref2_telefono', $aplicacion->tx_ref2_telefono ?? '') }}" 
                                           required> 
                                    
                                </div>
                            </div>
                        </div>
                        <!--Informacion bancaria-->
                        <div class="row mb-4" id="container-bank">
                            <div class="col-12 text-center">
                                <h5 class="border-bottom pb-2">Account Details / Detalles de la Cuenta</h5>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="tx_titular_cuenta" class="form-label">Name</label>
                                    <input type="text" class="form-control @error('tx_titular_cuenta') is-invalid @enderror" 
                                           id="tx_titular_cuenta" 
                                           name="tx_titular_cuenta" 
                                           value="{{ old('tx_titular_cuenta', $aplicacion->tx_titular_cuenta ?? '') }}" 
                                           >
                                           <label class="fixed-label">Titular de la Cuenta</label>                                                                               
                                </div>
                                <div class="mb-3">
                                    <label for="tx_nombre_banco" class="form-label">Bank Name</label>
                                    <input type="text" class="form-control @error('tx_nombre_banco') is-invalid @enderror" 
                                           id="tx_nombre_banco" 
                                           name="tx_nombre_banco" 
                                           value="{{ old('tx_nombre_banco', $aplicacion->tx_nombre_banco ?? '') }}" 
                                           >
                                           <label class="fixed-label">Nombre del Banco</label>                                    
                                </div>
                                <div class="mb-3">
                                    <label for="co_tipo_cuenta" class="form-label">Account Type</label>
                                    <select class="form-select @error('co_tipo_cuenta') is-invalid @enderror" 
                                            id="co_tipo_cuenta" 
                                            name="co_tipo_cuenta" 
                                            >
                                        <option value=""></option>
                                        @foreach ($tiposCuenta as $tipoCuenta)
                                            <option value="{{ $tipoCuenta->co_tipo_cuenta }}" {{ old('co_tipo_cuenta', $aplicacion->co_tipo_cuenta ?? '') == $tipoCuenta->co_tipo_cuenta ? 'selected' : '' }}>{{ $tipoCuenta->tx_tipo_cuenta }}</option>
                                        @endforeach                                        
                                    </select>                                    
                                </div>
                                <div class="mb-3">
                                    <label for="tx_numero_cuenta" class="form-label">Account Number</label>
                                    <input type="text" class="form-control @error('tx_numero_cuenta') is-invalid @enderror" 
                                           id="tx_numero_cuenta" 
                                           name="tx_numero_cuenta" 
                                           value="{{ old('tx_numero_cuenta', $aplicacion->tx_numero_cuenta ?? '') }}" 
                                           maxlength="17">
                                           <label class="fixed-label">Número de Cuenta</label>
                                </div>
                                <div class="mb-3">
                                    <label for="tx_numero_ruta" class="form-label">Routing Number</label>
                                    <input type="text" class="form-control @error('tx_numero_ruta') is-invalid @enderror" 
                                           id="tx_numero_ruta" 
                                           name="tx_numero_ruta" 
                                           value="{{ old('tx_numero_ruta', $aplicacion->tx_numero_ruta ?? '') }}" 
                                           maxlength="9">
                                           <label class="fixed-label">Número de Ruta</label>
                                </div>
                            </div>                            
                        </div>

                        <!-- Terminos y condiciones -->
                        <div class="row mb-4">
                            <div class="col-12 text-center">
                                <h4>Terms and Conditions/ Términos y Condiciones</h4>
                            </div>
                        </div>    
                        <div class="row mb-4">
                            <div class="col-12 text-center">
                                <h4>Signatures/ Firmas</h4>
                            </div>
                        </div>  
                        <!-- Signature Section -->
                        
                            <div class="row mb-3">
                                <div class="col-12 border-bottom pb-2">
                                    I, the primary customer, have reviewed the document above, and by signing, I accept all the terms and conditions contained in it.<span class="required"> *</span>
                                </div>   
                                <div class="col-12 form-group">
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input @error('signature_cliente') is-invalid @enderror"
                                                 type="checkbox" 
                                                 id="signature_cliente"
                                                 name="signature_cliente"
                                                 {{ isset($aplicacion->tx_url_img_signature_c1) && !empty($aplicacion->tx_url_img_signature_c1) ? 'checked' : '' }}
                                                 required>
                                            <label class="form-check-label" for="signature_cliente">
                                                Si
                                            </label>                                    

                                        </div>
                                        <div>
                                        <label class="fixed-label-signature">Yo, el cliente principal, he revisado el documento arriba y, al firmar, acepto todos los términos y condiciones que se encuentran en él.</label>
                                        </div>  
                                </div>
                            </div>                            
                            <div class="row">
                                <div class="col-12 mt-3"
                                 id="signature_container"
                                 style="display: {{ !empty($aplicacion->tx_url_img_signature_c1) ? 'block' : 'none' }};">
                                <!--<label for="signature_canvas_cliente" class="form-label"></label>-->
                                    <canvas id="signature_canvas_cliente" style="border: 1px solid #000; width:100%;"></canvas>                                
                                    <input type="hidden" 
                                           name="tx_url_img_signature_c1" 
                                           id="tx_url_img_signature_c1"
                                           value="{{ old('tx_url_img_signature_c1', $aplicacion->tx_url_img_signature_c1 ?? '') }}">
                                    <div class="col-12">
                                        <button type="button" class="btn btn-danger mt-2" id="clear_signature_primary">Clear Signature</button>
                                    </div>
                                </div>                            
                            </div>

                            <!-- Signature Section Cosigner -->
                            <div class="row mb-3 {{!empty($aplicacion->tx_url_img_signature_c2) ? 'd-block' : 'd-none' }}"
                                 id="signature-container-secondary">
                                <div class="col-12 text-center border-bottom pb-2">
                                    I, the secondary customer, have reviewed the document above, and by signing, I accept all the terms and conditions contained in it.<span class="required">
                                    *</span>
                                </div>
                                <div class="col-12 form-group">
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input @error('signature_cosigner') is-invalid @enderror" 
                                                type="checkbox" 
                                                id="signature_cosigner" 
                                                name="signature_cosigner"
                                                {{ (isset($aplicacion->tx_url_img_signature_c2) && !empty($aplicacion->tx_url_img_signature_c2) ) ? 'checked' : '' }}
                                            required>
                                            <label class="form-check-label" for="signature_cosigner">
                                                Si
                                            </label>

                                        </div>
                                    </div>
                                    <label class="fixed-label-signature">Yo, el cliente secundario, he revisado el documento arriba y, al firmar, acepto todos los términos y condiciones que se encuentran en él.</label>
                                </div>
                                <div class="col-12 mt-3" id="signature_cosigner_container" style="display: {{ !empty($aplicacion->tx_url_img_signature_c2) ? 'block' : 'none' }};">
                                    <!--<label for="signature_canvas_cosigner" class="form-label"></label>-->
                                    <canvas id="signature_canvas_cosigner" style="border: 1px solid #000; width:100%;"></canvas>                                
                                    <input type="hidden" 
                                           name="tx_url_img_signature_c2" 
                                           id="tx_url_img_signature_c2"
                                           value="{{ old('tx_url_img_signature_c2', $aplicacion->tx_url_img_signature_c2 ?? '') }}">
                                    <div class="col-12">
                                    <button type="button" class="btn btn-danger mt-2" id="clear_signature_secondary">Clear Signature</button>
                                </div>
                                </div>                            
                            </div>

                            <!-- Submit Buttons -->
                            <div class="row text-end">
                                <div class="col-12">
                                    <div>
                                        @if(isset($urldestination) && $urldestination != 'account')
                                            <a class="btn btn-secondary" href="{{(route('dashboard.team'))}}">Cancel</a>
                                        @else
                                            <a class="btn btn-secondary" href="{{(route('account'))}}">Cancel</a>
                                        @endif
                                        <!--<button type="button" id="btn-cancel" class="btn btn-secondary">Cancel</button>-->
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </div>
                            
                        
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
{{--<script src="/vendor/jquery-3.7.1.min.js"></script>--}}
<script src="{{ asset('vendor/data-picker-bootstrap5/gijgo.min.js') }}"></script>
<script src="{{ asset('js/decimalInput.js') }}"></script>
<script>
    
     $(document).ready(function() {
     
        $("#co_signer").change(function() {
              
          
          if ($(this).val() === "yes") {
            $("#signature-container-secondary").removeClass("d-none");
            $("#container-secondary").removeClass("d-none");
          } else {
            $("#signature-container-secondary").addClass("d-none");
            $("#container-secondary").addClass("d-none");
            // Limpiar los campos del contenedor secundario
            $('#container-secondary input[type="text"]').val('');
            $('#container-secondary input[type="file"]').val('');
            $('#container-secondary select').val('');
          }
          
        });
      });

      //co_metodo_pago_proyecto
      $(document).ready(function() {
        
        $("#co_metodo_pago_proyecto").change(function() {
           
        const metodoPagoSelect = document.getElementById('co_metodo_pago_proyecto');
        const metodoPagoInput = document.getElementById('tx_metodo_pago');
        const selectedOptionText = metodoPagoSelect.options[metodoPagoSelect.selectedIndex].text;
        // Asignar el texto al input
        metodoPagoInput.value = selectedOptionText;
       if ($(this).val() === "2") {
            $("#container-cosigner").removeClass("d-none");
            $("#container-social-c1").removeClass("d-none");
            $("#container-license-c1").removeClass("d-none");
            $("#container-fe-vencimiento-c1").removeClass("d-none");
            $("#container-continuacion-c1").removeClass("d-none");
            $("#container-references").removeClass("d-none");
            $("#container-finances").removeClass("d-none");
            $("#container-bank").removeClass("d-none");
            $("#tx_social_security_number_c1").prop('required', true);
            $("#tx_licencia_c1").prop('required', true);
            $("#fe_vencimto_licencia_c1").prop('required', true);
            $("#tx_nombre_trabajo_c1").prop('required', true);
            $("#nu_tiempo_trabajo_c1").prop('required', true);
            $("#tx_puesto_c1").prop('required', true);
            $("#nu_salario_mensual_c1").prop('required', true);
            $("#tx_direc1_trab_c1").prop('required', true);
            $("#tx_ciudad_trab_c1").prop('required', true);
            $("#co_estado_trab_c1").prop('required', true);
            $("#tx_zip_trab_c1").prop('required', true);
       } else {
            $("#container-cosigner").addClass("d-none");
            $("#container-social-c1").addClass("d-none");
            $("#container-license-c1").addClass("d-none");
            $("#container-fe-vencimiento-c1").addClass("d-none");
            $("#container-continuacion-c1").addClass("d-none");
            $("#container-references").addClass("d-none");
            $("#container-finances").addClass("d-none");
            $("#container-bank").addClass("d-none");
            //Ocultamos los datos del cliente 2 si estan habilitados
            $("#signature-container-secondary").addClass("d-none");
            $("#container-secondary").addClass("d-none");
            // Limpiar los campos del contenedor principal
            let containers = [
            '#container-cosigner',
            '#container-social-c1',
            '#container-fe-vencimto-c1',
            '#container-continuacion-c1',
            '#container-references',
            '#container-finances',
            '#container-license',
            '#container-bank'
            ];
            containers.forEach(function(container) {
            // Vaciar todos los inputs de tipo text
                $(container + ' input[type="text"]').val('');
                $(container + ' input[type="tel"]').val('');
                $(container + ' input[type="file"]').val('');
            // Vaciar todos los selects
                $(container + ' select').val('');
                //Quitar el required
                $(container + ' input[type="text"], ' + container + ' input[type="tel"]').prop('required', false);
                // Quitar required de todos los selects
                $(container + ' select').prop('required', false);
                // Quitar required de todos los checkboxes
                $(container + ' input[type="checkbox"]').prop('required', false);
                $(container + ' input[type="number"]').prop('required', false);
            });
            $("#tx_licencia_c1").prop('required', false);
            $("#fe_vencimto_licencia_c1").prop('required', false);
            $("#years_employer").prop('required', false);
            //Datos del cliente 2 cambiar el required a false
            $("#tx_relacion_c2_con_c1").prop('required', false);
            $("#tx_primer_nombre_c2").prop('required', false);
            $("#tx_apellido_c2").prop('required', false);
            $("#tx_telefono_c2").prop('required', false);
            $("#tx_email_c2").prop('required', false);
            $("#tx_direccion_c2").prop('required', false);
            //$("#confirm_email_c2").prop('required', false);
            $("#fe_fecha_nacimiento_c2").prop('required', false);
            $("#tx_social_security_number_c2").prop('required', false);
            $("#tx_licencia_c2").prop('required', false);
            $("#fe_vencimto_licencia_c2").prop('required', false);
            $("#tx_nombre_trabajo_c1").prop('required', false);
            $("#monthly_salary").prop('required', false);
            $("#photo_id_c2").prop('required', false);
            $("#tx_nombre_trabajo_c2").prop('required', false);
            $("#nu_tiempo_trabajo_c2").prop('required', false);
            $("#tx_puesto_c2").prop('required', false);
            $("#nu_salario_mensual_c2").prop('required', false);
            //mortgage_company
            $("#mortgage_company").prop('required', false);
       }
       
     });
   });
 </script> 
 <script>  
$(document).ready(function() {
    // Firma Principal
    $('#signature_cliente').change(function() {
        $('#signature_container').toggle(this.checked);
        if (this.checked) {
            setTimeout(() => {
                const canvas = $('#signature_canvas_cliente')[0];
                const ctx = canvas.getContext('2d');
                const rect = canvas.getBoundingClientRect();
                canvas.width = rect.width;
                canvas.height = rect.height;
                ctx.lineWidth = 2;
                ctx.lineCap = 'round';
                ctx.strokeStyle = '#000000';
            }, 100);
        }
    });

    const signatureCanvasPrimary = $('#signature_canvas_cliente')[0];
    const signatureDataInputPrimary = $('#tx_url_img_signature_c1');
    const ctxPrimary = signatureCanvasPrimary.getContext('2d');

    setupCanvas(signatureCanvasPrimary, ctxPrimary, signatureDataInputPrimary, '#clear_signature_primary');

    // Firma Secundaria
    $('#signature_cosigner').change(function() {
        $('#signature_cosigner_container').toggle(this.checked);
        if (this.checked) {
            setTimeout(() => {
                const canvas = $('#signature_canvas_cosigner')[0];
                const ctx = canvas.getContext('2d');
                const rect = canvas.getBoundingClientRect();
                canvas.width = rect.width;
                canvas.height = rect.height;
                ctx.lineWidth = 2;
                ctx.lineCap = 'round';
                ctx.strokeStyle = '#000000';
            }, 100);
        }
    });

    const signatureCanvasSecondary = $('#signature_canvas_cosigner')[0];
    const signatureDataInputSecondary = $('#tx_url_img_signature_c2');
    const ctxSecondary = signatureCanvasSecondary.getContext('2d');

    setupCanvas(signatureCanvasSecondary, ctxSecondary, signatureDataInputSecondary, '#clear_signature_secondary');

    function setupCanvas(canvas, ctx, dataInput, clearButtonSelector) {
        if (!ctx) {
            console.error("Error: No se pudo obtener el contexto 2D del canvas.");
            return;
        }

        // Array para almacenar los puntos de la firma
        let signaturePoints = [];
        let currentStroke = [];

        // Set canvas size
        function resizeCanvas() {
            const oldWidth = canvas.width;
            const oldHeight = canvas.height;
            const rect = canvas.getBoundingClientRect();
            
            // Guardar la imagen actual
            const tempCanvas = document.createElement('canvas');
            tempCanvas.width = oldWidth;
            tempCanvas.height = oldHeight;
            const tempCtx = tempCanvas.getContext('2d');
            tempCtx.drawImage(canvas, 0, 0);

            // Redimensionar el canvas
            canvas.width = rect.width;
            canvas.height = rect.height;
            
            // Restaurar el contexto
            ctx.lineWidth = 2;
            ctx.lineCap = 'round';
            ctx.strokeStyle = '#000000';

            // Redibujar la firma usando los puntos guardados
            if (signaturePoints.length > 0) {
                redrawSignature();
            } else {
                // Si no hay puntos guardados, restaurar la imagen anterior escalada
                ctx.drawImage(tempCanvas, 0, 0, canvas.width, canvas.height);
            }
        }

        function redrawSignature() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            signaturePoints.forEach(stroke => {
                if (stroke.length > 1) {
                    ctx.beginPath();
                    ctx.moveTo(stroke[0].x * canvas.width, stroke[0].y * canvas.height);
                    for (let i = 1; i < stroke.length; i++) {
                        ctx.lineTo(stroke[i].x * canvas.width, stroke[i].y * canvas.height);
                    }
                    ctx.stroke();
                }
            });
        }

        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

        let drawing = false;
        let lastX = 0;
        let lastY = 0;

        function getEventCoordinates(event, canvas) {
            const rect = canvas.getBoundingClientRect();
            if (event.type.includes('touch')) {
                const touch = event.touches[0] || event.changedTouches[0];
                return {
                    x: (touch.clientX - rect.left) / rect.width,
                    y: (touch.clientY - rect.top) / rect.height
                };
            }
            return {
                x: (event.clientX - rect.left) / rect.width,
                y: (event.clientY - rect.top) / rect.height
            };
        }

        function handleStart(event) {
            event.preventDefault();
            drawing = true;
            const coords = getEventCoordinates(event, canvas);
            lastX = coords.x;
            lastY = coords.y;
            currentStroke = [{x: lastX, y: lastY}];
            ctx.beginPath();
            ctx.moveTo(lastX * canvas.width, lastY * canvas.height);
        }

        function handleMove(event) {
            event.preventDefault();
            if (!drawing) return;
            
            const coords = getEventCoordinates(event, canvas);
            currentStroke.push({x: coords.x, y: coords.y});
            
            ctx.beginPath();
            ctx.moveTo(lastX * canvas.width, lastY * canvas.height);
            ctx.lineTo(coords.x * canvas.width, coords.y * canvas.height);
            ctx.stroke();
            
            lastX = coords.x;
            lastY = coords.y;
        }

        function handleEnd(event) {
            event.preventDefault();
            if (drawing) {
                drawing = false;
                if (currentStroke.length > 1) {
                    signaturePoints.push(currentStroke);
                }
                currentStroke = [];
                dataInput.val(canvas.toDataURL());
            }
        }

        // Mouse events
        canvas.addEventListener('mousedown', handleStart);
        canvas.addEventListener('mousemove', handleMove);
        canvas.addEventListener('mouseup', handleEnd);
        canvas.addEventListener('mouseout', handleEnd);

        // Touch events
        canvas.addEventListener('touchstart', handleStart, { passive: false });
        canvas.addEventListener('touchmove', handleMove, { passive: false });
        canvas.addEventListener('touchend', handleEnd, { passive: false });
        canvas.addEventListener('touchcancel', handleEnd, { passive: false });

        // Clear button
        $(clearButtonSelector).click(function() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            signaturePoints = [];
            currentStroke = [];
            dataInput.val('');
        });

        // Si hay una firma existente, cargarla
        if (dataInput.val()) {
            const img = new Image();
            img.onload = function() {
                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
            };
            img.src = dataInput.val();
        }
    }
});

 </script>
<script>
$(document).ready(function() {
    // Función para manejar los required de ambos contenedores
    function toggleInputsRequired() {
        // Para container-finances
        const containerFinances = $('#container-finances');
        const inputsFinances = containerFinances.find('input, select');
        
        if (containerFinances.hasClass('d-none')) {
            inputsFinances.prop('required', false);
        } else {
            inputsFinances.prop('required', true);
        }
        $("#mortgage_company").prop('required', false);
        // Para container-references
        const containerReferences = $('#container-references');
        const inputsReferences = containerReferences.find('input, select');
        
        if (containerReferences.hasClass('d-none')) {
            inputsReferences.prop('required', false);
        } else {
            inputsReferences.prop('required', true);
        }
    }

    // Ejecutar al inicio
    toggleInputsRequired();

    // Ejecutar cuando cambia el método de pago
    $("#co_metodo_pago_proyecto").change(function() {
        if ($(this).val() === "2") {
            $("#container-references").removeClass("d-none");
            $("#container-finances").removeClass("d-none");
        } else {
            $("#container-references").addClass("d-none");
            $("#container-finances").addClass("d-none");
        }
        
        // Actualizar los required después del cambio
        toggleInputsRequired();
    });
});
</script>
<script>
$(document).ready(function() {
    // Función para manejar los required del container-secondary
    function toggleSecondaryInputsRequired() {
        const containerSecondary = $('#container-secondary');
        const inputsSecondary = containerSecondary.find('input, select');
        
        if ($('#co_signer').val() === 'no') {
            inputsSecondary.prop('required', false);
        } else {
            inputsSecondary.prop('required', true);
        }
        $("#tx_inicial_segundo_c2").prop('required', false);
        $("#tx_tlf_trab_c2").prop('required', false);
        $("#tx_direc1_trab_c2").prop('required', false);
        $("#tx_direc2_trab_c2").prop('required', false);
        $("#tx_tlf_trab_c2").prop('required', false);
        $("#tx_ciudad_trab_c2").prop('required', false);
        $("#co_estado_trab_c2").prop('required', false);
        $("#tx_zip_trab_c2").prop('required', false);
        $("#nu_ingresos_alter_c2").prop('required', false);
        $("#tx_url_img_photoid_c2").prop('required', false);
    }

    // Ejecutar al inicio
    toggleSecondaryInputsRequired();

    // Ejecutar cuando cambia el co_signer
    $("#co_signer").change(function() {
        if ($(this).val() === "yes") {
            $("#signature-container-secondary").removeClass("d-none");
            $("#container-secondary").removeClass("d-none");
        } else {
            $("#signature-container-secondary").addClass("d-none");
            $("#container-secondary").addClass("d-none");
        }
        
        // Actualizar los required después del cambio
        toggleSecondaryInputsRequired();
    });
    // Si el co_signer es yes, mostrar el container-secondary
    @if(old('bo_co_signer') == 'yes')
        $("#signature-container-secondary").removeClass("d-none");
        $("#container-secondary").removeClass("d-none");  
        toggleSecondaryInputsRequired();
    @endif
});
</script>
<script>
    $(document).ready(function() {
        $('#fe_instalacion').datepicker({
            dateFormat: 'mm/dd/yy' // Formato de fecha
        });
        $('#fe_fecha_nacimiento_c1').datepicker({
            dateFormat: 'mm/dd/yy' // Formato de fecha
        });
        $('#fe_vencimto_licencia_c1').datepicker({
            dateFormat: 'mm/dd/yy' // Formato de fecha
        });
        $('#fe_fecha_nacimiento_c2').datepicker({
            dateFormat: 'mm/dd/yy' // Formato de fecha
        });
        $('#fe_vencimto_licencia_c2').datepicker({
            dateFormat: 'mm/dd/yy' // Formato de fecha
        });
    });
    
</script>
<script>
    $(document).ready(function() {
        $('#how_long_here').on('change', function() {
            let selectedText = $(this).find('option:selected').text();
            $('#tx_hipoteca_tiempo').val(selectedText);
        });            
    });
</script>
<script>
    $(document).ready(function() {
        
        const metodoPago = "{{ $aplicacion->co_metodo_pago_proyecto ?? '' }}";
        const coSigner = "{{ $aplicacion->bo_co_signer ?? '' }}";
        if(metodoPago != "2"){            
            $("#container-cosigner").addClass("d-none");
            $("#container-social-c1").addClass("d-none");
            $("#container-license-c1").addClass("d-none");
            $("#container-fe-vencimiento-c1").addClass("d-none");
            $("#container-continuacion-c1").addClass("d-none");
            $("#container-references").addClass("d-none");

            $("#container-finances").addClass("d-none");
            //Ocultamos los datos del cliente 2 si estan habilitados
            $("#signature-container-secondary").addClass("d-none");
            $("#container-secondary").addClass("d-none");
            $("#container-bank").addClass("d-none");
            // Limpiar los campos del contenedor principal
            let containers = [
            '#container-cosigner',
            '#container-social-c1',
            '#container-fe-vencimto-c1',
            '#container-continuacion-c1',
            '#container-references',
            '#container-finances',
            '#container-license',
            '#container-secondary',
            '#container-bank'
            ];
            containers.forEach(function(container) {
            // Vaciar todos los inputs de tipo text

                $(container + ' input[type="text"]').val('');
                $(container + ' input[type="tel"]').val('');
                $(container + ' input[type="file"]').val('');
            // Vaciar todos los selects
                $(container + ' select').val('');
                //Quitar el required
                $(container + ' input[type="text"], ' + container + ' input[type="tel"]').prop('required', false);
                // Quitar required de todos los selects
                $(container + ' select').prop('required', false);
                // Quitar required de todos los checkboxes
                $(container + ' input[type="checkbox"]').prop('required', false);
                $(container + ' input[type="number"]').prop('required', false);
            });
            $("#tx_licencia_c1").prop('required', false);
            $("#fe_vencimto_licencia_c1").prop('required', false);
            $("#years_employer").prop('required', false);
            $("#signature_cosigner").prop('required', false);
            //Datos del cliente 2 cambiar el required a false
            
            
            $("#tx_relacion_c2_con_c1").prop('required', false);
            $("#tx_primer_nombre_c2").prop('required', false);
            $("#tx_apellido_c2").prop('required', false);
            $("#tx_telefono_c2").prop('required', false);
            $("#tx_email_c2").prop('required', false);
            $("#tx_direccion_c2").prop('required', false);
            $("#confirm_email_c2").prop('required', false);
            $("#fe_fecha_nacimiento_c2").prop('required', false);
            $("#tx_social_security_number_c2").prop('required', false);
            $("#tx_licencia_c2").prop('required', false);
            $("#fe_vencimto_licencia_c2").prop('required', false);
            $("#tx_nombre_trabajo_c1").prop('required', false);
            $("#monthly_salary").prop('required', false);
            $("#photo_id_c2").prop('required', false);
            $("#tx_nombre_trabajo_c2").prop('required', false);
            $("#nu_tiempo_trabajo_c2").prop('required', false);
            $("#tx_puesto_c2").prop('required', false);
            $("#nu_salario_mensual_c2").prop('required', false);            
            $("#tx_url_img_photoid_c2").prop('required', false);
            //mortgage_company
            $("#mortgage_company").prop('required', false);

        }else{
            if(coSigner){
                //$("#signature-container-secondary").removeClass("d-none");
                $("#container-secondary").removeClass("d-none");
                $("#tx_inicial_segundo_c2").prop('required', false);
                    $("#tx_tlf_trab_c2").prop('required', false);
                    $("#tx_direc1_trab_c2").prop('required', false);
                    $("#tx_direc2_trab_c2").prop('required', false);
                    $("#tx_tlf_trab_c2").prop('required', false);
                    $("#tx_ciudad_trab_c2").prop('required', false);
                    $("#co_estado_trab_c2").prop('required', false);
                    $("#tx_zip_trab_c2").prop('required', false);
                    $("#nu_ingresos_alter_c2").prop('required', false);
            }else{
                $("#signature_cosigner").prop('required', false);
            }
        }
    
    });
</script>
@if(!empty($aplicacion->tx_url_img_signature_c1))
<script>
    document.addEventListener('DOMContentLoaded', function() {        

        const canvas = document.getElementById('signature_canvas_cliente');
        const ctx = canvas.getContext('2d');
        /*
        // Establecer dimensiones iniciales del canvas
        canvas.style.width = '100%';
        canvas.style.height = 'auto';
        canvas.style.display = 'block';
        canvas.style.border = '1px solid #000';
        */
        
        const img = new Image();
        img.src = "{{ asset('storage/' . $aplicacion->tx_url_img_signature_c1) }}";
        
        img.onload = function() {
            // Ajustar el tamaño del canvas al tamaño de la imagen
            canvas.width = img.width;
            canvas.height = img.height;
            
            // Dibujar la imagen en el canvas
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
        }
    });
</script>
@endif
@if(!empty($aplicacion->tx_url_img_signature_c2))
<script>
    document.addEventListener('DOMContentLoaded', function() {        

        const canvas = document.getElementById('signature_canvas_cosigner');
        const ctx = canvas.getContext('2d');
        /*
        // Establecer dimensiones iniciales del canvas
        canvas.style.width = '100%';
        canvas.style.height = 'auto';
        canvas.style.display = 'block';
        canvas.style.border = '1px solid #000';
        */
        
        const img = new Image();
        img.src = "{{ asset('storage/' . $aplicacion->tx_url_img_signature_c2) }}";
        
        img.onload = function() {
            // Ajustar el tamaño del canvas al tamaño de la imagen
            canvas.width = img.width;
            canvas.height = img.height;
            
            // Dibujar la imagen en el canvas
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
        }
    });
</script>
@endif
<script>
    $(document).ready(function() {
        const form = $('#generalInfoForm');
    
        // Obtener los campos
        const ssn1 = $('#tx_social_security_number_c1');
        const ssn2 = $('#tx_social_security_number_c2');
        const photoC1 = $('#tx_url_img_photoid_c1');
        const photoC1Old = $('#tx_url_img_photoid_c1_old');
        const account_number = $('#tx_numero_cuenta');
        const routing_number = $('#tx_numero_ruta');
        const tx_ciudad_c1 = $('#tx_ciudad_c1');
        const tx_ciudad_trab_c1 = $('#tx_ciudad_trab_c1');
        const metodo_pago_proyecto = $('#co_metodo_pago_proyecto');
        const tx_total_price = $('#nu_precio_total');
        form.on('submit', function(e) {
            let isValid = true;
            let firstInvalidField = null;
            function validateTotalPrice($input) {
                const valor = parseFloat($input.val()) || 0;
                
                if (valor < 1) {
                    $input.addClass('is-invalid');
                    
                    let $errorDiv = $input.next('.invalid-feedback');
                    if (!$errorDiv.length) {
                        $errorDiv = $('<div class="invalid-feedback">');
                        $input.after($errorDiv);
                    }
                    $errorDiv.text('El precio total debe ser mayor a uno');
                    
                    if (!firstInvalidField) {
                        firstInvalidField = $input;
                    }
                    
                    return false;
                }
                
                $input.removeClass('is-invalid');
                $input.next('.invalid-feedback').remove();
                return true;
            }

            
            function validateAccountNumber($input) {
                // Verificar si el campo tiene un valor
                if (!$input.val() || $input.val().trim() === '') {
                    $input.removeClass('is-invalid');
                    return true;
                }
                
                const valor = $input.val().trim();
                const soloDigitos = /^\d+$/.test(valor);
                const longitudValida = valor.length <= 17;
                
                if (!soloDigitos || !longitudValida) {
                    $input.addClass('is-invalid');
                    
                    let $errorDiv = $input.next('.invalid-feedback');
                    if (!$errorDiv.length) {
                        $errorDiv = $('<div class="invalid-feedback">');
                        $input.after($errorDiv);
                    }
                    
                    if (!soloDigitos) {
                        $errorDiv.text('El número de cuenta debe contener solo dígitos');
                    } else {
                        $errorDiv.text('El número de cuenta debe tener máximo 17 dígitos');
                    }
                    
                    if (!firstInvalidField) {
                        firstInvalidField = $input;
                    }
                    
                    return false;
                }
                
                $input.removeClass('is-invalid');
                return true;
            }

            // Función para validar Routing Number
            function validateRoutingNumber($input) {
                // Verificar si el campo tiene un valor
                if (!$input.val() || $input.val().trim() === '') {
                    $input.removeClass('is-invalid');
                    return true;
                }
                
                const valor = $input.val().trim();
                const soloDigitos = /^\d+$/.test(valor);
                const longitudExacta = valor.length === 9;
                
                if (!soloDigitos || !longitudExacta) {
                    $input.addClass('is-invalid');
                    
                    let $errorDiv = $input.next('.invalid-feedback');
                    if (!$errorDiv.length) {
                        $errorDiv = $('<div class="invalid-feedback">');
                        $input.after($errorDiv);
                    }
                    
                    if (!soloDigitos) {
                        $errorDiv.text('El número de ruta debe contener solo dígitos');
                    } else {
                        $errorDiv.text('El número de ruta debe tener exactamente 9 dígitos');
                    }
                    
                    if (!firstInvalidField) {
                        firstInvalidField = $input;
                    }
                    
                    return false;
                }
                
                $input.removeClass('is-invalid');
                return true;
            }
            // Función para validar SSN
            function validateSSN($input) {
                if ($input.prop('required') && (!$input.val() || $input.val().replace(/\D/g, '').length !== 9)) {
                    $input.addClass('is-invalid');
                
                    if (!firstInvalidField) {
                        firstInvalidField = $input;
                    }
                
                    let $errorDiv = $input.next('.invalid-feedback');
                    if (!$errorDiv.length) {
                        $errorDiv = $('<div class="invalid-feedback">');
                        $input.after($errorDiv);
                    }
                    $errorDiv.text('El número de seguro social debe tener 9 dígitos');
                
                    return false;
                }
            
                $input.removeClass('is-invalid');
                return true;
            }
        
            // Función para validar el formato de fecha mm/dd/yyyy
            function validarFormatoFecha(fecha) {
                if (!fecha) return false;
            
                const formatoFecha = /^(0[1-9]|1[0-2])\/(0[1-9]|[12][0-9]|3[01])\/\d{4}$/;
                if (!formatoFecha.test(fecha)) return false;

                const partes = fecha.split('/');
                const mes = parseInt(partes[0], 10);
                const dia = parseInt(partes[1], 10);
                const año = parseInt(partes[2], 10);
            
                const fechaObj = new Date(año, mes - 1, dia);
                return fechaObj.getMonth() === mes - 1 && 
                   fechaObj.getDate() === dia && 
                   fechaObj.getFullYear() === año;
            }

            //Función para validar la foto
            function validatePhoto($input, $oldInput) {
         
                if ($input.prop('required') && !$input.val() && !$oldInput.val()) {
                    $input.addClass('is-invalid');
                    if (!firstInvalidField) {
                        firstInvalidField = $input;
                    }
                    let $errorDiv = $input.next('.invalid-feedback');
                    if (!$errorDiv.length) {
                        $errorDiv = $('<div class="invalid-feedback">');
                        $input.after($errorDiv);
                    }
                    $errorDiv.text('La foto es requerida');
                    return false;
                }
                $input.removeClass('is-invalid');
            
                return true;
            }            

            function validateLengthCity($input) {
                //$cityLong = $input.val().trim().length;
                if ($input.val().trim().length < 3) {
                    $input.addClass('is-invalid');
                
                    if (!firstInvalidField) {
                        firstInvalidField = $input;
                    }
            
                    let $errorDiv = $input.next('.invalid-feedback');
                    if (!$errorDiv.length) {
                        $errorDiv = $('<div class="invalid-feedback">');
                        $input.after($errorDiv);
                    }
                    $errorDiv.text('La ciudad debe tener al menos 3 caracteres');
            
                    return false;
                }
        
                $input.removeClass('is-invalid');
                return true;
            }

            // Validar SSN
            if (ssn1.length) {
                isValid = validateSSN(ssn1) && isValid;
            }
        
            if (ssn2.length) {
                isValid = validateSSN(ssn2) && isValid;
            }

            if (photoC1.length) {          
                isValid = validatePhoto(photoC1, photoC1Old) && isValid;            
            }

            if (tx_ciudad_c1.length) {
                isValid = validateLengthCity(tx_ciudad_c1) && isValid;
            }

            if ( metodo_pago_proyecto.val() == 2 && tx_ciudad_trab_c1.length ) {
                isValid = validateLengthCity(tx_ciudad_trab_c1) && isValid;
            }

            //validate system price
            if (tx_total_price.length) {
                isValid = validateTotalPrice(tx_total_price) && isValid;
            }
            
            // Campos datepicker a validar
            const datepickerFields = [
                { id: '#fe_instalacion', label: 'Fecha de Instalación' },
                { id: '#fe_fecha_nacimiento_c1', label: 'Fecha de Nacimiento del Cliente Principal' },
                { id: '#fe_vencimto_licencia_c1', label: 'Fecha de Vencimiento de Licencia del Cliente Principal' },
                { id: '#fe_fecha_nacimiento_c2', label: 'Fecha de Nacimiento del Cliente Cofirmante' },
                { id: '#fe_vencimto_licencia_c2', label: 'Fecha de Vencimiento de Licencia del Cofirmante' }
            ];

            // Validar cada campo datepicker
            datepickerFields.forEach(field => {
                const $campo = $(field.id);
                const valor = $campo.val().trim();
            
                // Solo validar si el campo tiene un valor o es requerido
                if (valor !== '' || $campo.prop('required')) {
                    if (!validarFormatoFecha(valor)) {
                        isValid = false;
                        $campo.addClass('is-invalid');
                    
                        let $errorDiv = $campo.next('.invalid-feedback');
                        if (!$errorDiv.length) {
                            $errorDiv = $('<div class="invalid-feedback">');
                            $campo.after($errorDiv);
                        }
                        $errorDiv.text('Formato mm/dd/yyyy');
                    
                        if (!firstInvalidField) {
                            firstInvalidField = $campo;
                        }
                } else {
                    $campo.removeClass('is-invalid');
                    $campo.next('.invalid-feedback').remove();
                }
            }
            });

            if (account_number.length) {
                isValid = validateAccountNumber(account_number) && isValid;                
            }
            // Validar Routing Number            
            if (routing_number.length) {
                isValid = validateRoutingNumber(routing_number) && isValid;                
            }

            // Si hay errores, prevenir el envío y enfocar el primer campo con error
            if (!isValid) {
                e.preventDefault();
                if (firstInvalidField) {
                    if (firstInvalidField.attr('id').startsWith('fe_')) {  
                        firstInvalidField.val('');
                        firstInvalidField.datepicker({
                            dateFormat: 'mm/dd/yy',
                            defaultDate: new Date()
                        });                                            
                    }
                    firstInvalidField.focus();
                }
            }
        });
    
        // Limpiar errores cuando el usuario modifica los campos
        if (ssn1.length) {
            ssn1.on('input', function() {
                $(this).removeClass('is-invalid')
                   .next('.invalid-feedback').remove();
            });
        }
    
        if (ssn2.length) {
          ssn2.on('input', function() {
            $(this).removeClass('is-invalid')
                   .next('.invalid-feedback').remove();
            });
        }

        if (photoC1.length) {
            photoC1.on('change', function() {
                $(this).removeClass('is-invalid')
                   .next('.invalid-feedback').text('');
                });
        }

        //Limpiar errores cuando el usuario modifica los campos de Account Number
        if (account_number.length) {
            account_number.on('input', function() {
            $(this).removeClass('is-invalid')
                   .next('.invalid-feedback').remove();
            });
        }
        if (routing_number.length) {
            routing_number.on('input', function() {
            $(this).removeClass('is-invalid')
                   .next('.invalid-feedback').remove();
            });
        }   

        $('#tx_ciudad_c1').on('input', function() {
            $(this).removeClass('is-invalid');
        });

        $('#tx_ciudad_trab_c1').on('input', function() {
            $(this).removeClass('is-invalid');
        });
        // Agregar este evento para limpiar el error cuando se escribe:
        if (tx_total_price .length) {
            tx_total_price .on('input', function() {
                $(this).removeClass('is-invalid')
                    .next('.invalid-feedback').remove();
            });
        }

        $('.datepicker, [id^="fe_"]').on('change', function() {
            $(this).removeClass('is-invalid')
                   .next('.invalid-feedback').remove();
        });

    });    
</script>
<script>
    $(document).ready(function() {
        $('#tx_url_img_photoid_c2').on('change', function() {
            if(this.files && this.files.length > 0) {
                $('#actual_photo_id_c2').hide();
            } else {
                $('#actual_photo_id_c2').show();
            }
        });
    });
</script>
<script>
        $(document).ready(function() {
            $('#tx_url_img_photoid_c1').on('change', function() {
                if(this.files && this.files.length > 0) {
                    $('#actual_photo_id_c1').hide();
                } else {
                    $('#actual_photo_id_c1').show();
                }
            });
        });
</script>
<script>
    $(document).ready(function() {
        const precioTotal = $('#nu_precio_total');
        const downPayment = $('#nu_down_payment');
        const precioFinanciado = $('#nu_precio_financiado');
    
        function calcularPrecioFinanciado() {
            let total = parseFloat(precioTotal.val()) || 0;
            let inicial = parseFloat(downPayment.val()) || 0;
        
            // Validar que inicial no sea mayor que total
            if (inicial > total) {
                //inicial = total;
                downPayment.val('0.00');
                precioFinanciado.val(total.toFixed(2));
                return;
            }
        
            if (inicial > 0) {
                precioFinanciado.val((total - inicial).toFixed(2));
            } else {
                precioFinanciado.val(total.toFixed(2));
            }
        }
    
        precioTotal.on('input', calcularPrecioFinanciado);
        downPayment.on('input', calcularPrecioFinanciado);
    });
    $('.decimal-input').each(function() {
        new DecimalInput(this);
    });
</script>
@endpush