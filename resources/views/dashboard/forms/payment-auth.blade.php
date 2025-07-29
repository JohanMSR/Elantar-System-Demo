@extends('layouts.master')

@section('title')
    Payment Authorization Form - Business Center
@endsection

@section('content')
<style>
    .form-group {
        position: relative;
        margin-bottom: 20px;
    }

    .fixed-label {        
        position: absolute;
        bottom: 10;
        left: 25;
        font-size: 12px;
        color: #6c757d;
        pointer-events: none;
        margin-bottom: 10px;
    }

    .no-rounded-border {
        border-radius: 0;
        border: 1px solid #ced4da;
        padding: 8px;
    }

    .no-rounded-border:focus {
        box-shadow: none;
        border-color: #80bdff;
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
        color: red;
    }
</style>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <br><br>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Payment Authorization Form</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="paymentAuthForm" method="POST" action="{{ route('forms.payment-auth.store') }}">
                        @csrf
                        <input type="hidden" name="co_aplicacion" id="co_aplicacion" value="{{ request('co_aplicacion') }}">

                        <!-- Payment Information -->
                        <div class="row mb-4">
                            <div class="col-12 text-center">
                                <h5 class="border-bottom pb-2">Payment Authorization Form</h5>
                            </div>
                            
                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount</label>
                                    <input type="number" 
                                           step="0.01" 
                                           class="form-control @error('nu_monto') is-invalid @enderror" 
                                           id="nu_monto" 
                                           name="nu_monto" 
                                           value="{{ old('nu_monto') }}"
                                           required>
                                    <label class="fixed-label">Cantidad</label>
                                </div>
                            </div>

                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="tx_nombre" class="form-label">Name as appears on card</label>
                                    <input type="text" 
                                           class="form-control @error('tx_nombre') is-invalid @enderror" 
                                           id="tx_nombre" 
                                           name="tx_nombre" 
                                           value="{{ old('tx_nombre') }}"
                                           required>
                                    <label class="fixed-label">Nombre como aparece en la tarjeta</label>
                                </div>
                            </div>

                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="tx_telefono" class="form-label">Customer Phone</label>
                                    <input type="tel" 
                                           class="form-control @error('tx_telefono') is-invalid @enderror" 
                                           id="tx_telefono" 
                                           name="tx_telefono" 
                                           value="{{ old('tx_telefono') }}"
                                           required>
                                    <label class="fixed-label">Teléfono del Cliente</label>
                                </div>
                            </div>

                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="tx_email" class="form-label">Customer Email</label>
                                    <input type="email" 
                                           class="form-control @error('tx_email') is-invalid @enderror" 
                                           id="tx_email" 
                                           name="tx_email" 
                                           value="{{ old('tx_email') }}"
                                           required>
                                    <label class="fixed-label">Correo Electrónico del Cliente</label>
                                </div>
                            </div>

                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="confirm_email" class="form-label">Confirm Email</label>
                                    <input type="email" 
                                           class="form-control @error('confirm_email') is-invalid @enderror" 
                                           id="confirm_email" 
                                           name="confirm_email" 
                                           value="{{ old('confirm_email') }}"
                                           required>
                                    <label class="fixed-label">Confirmar Correo Electrónico</label>
                                </div>
                            </div>
                        </div>

                        <!-- Billing Address -->
                        <div class="row mb-4">
                            <div class="col-12 text-center">
                                <h5 class="border-bottom pb-2">Billing Address</h5>
                            </div>

                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <label for="tx_direccion1" class="form-label">Address Line 1</label>
                                    <input type="text" 
                                           class="form-control @error('tx_direccion1') is-invalid @enderror" 
                                           id="tx_direccion1" 
                                           name="tx_direccion1" 
                                           value="{{ old('tx_direccion1') }}"
                                           required>
                                    <label class="fixed-label">Dirección Línea 1</label>
                                </div>
                            </div>

                            <div class="col-12 form-group">
                                <div class="mb-3">
                                    <input type="text" 
                                           class="form-control @error('tx_direccion2') is-invalid @enderror" 
                                           id="tx_direccion2" 
                                           name="tx_direccion2" 
                                           value="{{ old('tx_direccion2') }}">
                                    <label class="fixed-label">Dirección Línea 2</label>
                                </div>
                            </div>

                            <div class="col-md-4 form-group">
                                <div class="mb-3">
                                    <label for="tx_ciudad" class="form-label">City</label>
                                    <input type="text" 
                                           class="form-control @error('tx_ciudad') is-invalid @enderror" 
                                           id="tx_ciudad" 
                                           name="tx_ciudad" 
                                           value="{{ old('tx_ciudad') }}"
                                           required>
                                    <label class="fixed-label">Ciudad</label>
                                </div>
                            </div>

                            <div class="col-md-4 form-group">
                                <div class="mb-3">
                                    <label for="co_estado" class="form-label">State</label>
                                    <select class="form-select @error('co_estado') is-invalid @enderror" 
                                            id="co_estado" 
                                            name="co_estado"
                                            required>
                                        <option value="">Select a state</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state->co_estado }}" {{ old('co_estado') == $state->co_estado ? 'selected' : '' }}>
                                                {{ $state->tx_nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label class="fixed-label">Estado</label>
                                </div>
                            </div>

                            <div class="col-md-4 form-group">
                                <div class="mb-3">
                                    <label for="tx_zip" class="form-label">ZIP Code</label>
                                    <input type="text" 
                                           class="form-control @error('tx_zip') is-invalid @enderror" 
                                           id="tx_zip" 
                                           name="tx_zip" 
                                           value="{{ old('tx_zip') }}"
                                           required>
                                    <label class="fixed-label">Código Postal</label>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="row text-end">
                            <div class="col-12">
                                <div>
                                    <a class="btn btn-secondary" href="{{ route('shop') }}">Cancel</a>
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
<br><br><br>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Email confirmation validation
    $('#paymentAuthForm').on('submit', function(e) {
        const email = $('#tx_email').val();
        const confirmEmail = $('#confirm_email').val();
        
        if (email !== confirmEmail) {
            e.preventDefault();
            alert('Email addresses do not match');
            return false;
        }
    });
});
</script>
@endpush