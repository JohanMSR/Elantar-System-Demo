@extends('layouts.master')

@section('title')
    @lang('translation.user_registration') - @lang('translation.business-center')
@endsection

@push('css')
    <style>
        .img_profile {
            width: 150px;
            height: 150px;
            border: 1px solid #ccc;
            margin-top: 20px;
        }

        #previewImage {
            width: 150px;
            height: 150px;
        }

        .remove-img {
            top: -10px;
            right: -5px;
            color: white;
            width: 25px;
            height: 30px;
            padding: 0;
        }

        .remove-img>i {
            color: white;
            width: 5px;
            height: 5px;
            margin: 0;
        }
    </style>
@endpush

@section('content')

    @php
        $bandRregistro = '0';
        $registroMensaje = '';
        if (session()->exists('success_register')) {
            $bandRregistro = '1';
            $registroMensaje = session('success_register');
        } else {
            if (session()->exists('error')) {
                $bandRregistro = '2';
                $registroMensaje = session('error');
            }
        }
    @endphp
    <div class="container pt-5">
        <div class="card">
            <div class="card-header">
                <h5>@lang('translation.user_registration')</h5>
            </div>
            <div class="card-body">
                @error('title')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <p class="lead fs-6">Ingresar los datos de la cuenta de acceso del usuario.</p>
                <div class="row">
                    <div class="col">
                        <div class="mx-md-4">
                            <form method="POST" action="{{ route('storeacces') }}" class="row needs-validation"
                                enctype="multipart/form-data" novalidate>
                                @csrf
                                <div class="col-12">
                                    <label for="name" class="form-label">@lang('translation.name')</label>
                                    <input type="text" class="form-control mb-3" id="name"
                                        value="{{ old('name') }}" name="name" required>
                                    @if ($errors->get('name'))
                                        <div class="d-block invalid-feedback">
                                            <div class="d-flex flex-column mb-3">
                                                @foreach ((array) $errors->get('name') as $message)
                                                    <div>{{ $message }}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-12">
                                    <label for="surname" class="form-label">@lang('translation.surname')</label>
                                    <input type="text" class="form-control mb-3" id="surname" name="surname"
                                        value="{{ old('surname') }}" required>
                                    @if ($errors->get('surname'))
                                        <div class="d-block invalid-feedback">
                                            <div class="d-flex flex-column mb-3">
                                                @foreach ((array) $errors->get('surname') as $message)
                                                    <div>{{ $message }}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-12">
                                    <label for="email" class="form-label">@lang('translation.email')</label>
                                    <input type="text" class="form-control mb-3" id="email"
                                        value="{{ old('email') }}" name="email" required>
                                    @if ($errors->get('email'))
                                        <div class="d-block invalid-feedback">
                                            <div class="d-flex flex-column mb-3">
                                                @foreach ((array) $errors->get('email') as $message)
                                                    <div>{{ $message }}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-12">
                                    <label for="password" class="form-label">@lang('translation.password')</label>
                                    <input type="password" class="form-control mb-3" id="password" name="password"
                                        autocomplete="new-password" required>
                                    @if ($errors->get('password'))
                                        <div class="d-block invalid-feedback">
                                            <div class="d-flex flex-column mb-3">
                                                @foreach ((array) $errors->get('password') as $message)
                                                    <div>{{ $message }}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-12">
                                    <label for="password_confirmation" class="form-label">@lang('translation.confirm_password')</label>
                                    <input type="password" class="form-control mb-3" id="password_confirmation"
                                        name="password_confirmation" autocomplete="new-password" required>
                                    @if ($errors->get('password_confirmation'))
                                        <div class="d-block invalid-feedback">
                                            <div class="d-flex flex-column mb-3">
                                                @foreach ((array) $errors->get('password_confirmation') as $message)
                                                    <div>{{ $message }}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-12">
                                    <label for="img_profile" class="form-label">@lang('translation.profile_image')&nbsp;&nbsp;<span
                                            style="font-size:12px;" class="fst-italic">@lang('translation.optional')</span></label>
                                    <div class="row">
                                        <div class="col-12 col-lg-2 d-flex flex-column">
                                            <div id="content_img_profile"
                                                class="img_profile mb-2 bg-body-secondary position-relative">
                                                <img id="previewImage" src="{{ asset('img/profile/img_preview.webp') }}"
                                                    alt="Image Preview">
                                                <a id="remove-img-a" src="#"
                                                    class="remove-img btn btn-danger position-absolute d-none"
                                                    data-bs-toggle="tooltip" title="Eliminar"><i style="width:15px;"
                                                        data-feather="trash-2"></i></a>
                                            </div>
                                            <div>
                                                <button id="img_profile" type="button" class="btn btn-info"><i
                                                        style="width:15px;" data-feather="upload"
                                                        class="icon-dual-** icon-sm"></i>&nbsp;&nbsp;Imagen</button>
                                            </div>
                                            @if ($errors->get('image_path'))
                                                <div class="d-block invalid-feedback">
                                                    <div class="d-flex flex-column mb-3">
                                                        @foreach ((array) $errors->get('image_path') as $message)
                                                            <div>{{ $message }}</div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-12 col-lg-10">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-lg-center mt-4">
                                    <button type="submit" class="btn btn-primary">{{ __('Register') }}</button>
                                </div>
                                <input class="d-none" type="file" name="image_path" id="image_input_file"
                                    accept="image/*">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <br />
        </div>
        <br />
    </div>
@endsection

@push('scripts')
    <script>
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

        activateOption('opc8');
        activateOption2('icon-settings');

        document.getElementById('name').focus();
        const bandRregistro = "{{ $bandRregistro }}";
        const registroMensaje = "{{ $registroMensaje }}";
        window.onload = function() {
            if (bandRregistro == "1") {
                Swal.fire({
                    title: "Exito!",
                    text: registroMensaje,
                    icon: "success"
                });
            } else {
                if (bandRregistro == "2") {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: registroMensaje
                    });
                }
            }
        };

        const btnImgProfile = document.querySelector("#img_profile");
        btnImgProfile.addEventListener('click', function() {
            document.getElementById("image_input_file").click();
        });

        const imageInputFile = document.querySelector("#image_input_file");
        imageInputFile.addEventListener('change', function() {

            const file = this.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                };
                reader.readAsDataURL(file);
                document.getElementById('content_img_profile').classList.remove("d-none");
                document.getElementById('remove-img-a').classList.remove("d-none");
            } else {
                previewImage.src = '#';
            }

        });

        const removeImgA = document.querySelector("#remove-img-a");
        removeImgA.addEventListener('click', function() {
            previewImage.src = "{{ asset('img/profile/img_preview.webp') }}";
            document.getElementById('remove-img-a').classList.add("d-none");
            document.getElementById("image_input_file").value = "";
        });
    </script>
@endpush
