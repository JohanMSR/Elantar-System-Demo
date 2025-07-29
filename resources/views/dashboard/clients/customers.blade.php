@extends('layouts.master')

@section('title')
    @lang('translation.customers_title') - @lang('translation.business-center')
@endsection

@push('css')
<link href="{{ asset('vendor/data-tables-bootstrap5/datatables.min.css') }}" rel="stylesheet" />
<link href="{{ asset('vendor/data-picker-bootstrap5/gijgo.css') }}" rel="stylesheet" type="text/css" />
<style>
        .btnRango{
            background-color: #318ce7;
            margin-bottom: 5px;
        }
        .campo-date {
            margin-bottom: 5px;
        }
            .tabla-informe {
        display: block;
        overflow: auto;
        width: 300px; /* Ajusta el ancho según tus necesidades */
    }
</style>
@endpush

@section('content')
    <div class="container-fluid">
        <div clas="row">
            <div class="col-12">
                <br />
                <h5 id="principal-head">Mis Clientes</h5>
            </div>
        </div>
        <div clas="row">
            <div class="col-12">
                <hr>
                    <div class="row row row-col-md-auto g-md-3 align-items-center">
                        <div class="col-12 col-md-auto">
                            <label for="colFormLabelSm" class="form-label">Desde</label>
                        </div>
                        <div class="col-12 col-md-auto">
                            <input id="startDate" name="startDate" type="text" class="form-control campo-date"
                                placeholder="mm/dd/aa" aria-label="startDate">
                        </div>
                        <div class="col-12 col-md-auto">
                            <label for="endDate" class="form-label">Hasta</label>
                        </div>
                        <div class="col-12 col-md-auto">
                            <input id="endDate" type="text" name="endDate" class="form-control campo-date"
                                placeholder="mm/dd/aa" aria-label="endDate">
                        </div>
                        <div class="col-12 col-md-auto button">
                            <button class="btn btn-primary btnRango" title="Buscar"
                                type="submit">Buscar</button>
                        </div>
                        <div class="col-12 col-md-auto button">

                        <div class="modal fade" id="myModal">
                            <div class="modal-dialog modal-dialog-centered modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title"><i class=""
                                        data-feather="book-open"></i> Informacion de nuevo Lead</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                    <div class="container-fluid">
                                    <form class="needs-validation" novalidate>
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                            <label for="validationCustom01">Nombres</label> 
                                            <input type="text" class="form-control" id="validationCustom01" placeholder="" required>
                                            <div class="valid-feedback">
                                                Campo llenado correctamente
                                            </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                            <label for="validationCustom02">Apellidos</label>
                                            <input type="text" class="form-control" id="validationCustom02" placeholder="" required>
                                            <div class="valid-feedback">
                                                Campo llenado correctamente
                                            </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                            <label for="validationCustomUsername">Email</label>
                                                <div class="input-group">
                                                    <input type="email" class="form-control" id="validationCustomUsername" placeholder="" aria-describedby="inputGroupPrepend" required>
                                                    <div class="invalid-feedback">
                                                        Ingrese un correo electronico valido.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">    
                                                <label for="validationCustom03">City</label>
                                                <input type="text" class="form-control" id="validationCustom03" placeholder="" required>
                                                <div class="invalid-feedback">
                                                    Ingrese una ciudad valida.
                                                </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                <label for="validationCustom04">State</label>
                                                <select id="ValidationCustom04" class="form-control" required>
                                                    <option value="" selected></option>
                                                    <option value="AK">Alaska</option>
                                                    <option value="AL">Alabama</option>
                                                    <option value="AR">Arkansas</option>
                                                    <option value="CA">California</option>
                                                    <option value="CO">Colorado</option>
                                                    <option value="CT">Connecticut</option>
                                                    <option value="DE">Delaware</option>
                                                    <option value="FL">Florida</option>
                                                    <option value="GA">Georgia</option>
                                                    <option value="HI">Hawái</option>
                                                    <option value="ID">Idaho</option>
                                                    <option value="AZ">Arizona</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Seleccion una opcion de Estado.
                                                </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                <label for="validationCustom05">Zip</label>
                                                <input type="text" class="form-control" id="validationCustom05" placeholder="" required>
                                                <div class="invalid-feedback">
                                                    Codigo Zip invalido.
                                                </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                <label for="validationCustom05">Direccion</label>
                                                <input type="text" class="form-control" id="validationCustom05" placeholder="" required>
                                                <div class="invalid-feedback">
                                                    Ingrese una direccion valida.
                                                </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                <label for="validationCustom05">Direccion 2</label>
                                                <input type="text" class="form-control" id="validationCustom05" placeholder="" required>
                                                <div class="invalid-feedback">
                                                    Ingrese una direccion valida.
                                                </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                <label for="validationCustom05">Numero Telefonico</label>
                                                <input type="text" class="form-control" id="validationCustom05" placeholder="" required>
                                                <div class="invalid-feedback">
                                                    Ingrese un numero de telefono valido.
                                                </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="form-check col-mb-6">
                                                <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                                                <label class="form-check-label" for="invalidCheck">
                                                    Estoy segur@ que he ingresado los datos correctamente
                                                </label>
                                                <div class="invalid-feedback">
                                                    Debes confirmar el recuadro anterior
                                                </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-mb-6 text-center align-items-center">
                                                <button class="btn btn-primary btnRango" type="submit" class="text-center">Subir Lead</button>
                                            </div>
                                            </div>
                                            
                                            </form>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-auto">
                            <select class="form-select" aria-label="Select">
                                <option selected>Ordernar por</option>
                                <option value="1">Ciudad</option>
                                <option value="2">Fecha de Creacion</option>
                                <option value="3">Estado</option>
                            </select>
                        </div>
                    </div>
                    
                </div>
                <div>
                <br>
                <div class="table-responsive">
                <table class="table rounded-3 overflow-hidden table-hover table-bordered align-middle">
                                        <thead>
                                                <tr class="table-primary custom-row align-middle text-center">
                                                        <th class="col-1">ID</th>
                                                        <th class="col-1">Nombre</th>
                                                        <th class="col-1">Apellido</th>
                                                        <th class="col-2">Numero de Telefono</th>
                                                        <th class="col-1">Estado</th>
                                                        <th class="col-1">Ciudad</th>
                                                        <th class="col-4">Direccion</th>
                                                        <th class="col-1">Zip</th>
                                                        <th class="col-1">Acciones</th>
                                                        <th class="col-1">Fecha</th>
                                                </tr>
                                        </thead>
                                        <tbody data-bs-toggle="modal" data-bs-target="#myModal1">
                                                <tr class="text-center">
                                                        <td class="table-secondary">251523</td>
                                                        <td class="table-success">Juan</td>
                                                        <td class="table-success">Marques</td>
                                                        <td class="table-info">(555) 555-5555</td>
                                                        <td>Georgia</td>
                                                        <td>Lecheria</td>
                                                        <td>
                                                            Calle R8, Res Costa del Sol TH6
                                                        </td>
                                                        <td class="table-warning">60162</td>
                                                        <td class="table-secondary"><div class="row"><div class="col-6 text-center"><i  data-feather="message-circle"></i></div><div class="col-6 text-center"><i  data-feather="edit"></i></div></div></td>
                                                        <td>05/07/2023</td>
                                                </tr>
                                                <tr class="text-center">
                                                        <td class="table-secondary">251523</td>
                                                        <td class="table-success">Juan</td>
                                                        <td class="table-success">Marques</td>
                                                        <td class="table-info">(555) 555-5555</td>
                                                        <td>Georgia</td>
                                                        <td>Lecheria</td>
                                                        <td>
                                                            Calle R8, Res Costa del Sol TH6
                                                        </td>
                                                        <td class="table-warning">60162</td>
                                                        <td class="table-secondary"><div class="row"><div class="col-6 text-center"><i  data-feather="message-circle"></i></div><div class="col-6 text-center"><i  data-feather="edit"></i></div></div></td>
                                                        <td>05/07/2023</td>
                                                </tr>
                                                <tr class="text-center">
                                                        <td class="table-secondary">251523</td>
                                                        <td class="table-success">Juan</td>
                                                        <td class="table-success">Marques</td>
                                                        <td class="table-info">(555) 555-5555</td>
                                                        <td>Georgia</td>
                                                        <td>Lecheria</td>
                                                        <td>
                                                            Calle R8, Res Costa del Sol TH6
                                                        </td>
                                                        <td class="table-warning">60162</td>
                                                        <td class="table-secondary"><div class="row"><div class="col-6 text-center"><i  data-feather="message-circle"></i></div><div class="col-6 text-center"><i  data-feather="edit"></i></div></div></td>
                                                        <td>05/07/2023</td>
                                                </tr>
                                                <tr class="text-center">
                                                        <td class="table-secondary">251523</td>
                                                        <td class="table-success">Juan</td>
                                                        <td class="table-success">Marques</td>
                                                        <td class="table-info">(555) 555-5555</td>
                                                        <td>Georgia</td>
                                                        <td>Lecheria</td>
                                                        <td>
                                                            Calle R8, Res Costa del Sol TH6
                                                        </td>
                                                        <td class="table-warning">60162</td>
                                                        <td class="table-secondary"><div class="row"><div class="col-6 text-center"><i  data-feather="message-circle"></i></div><div class="col-6 text-center"><i  data-feather="edit"></i></div></div></td>
                                                        <td>05/07/2023</td>
                                                </tr>
                                                <tr class="text-center">
                                                        <td class="table-secondary">251523</td>
                                                        <td class="table-success">Juan</td>
                                                        <td class="table-success">Marques</td>
                                                        <td class="table-info">(555) 555-5555</td>
                                                        <td>Georgia</td>
                                                        <td>Lecheria</td>
                                                        <td>
                                                            Calle R8, Res Costa del Sol TH6
                                                        </td>
                                                        <td class="table-warning">60162</td>
                                                        <td class="table-secondary"><div class="row"><div class="col-6 text-center"><i  data-feather="message-circle"></i></div><div class="col-6 text-center"><i  data-feather="edit"></i></div></div></td>
                                                        <td>05/07/2023</td>
                                                </tr>
                                                <tr class="text-center">
                                                        <td class="table-secondary">251523</td>
                                                        <td class="table-success">Juan</td>
                                                        <td class="table-success">Marques</td>
                                                        <td class="table-info">(555) 555-5555</td>
                                                        <td>Georgia</td>
                                                        <td>Lecheria</td>
                                                        <td>
                                                            Calle R8, Res Costa del Sol TH6
                                                        </td>
                                                        <td class="table-warning">60162</td>
                                                        <td class="table-secondary"><div class="row"><div class="col-6 text-center"><i  data-feather="message-circle"></i></div><div class="col-6 text-center"><i  data-feather="edit"></i></div></div></td>
                                                        <td>05/07/2023</td>
                                                </tr>
                                                <tr class="text-center">
                                                        <td class="table-secondary">251523</td>
                                                        <td class="table-success">Juan</td>
                                                        <td class="table-success">Marques</td>
                                                        <td class="table-info">(555) 555-5555</td>
                                                        <td>Georgia</td>
                                                        <td>Lecheria</td>
                                                        <td>
                                                            Calle R8, Res Costa del Sol TH6
                                                        </td>
                                                        <td class="table-warning">60162</td>
                                                        <td class="table-secondary"><div class="row"><div class="col-6 text-center"><i  data-feather="message-circle"></i></div><div class="col-6 text-center"><i  data-feather="edit"></i></div></div></td>
                                                        <td>05/07/2023</td>
                                                </tr>
                                                <tr class="text-center">
                                                        <td class="table-secondary">251523</td>
                                                        <td class="table-success">Juan</td>
                                                        <td class="table-success">Marques</td>
                                                        <td class="table-info">(555) 555-5555</td>
                                                        <td>Georgia</td>
                                                        <td>Lecheria</td>
                                                        <td>
                                                            Calle R8, Res Costa del Sol TH6
                                                        </td>
                                                        <td class="table-warning">60162</td>
                                                        <td class="table-secondary"><div class="row"><div class="col-6 text-center"><i  data-feather="message-circle"></i></div><div class="col-6 text-center"><i  data-feather="edit"></i></div></div></td>
                                                        <td>05/07/2023</td>
                                                </tr>
                                                <tr class="text-center">
                                                        <td class="table-secondary">251523</td>
                                                        <td class="table-success">Juan</td>
                                                        <td class="table-success">Marques</td>
                                                        <td class="table-info">(555) 555-5555</td>
                                                        <td>Georgia</td>
                                                        <td>Lecheria</td>
                                                        <td>
                                                            Calle R8, Res Costa del Sol TH6
                                                        </td>
                                                        <td class="table-warning">60162</td>
                                                        <td class="table-secondary"><div class="row"><div class="col-6 text-center"><i  data-feather="message-circle"></i></div><div class="col-6 text-center"><i  data-feather="edit"></i></div></div></td>
                                                        <td>05/07/2023</td>
                                                </tr>
                                                <tr class="text-center">
                                                        <td class="table-secondary">251523</td>
                                                        <td class="table-success">Juan</td>
                                                        <td class="table-success">Marques</td>
                                                        <td class="table-info">(555) 555-5555</td>
                                                        <td>Georgia</td>
                                                        <td>Lecheria</td>
                                                        <td>
                                                            Calle R8, Res Costa del Sol TH6
                                                        </td>
                                                        <td class="table-warning">60162</td>
                                                        <td class="table-secondary"><div class="row"><div class="col-6 text-center"><i  data-feather="message-circle"></i></div><div class="col-6 text-center"><i  data-feather="edit"></i></div></div></td>
                                                        <td>05/07/2023</td>
                                                </tr>
                                                <tr class="text-center">
                                                        <td class="table-secondary">251523</td>
                                                        <td class="table-success">Juan</td>
                                                        <td class="table-success">Marques</td>
                                                        <td class="table-info">(555) 555-5555</td>
                                                        <td>Georgia</td>
                                                        <td>Lecheria</td>
                                                        <td>
                                                            Calle R8, Res Costa del Sol TH6
                                                        </td>
                                                        <td class="table-warning">60162</td>
                                                        <td class="table-secondary"><div class="row"><div class="col-6 text-center"><i  data-feather="message-circle"></i></div><div class="col-6 text-center"><i  data-feather="edit"></i></div></div></td>
                                                        <td>05/07/2023</td>
                                                </tr>
                                                <tr class="text-center">
                                                        <td class="table-secondary">251523</td>
                                                        <td class="table-success">Juan</td>
                                                        <td class="table-success">Marques</td>
                                                        <td class="table-info">(555) 555-5555</td>
                                                        <td>Georgia</td>
                                                        <td>Lecheria</td>
                                                        <td>
                                                            Calle R8, Res Costa del Sol TH6
                                                        </td>
                                                        <td class="table-warning">60162</td>
                                                        <td class="table-secondary"><div class="row"><div class="col-6 text-center"><i  data-feather="message-circle"></i></div><div class="col-6 text-center"><i  data-feather="edit"></i></div></div></td>
                                                        <td>05/07/2023</td>
                                                </tr>
                                                <tr class="text-center">
                                                        <td class="table-secondary">251523</td>
                                                        <td class="table-success">Juan</td>
                                                        <td class="table-success">Marques</td>
                                                        <td class="table-info">(555) 555-5555</td>
                                                        <td>Georgia</td>
                                                        <td>Lecheria</td>
                                                        <td>
                                                            Calle R8, Res Costa del Sol TH6
                                                        </td>
                                                        <td class="table-warning">60162</td>
                                                        <td class="table-secondary"><div class="row"><div class="col-6 text-center"><i  data-feather="message-circle"></i></div><div class="col-6 text-center"><i  data-feather="edit"></i></div></div></td>
                                                        <td>05/07/2023</td>
                                                </tr>
                                                <tr class="text-center">
                                                        <td class="table-secondary">251523</td>
                                                        <td class="table-success">Juan</td>
                                                        <td class="table-success">Marques</td>
                                                        <td class="table-info">(555) 555-5555</td>
                                                        <td>Georgia</td>
                                                        <td>Lecheria</td>
                                                        <td>
                                                            Calle R8, Res Costa del Sol TH6
                                                        </td>
                                                        <td class="table-warning">60162</td>
                                                        <td class="table-secondary"><div class="row"><div class="col-6 text-center"><i  data-feather="message-circle"></i></div><div class="col-6 text-center"><i  data-feather="edit"></i></div></div></td>
                                                        <td>05/07/2023</td>
                                                </tr>
                                                <tr class="text-center">
                                                        <td class="table-secondary">251523</td>
                                                        <td class="table-success">Juan</td>
                                                        <td class="table-success">Marques</td>
                                                        <td class="table-info">(555) 555-5555</td>
                                                        <td>Georgia</td>
                                                        <td>Lecheria</td>
                                                        <td>
                                                            Calle R8, Res Costa del Sol TH6
                                                        </td>
                                                        <td class="table-warning">60162</td>
                                                        <td class="table-secondary"><div class="row"><div class="col-6 text-center"><i  data-feather="message-circle"></i></div><div class="col-6 text-center"><i  data-feather="edit"></i></div></div></td>
                                                        <td>05/07/2023</td>
                                                </tr>
                                                <tr class="text-center">
                                                        <td class="table-secondary">251523</td>
                                                        <td class="table-success">Juan</td>
                                                        <td class="table-success">Marques</td>
                                                        <td class="table-info">(555) 555-5555</td>
                                                        <td>Georgia</td>
                                                        <td>Lecheria</td>
                                                        <td>
                                                            Calle R8, Res Costa del Sol TH6
                                                        </td>
                                                        <td class="table-warning">60162</td>
                                                        <td class="table-secondary"><div class="row"><div class="col-6 text-center"><i  data-feather="message-circle"></i></div><div class="col-6 text-center"><i  data-feather="edit"></i></div></div></td>
                                                        <td>05/07/2023</td>
                                                </tr class="text-center">
                                        </tbody>
                                        <div class="modal fade" id="myModal1">
                            <div class="modal-dialog modal-dialog-centered modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title"><i class="" data-feather="bookmark"></i> Informacion de
                                            Cliente</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-md-2 mb-3 text-dark text-center">
                                                    <label for="forma_1">
                                                        <h5>ID</h5>
                                                    </label>
                                                    <input type="text" class="form-control border-success" id="forma_1"
                                                        placeholder="251523" readonly>
                                                </div>
                                                <div class="col-md-5 mb-3 text-dark text-center">
                                                    <label for="forma_1">
                                                        <h5>Nombres</h5>
                                                    </label>
                                                    <input type="text" class="form-control border-primary" id="forma_1"
                                                        placeholder="Enrique" readonly>
                                                </div>
                                                <div class="col-md-5 mb-3 text-dark text-center">
                                                    <label for="form_1">
                                                        <h5>Apellido</h5>
                                                    </label>
                                                    <input type="text" class="form-control border-primary" id="forma_1"
                                                        placeholder="Rivero" readonly>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-2 mb-3 text-dark text-center">
                                                    <label for="forma_1">
                                                        <h5>Estado</h5>
                                                    </label>
                                                    <input type="text" class="form-control border-secondary"
                                                        id="forma_1" placeholder="Georgia" readonly>
                                                </div>
                                                <div class="col-md-2 mb-3 text-dark text-center">
                                                    <label for="forma_1">
                                                        <h5>Ciudad</h5>
                                                    </label>
                                                    <input type="text" class="form-control border-secondary"
                                                        id="forma_1" placeholder="Lecheria" readonly>
                                                </div>
                                                <div class="col-md-6 mb-3 text-dark text-center">
                                                    <label for="form_1">
                                                        <h5>Direccion</h5>
                                                    </label>
                                                    <input type="text" class="form-control border-secondary"
                                                        id="forma_1" placeholder="Calle R8, Res Costa del Sol TH6"
                                                        readonly>
                                                </div>
                                                <div class="col-md-2 mb-3 text-dark text-center">
                                                    <label for="form_1">
                                                        <h5>ZIP</h5>
                                                    </label>
                                                    <input type="text" class="form-control border-warning" id="forma_1"
                                                        placeholder="18102" readonly>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-6 mb-3 text-dark text-center">
                                                    <label for="form_1">
                                                        <h5>Numero Telefonico</h5>
                                                    </label>
                                                    <input type="text" class="form-control border-info" id="forma_1"
                                                        placeholder="(555) 555-5555" readonly>
                                                </div>
                                                <div class="col-md-5 mb-3 text-dark text-center">
                                                    <label for="form_1">
                                                        <h5>Email</h5>
                                                    </label>
                                                    <input type="text" class="form-control border-info" id="forma_1"
                                                        placeholder="example_2341@example.com" readonly>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="progress m-3">
                                                <div name="partbar" class="progress-bar bg-primary" role="progressbar" style="width: 0%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <div class="row m-2">
                                                <div class="col-12 align-middle">
                                                <button onclick="check1(), changeColor()" id="btn1" class="btn btn-secondary col-2 m-1 text-wrap">Pre-Calificacion</button>
                                                <button onclick="check2(), changeColor2()" id="btn2" class="btn btn-secondary col-3 m-1 text-wrap">Formularios de autorización de pago</button>
                                                <button onclick="check3(), changeColor3()" id="btn3" class="btn btn-secondary col-2 m-1 text-wrap">Agregar Foto</button>
                                                <button onclick="check4(), changeColor4()" id="btn4" class="btn btn-secondary col-2 m-1 text-wrap">Verificacion de Documentos</button>
                                                <button onclick="check5(), changeColor5()" id="btn5" class="btn btn-secondary col-2 m-1 text-wrap">Prueba de calidad del agua</button>
                                                </div>
                                        </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                                </table></div>        
            </div>
        </div>
        </br>
    </div>
