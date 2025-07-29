@extends('layouts.master')

@section('title')
    @lang('translation.title_notifications') - @lang('translation.business_center')
@endsection

@push('styles')
<style>
    .card {
        border: none;
        border-radius: 8px;
    }
    .card .card-header {
        background: #0d6efd !important;
        border-bottom: 2px solid #e9ecef;
        padding: 1rem;
        color: white !important;
    }
    .card .card-header h5 {
        color: white !important;
        font-weight: 600;
        margin: 0;
    }

    .section-divider {
        height: 2px;
        background: #e9ecef;
        margin: 1rem 0;
    }
</style>
@endpush

@section('content')
@php  
    $bandRregistro = '0';
    $registroMensaje = '';
    if (session()->exists('success')) {
        $bandRregistro = '1';
        $registroMensaje = session('success');
        session()->forget('success');
    } else{
        if (session()->exists('error')) {
            $bandRregistro = '2';
            $registroMensaje = session('error');
            session()->forget('error');
        }    
    }
@endphp
<div class="container-fluid">
     @if ($errors->any())
        <div class="row justify-content-center align-items-center">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif
        <div clas="row">
            <div class="col-12">
                <br />
                <h5 id="principal-head">@lang('translation.title_create_notification')</h5>
            </div>
            <hr>
        </div>
        <br>
        <div class="col-12 col-lg-8 mx-auto">
            <form action="{{ route('notifications.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf                
                <div class="row justify-content-center align-items-center mb-4">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-user me-2"></i>Crear Notificación
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="form-group mx-auto">
                                        <div class="col-md-6 mb-3">
                                            
                                            @php
                                                $tipoSeleccionado = 6; // El valor que quieres preseleccionar
                                                $textoTipoSeleccionado = '';
                                                foreach($tiposNotificacion ?? [] as $tipo) {
                                                    if($tipo->co_tiponoti == $tipoSeleccionado) {
                                                        $textoTipoSeleccionado = $tipo->tx_descripcion;
                                                        break;
                                                    }
                                                }
                                            @endphp
                                            <label for="co_tiponoti" class="form-label">Tipo de Notificación</label>
                                            <input type="text" class="form-select" value="{{ $textoTipoSeleccionado }}" readonly>
                                            <input type="hidden" id="co_tiponoti" name="co_tiponoti" value="{{ $tipoSeleccionado }}">
                                            {{--
                                            <label for="co_tiponoti" class="form-label">Tipo de Notificación</label>
                                            <select id="co_tiponoti" name="co_tiponoti" class="form-select h-38px @error('co_tiponoti') is-invalid @enderror" required>
                                                
                                                @foreach($tiposNotificacion ?? [] as $tipo)
                                                    <option value="{{ $tipo->co_tiponoti }}" {{$tipo->co_tiponoti == 6 ? 'selected': ''}}>{{ $tipo->tx_descripcion }}</option>
                                                @endforeach
                                            </select>
                                            --}}
                                            @error('co_tiponoti')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="tx_info_general" class="form-label">Notificación</label>
                                            <textarea id="tx_info_general" name="tx_info_general" 
                                                class="form-control 
                                                @error('tx_info_general') is-invalid @enderror"
                                                rows="4"
                                                placeholder="Ingrese el texto de la Notificación" 
                                                style="resize: none;"
                                                required></textarea>
                                            @error('tx_info_general')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="d-flex flex-wrap align-items-center justify-content-center gap-3">
                                            <button type="submit" class="btn btn-primary h-38px d-inline-flex align-items-center justify-content-center px-3">
                                                <i data-feather="save" class="feather-sm me-1"></i>
                                                Crear Notificación
                                            </button>
                                            
                                            <button type="reset" class="btn btn-secondary h-38px d-inline-flex align-items-center justify-content-center px-3">
                                                <i data-feather="refresh-ccw" class="feather-sm me-1"></i>
                                                Restablecer
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>            
        </div>
    </div>
</div>
@push('scripts')
<script>
        const bandRregistro = "{{ $bandRregistro }}";
        const registroMensaje = "{{ $registroMensaje }}";
        window.onload = function() {
            
            if (bandRregistro == "1") {
                Swal.fire({
                    title: "Exito!",
                    text: registroMensaje,
                    icon: "success"
                });
            }else{
                 if (bandRregistro == "2") {
                    Swal.fire({
                        icon: "info",
                        title: "Información",
                        text: registroMensaje
                    });
                }
            }
        }
        
</script>
@endpush
@endsection 