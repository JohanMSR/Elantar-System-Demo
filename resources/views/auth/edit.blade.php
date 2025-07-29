@extends('layouts.master')

@section('title')
@lang('translation.user_registration') - @lang('translation.business-center')
@endsection

@push('css')
    <link href="{{ asset('vendor/data-tables-bootstrap5/datatables.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/data-picker-bootstrap5/gijgo.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('vendor/apexcharts-bundle/apexcharts.css') }}" rel="stylesheet" type="text/css" />
    <style>
        :root {
            --color-primary: #13c0e6;
            --color-primary-dark: #10a5c6;
            --color-secondary: #4687e6;
            --color-secondary-dark: #3472c9;
            --color-accent: #8ce04f;
            --color-accent-dark: #7ac843;
            --color-dark: #162d92;
            --color-text: #495057;
            --color-light-text: #6c757d;
            --color-border: #eaeaea;
            --color-input-bg: #ffffff;
            --color-input-bg-hover: #f8f9fa;
            --shadow-card: 0 12px 30px rgba(19, 192, 230, 0.25);
            --shadow-btn: 0 5px 15px rgba(70, 135, 230, 0.3);
            --shadow-input: 0 2px 4px rgba(0, 0, 0, 0.05);
            --transition-normal: all 0.3s ease;
            --transition-slow: all 0.5s ease;
            --transition-fast: all 0.2s ease;
            --radius-sm: 4px;
            --radius-md: 8px;
            --radius-lg: 15px;
        }
        
        .dashboard-card {
            background-color: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-card);
            padding: 2rem;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: var(--transition-normal);
            margin-bottom: 2rem;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(19, 192, 230, 0.35);
        }

        .dashboard-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--color-primary), transparent);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.5s ease;
        }

        .dashboard-card:hover::after {
            transform: scaleX(1);
        }

        .card-header-custom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid var(--color-border);
            padding-bottom: 1rem;
        }

        .card-title {
            font-family: "Montserrat", sans-serif;
            font-weight: 600;
            font-size: 1.25rem;
            color: var(--color-dark);
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
        }

        .card-title i {
            color: var(--color-primary);
            margin-right: 0.75rem;
            font-size: 1.5rem;
        }

        .card-subtitle {
            font-size: 0.9rem;
            color: var(--color-light-text);
        }
        
        .form-label {
            font-weight: 500;
            color: var(--color-text);
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            border: 2px solid var(--color-border);
            transition: var(--transition-normal);
            height: 45px;
            padding: 0.5rem 1rem;
            border-radius: var(--radius-md);
            font-size: 1rem;
            letter-spacing: 0.025em;
            box-shadow: var(--shadow-input);
            background-color: var(--color-input-bg);
        }
        
        .form-control:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(19, 192, 230, 0.15);
            outline: none;
            background-color: var(--color-input-bg);
        }
        
        .form-control:hover {
            border-color: rgb(75, 193, 247);
            background-color: var(--color-input-bg-hover);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--color-secondary), var(--color-primary));
            border: none;
            transition: var(--transition-normal);
            border-radius: var(--radius-md);
            font-weight: 600;
            letter-spacing: 0.5px;
            overflow: hidden;
            position: relative;
            z-index: 1;
            padding: 0.75rem 2rem;
            box-shadow: var(--shadow-btn);
        }
        
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: var(--transition-slow);
            z-index: -1;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--color-secondary-dark), var(--color-primary-dark));
            transform: translateY(-2px);
            box-shadow: 0 7px 20px rgba(70, 135, 230, 0.4);
        }
        
        .btn-primary:hover::before {
            left: 100%;
        }
        
        .btn-primary:active {
            transform: translateY(1px);
            box-shadow: 0 3px 10px rgba(70, 135, 230, 0.3);
        }

        .btn-primary:focus {
            box-shadow: 0 0 0 3px rgba(70, 135, 230, 0.3);
        }
        
        .fade-in {
            opacity: 0;
            animation: fadeIn 0.6s ease-in-out forwards;
        }
        
        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(-5px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        
        .img-perfil-inside {
            width: 152px;
            height: 152px;
            gap: 0px;
            border: 1px 0px 0px 0px;
            opacity: 0px;
            /* top: 28px; */
            cursor: pointer;
            box-shadow: rgba(0, 0, 0, 0.05) 0px 0px 0px 1px;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            border: 4px solid var(--color-primary);
        }

        .img-perfil-inside:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(19, 192, 230, 0.3);
        }
        
        .content_img_profile {
            position: relative;
            margin-bottom: 1.5rem;
        }
        
        h5 {
            color: var(--color-text);
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .btn-secondary {
            background: rgba(70, 135, 230, 0.1);
            color: var(--color-secondary);
            border: none;
            transition: var(--transition-normal);
        }
        
        .btn-secondary:hover {
            background: rgba(70, 135, 230, 0.2);
            color: var(--color-secondary-dark);
        }
        
        .btn-danger {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border: none;
            transition: var(--transition-normal);
        }
        
        .btn-danger:hover {
            background: rgba(220, 53, 69, 0.2);
            color: #b02a37;
        }
    </style>
@endpush

@section('content')
@php
    $pageTitle = 'Mi Perfil';
@endphp
<div class="container-fluid fade-in">
    <x-page-header :title="$pageTitle" icon="user" />
    <br>
    <div class="dashboard-card">
        <div class="card-header-custom">
            <div>
                <h5 class="card-title"><i class="fas fa-user"></i> Información personal</h5>
                <p class="card-subtitle">Actualice sus datos personales</p>
            </div>
        </div>
        
        <form action="{{route('setting.update')}}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('put')

            {{-- IMAGEN --}}
        
            <div class="position-relative justify-content-center align-items-center d-flex">
                <div class="col-md-3 mb-3 text-dark text-center"> 
                    <div class="content_img_profile" id="content_img_profile">
                        @if ($user->image_path && !$errors->get('image_profile'))
                            <img id="imagepreview" class="rounded-circle img-perfil-inside" 
                                aria-expanded="false" src="{{url('storage/' . $user->image_path) }}" alt="img profile">
                        @else
                            <img id="imagepreview" class="rounded-circle img-perfil-inside"
                                aria-expanded="false" src="{{ asset('img/profile/no.png') }}" alt="img profile">
                        @endif
                        <a id="remove-img-a" src="#"
                            class="remove-img btn btn-danger position-absolute d-none"
                            data-bs-toggle="tooltip" title="Eliminar">
                            <i style="width:10px;"  data-feather="trash-2"></i>
                        </a>
                        <a id="edit-img-a" src="#"
                            class="btn btn-secondary position-absolute"
                            data-bs-toggle="tooltip" title="Editar">
                            <i style="width:10px;"  data-feather="edit"></i>
                        </a>         
                    </div>
                    @if ($errors->get('image_profile'))
                        <div class="d-block invalid-feedback">
                            <div class="d-flex flex-column mb-3">
                                 @foreach ((array) $errors->get('image_profile') as $message)
                                    <div>{{ $message }}</div>
                                @endforeach
                            </div>
                        </div>
                    @endif
            </div>
            </div>
            <input class="d-none" type="file" name="image_profile" id="image_input_file"
            accept="image/*">        

        {{-- FIN IMAGEN --}}
        
            <div class="row justify-content-center">
                 <div class="col-md-3 mb-3 text-dark text-justify">
                    <label for="tx_rol">
                        <h5>Rol</h5>
                    </label>
                    <input 
                        type="type" 
                        class="form-control border-success" 
                        id="tx_rol" 
                        name="tx_rol" 
                        placeholder="Rol" 
                        value="{{old('tx_rol',$user->tx_rol)}}"
                        readonly disabled>
                </div>
                <div class="col-md-3 mb-3 text-dark text-justify">                    
                </div>
                
            </div>    
            <div class="row justify-content-center">
                
                <div class="col-md-3 mb-3 text-dark text-justify">
                    <label for="office">
                        <h5>Mi Oficina</h5>
                    </label>
                    <input 
                        type="type" 
                        class="form-control border-success" 
                        id="office" 
                        name="office" 
                        placeholder="Mi Oficina" 
                        value="{{old('office',$user->office_city)}}"
                        readonly disabled>
                </div>
                <div class="col-md-3 mb-3 text-dark text-justify">
                    <label for="manager">
                        <h5>{{$user->rol_manager}}</h5>
                    </label>
                    <input 
                        type="text" 
                        class="form-control border-secondary" 
                        id="manager"
                        name="manager"
                        value="{{old('manager',$user->manager_full_name)}}"
                        placeholder="Manager"
                        readonly disabled>
                </div>   
            </div>
            <div class="row justify-content-center">
                <div class="col-md-6 mb-3 text-dark text-justify">
                    <label for="email">
                        <h5>Email</h5>
                    </label>
                    <input 
                        type="text" 
                        class="form-control border-secondary" 
                        id="email"
                        name="email"
                        value="{{old('email',$user->tx_email)}}"
                        placeholder="example_2341@example.com"
                        readonly disabled>
                </div>   
            </div>                 
                <div class="row justify-content-center">
                    <div class="col-md-3 mb-3 text-dark text-justify">
                        <label for="first_name">
                            <h5>Primer Nombre</h5>
                        </label>
                        <input 
                            type="text" 
                            class="form-control border-secondary @error('first_name') is-invalid @enderror" 
                            id="first_name"
                            name="first_name"
                            value="{{old('first_name',$user->tx_primer_nombre)}}"
                            placeholder=""
                            readonly disabled>
                            @error('first_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                    </div>
                    <div class="col-md-3 mb-3 text-dark text-justify">
                        <label for="secon_name">
                            <h5>Segundo Nombre</h5>
                        </label>
                        <input 
                            type="text" 
                            class="form-control border-secondary @error('second_name') is-invalid @enderror" 
                            id="second_name"
                            name="second_name"
                            value="{{old('second_name',$user->tx_segundo_nombre)}}"
                            placeholder=""
                            readonly disabled>
                            @error('second_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-3 mb-3 text-dark text-justify">
                    <label for="first_lastname">
                        <h5>Primer Apellido</h5>
                    </label>
                    <input 
                        type="text" 
                        class="form-control border-secondary @error('first_lastname') is-invalid @enderror" 
                        id="first_lastname"
                        name="first_lastname"
                        value="{{old('first_lastname',$user->tx_primer_apellido)}}"
                        placeholder=""
                        readonly disabled>
                        @error('first_lastname')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                    </div>
                    <div class="col-md-3 mb-3 text-dark text-justify">
                    <label for="second_lastname">
                        <h5>Segundo Apellido</h5>
                    </label>
                    <input 
                        type="text" 
                        class="form-control border-secondary @error('second_lastname') is-invalid @enderror" 
                        id="second_lastname"
                        name="second_lastname"
                        value="{{old('second_lastname',$user->tx_segundo_apellido)}}"
                        placeholder=""
                        readonly disabled>
                        @error('second_lastname')
                            <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    </div>
                    
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-3 mb-3 text-dark text-justify">
                        <label for="phone">
                            <h5>Número Telefónico</h5>
                        </label>
                        <input 
                            type="tel" 
                            class="form-control border-secondary @error('phone') is-invalid @enderror" 
                            id="phone"
                            name="phone"
                            value="{{old('phone',$user->tx_telefono)}}"
                            placeholder="(555) 555-5555"
                            {{--pattern="^\+?([0-9]{1,3})?\s?\(?([0-9]{3})\)?[- ]?([0-9]{3})[- ]?([0-9]{4})$"--}}
                            required>
                            @error('phone')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                    </div>
                    <div class="col-md-3 mb-3">

                    </div>
                </div>
                <br>
                <div class="row justify-content-center">
                 <div class="col-md-6 mb-3 text-dark text-justify">
                    <label for="first_location">
                        <h5>Dirección 1</h5>
                    </label>
                    <input 
                        type="text" 
                        class="form-control border-secondary @error('first_location') is-invalid @enderror" 
                        id="first_location"
                        name="first_location"
                        value="{{old('first_location',$user->tx_direccion1)}}"
                        placeholder="Estado, Ciudad, codigo zip">
                            @error('first_location')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-6 mb-3 text-dark text-justify">
                    <label for="second_location">
                        <h5>Dirección 2</h5>
                    </label>
                    <input 
                        type="text" 
                        class="form-control border-secondary @error('second_location') is-invalid @enderror" 
                        id="second_location"
                        name="second_location"
                        value="{{old('second_location',$user->tx_direccion2)}}"
                        placeholder="Dirección 2">
                        @error('second_location')
                                <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <br>
                <div class="row justify-content-center">
                    <div class="col-md-6 mb-3 text-dark text-center">
                     <input 
                        type="submit" 
                        class="btn btn-primary" 
                        value="Actualizar"
                        >
                        <input 
                        type="button" 
                        class="btn btn-secondary" 
                        value="Cancelar"
                        form="formReset"> 
                    </div>
                    
                </div>                
        </div>        
    </form>
</div>
    <form id="formReset" action="{{ route('setting.backLastUrl') }}" method="GET">

    </form>
</div>
@endsection

@push('scripts')
<script>
    $('input[value=Cancelar]').click(function(e){
        e.preventDefault();
        let formulario = $('#formReset');
        formulario.submit();
        //window.history.back();
    })

    @if ($message = Session::get("success"))    
            window.onload = function() {
                Swal.fire({
                    title: "Éxito!",
                    text: "{{$message}}",
                    icon: "success"
                });
            };
    @elseif($message = Session::get("danger"))    
            window.onload = function() {
                Swal.fire({
                    title: "Advertencia!",
                    text: "{{$message}}",
                    icon: "warning"
                });
            };
    @endif
    
    @if ($errors->has('image_profile'))
        $('#imagepreview').focus();    
    @elseif ($errors->has('phone'))
        $('#phone').focus();
    @elseif($errors->has('first_location'))        
        $('#first_location').focus()
    @elseif($errors->has('second_location'))            
        $('#second_location').focus()
    @endif
        

</script>
<script>
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        
        const btnImgProfile = document.getElementById('imagepreview');//document.querySelector("#imagepreview");
        btnImgProfile.addEventListener('click', function() {
            document.getElementById("image_input_file").click();
        });

        const btnImgEdit = document.getElementById('edit-img-a');//document.querySelector("#imagepreview");
        btnImgEdit.addEventListener('click', function() {
            document.getElementById("image_input_file").click();            
        });
        

        const imageInputFile = document.querySelector("#image_input_file");
        imageInputFile.addEventListener('change', function() {
            
            const file = this.files[0];

            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    btnImgProfile.src = e.target.result;
                };
                reader.readAsDataURL(file);
                document.getElementById('edit-img-a').classList.add("d-none");
                document.getElementById('remove-img-a').classList.remove("d-none");
            } else {
                imageInputFile.value ="";
                Swal.fire({
                    title: "Advertencia!",
                    text: "Por favor seleccione una imagen",
                    icon: "warning"
                });            
            }

        });

        const removeImgA = document.querySelector("#remove-img-a");
        removeImgA.addEventListener('click', function() {
            btnImgProfile.src = "{{ asset('img/profile/img_preview.webp') }}";
            document.getElementById('edit-img-a').classList.remove("d-none");
            document.getElementById('remove-img-a').classList.add("d-none");
            document.getElementById("image_input_file").value = "";
        });
</script>

@endpush