@endsection

@push('scripts')
    <script>
        const partbar = document.getElementsByName('partbar');
        const btnprogressbar1 =document.getElementById('btn1');
        const btnprogressbar2 =document.getElementById('btn2');
        const btnprogressbar3 =document.getElementById('btn3');
        const btnprogressbar4 =document.getElementById('btn4');
        const btnprogressbar5 =document.getElementById('btn5');
        function changeColor(){
            btnprogressbar1.className = "btn btn-primary col-2 m-1 text-wrap";
        }
        function changeColor2(){
            if (btnprogressbar1.className === "btn btn-primary col-2 m-1 text-wrap"){
                btnprogressbar2.className = "btn btn-primary col-3 m-1 text-wrap";
            }
        }
        function changeColor3(){
            if (btnprogressbar1.className === "btn btn-primary col-2 m-1 text-wrap" && btnprogressbar2.className === "btn btn-primary col-3 m-1 text-wrap"){
                btnprogressbar3.className = "btn btn-primary col-2 m-1 text-wrap";
            }
        }
        function changeColor4(){
            if (btnprogressbar1.className === "btn btn-primary col-2 m-1 text-wrap" && btnprogressbar2.className === "btn btn-primary col-3 m-1 text-wrap" && btnprogressbar3.className === "btn btn-primary col-2 m-1 text-wrap"){
                btnprogressbar4.className = "btn btn-primary col-2 m-1 text-wrap";
            }
        }
        function changeColor5(){
            if (btnprogressbar1.className === "btn btn-primary col-2 m-1 text-wrap" && btnprogressbar2.className === "btn btn-primary col-3 m-1 text-wrap" && btnprogressbar3.className === "btn btn-primary col-2 m-1 text-wrap" && btnprogressbar4.className === "btn btn-primary col-2 m-1 text-wrap"){
                btnprogressbar5.className = "btn btn-primary col-2 m-1 text-wrap";
            } 
        }

        function check1(){
            for (const boton of partbar) {
                boton.style.width = "20%"

            }
        }
        function check2(){
            if (btnprogressbar1.className === "btn btn-primary col-2 m-1 text-wrap"){
                for (const boton of partbar) {
                    boton.style.width = "40%"
    
                }
            }
        }
        function check3(){
            for (const boton of partbar) {
                if (btnprogressbar1.className === "btn btn-primary col-2 m-1 text-wrap" && btnprogressbar2.className === "btn btn-primary col-3 m-1 text-wrap"){
                for (const boton of partbar) {
                    boton.style.width = "60%"
    
                }
            }
            }
        }
        function check4(){
            if (btnprogressbar1.className === "btn btn-primary col-2 m-1 text-wrap" && btnprogressbar2.className === "btn btn-primary col-3 m-1 text-wrap" && btnprogressbar3.className === "btn btn-primary col-2 m-1 text-wrap"){
                for (const boton of partbar) {
                boton.style.width = "80%"
                }
            }
        }
        function check5(){
            if (btnprogressbar1.className === "btn btn-primary col-2 m-1 text-wrap" && btnprogressbar2.className === "btn btn-primary col-3 m-1 text-wrap" && btnprogressbar3.className === "btn btn-primary col-2 m-1 text-wrap" && btnprogressbar4.className === "btn btn-primary col-2 m-1 text-wrap"){
                for (const boton of partbar) {
                boton.style.width = "100%"

                }
            }
        }
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function () {
            'use strict';
            window.addEventListener('load', function () {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName('needs-validation');
                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function (form) {
                    form.addEventListener('submit', function (event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
<script>
        activateOption('opc6');
        activateOption2('icon-customers');
    </script>
@endpush
