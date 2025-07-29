@extends('layouts.master-auth')

@section('title')
    @lang('translation.recover_password') - Centro de Negocio 
@endsection

@push('css')
    <style>
        html,
        body {
            height: 100%;
            background-color: #F1F8FF;
        }

        .form-signin {
            max-width: 380px;
            padding: 1rem;
        }

        .form-signin .form-floating:focus-within {
            z-index: 2;
        }

        .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
        h4{
            margin-top: 10px;
            text-align: center;
            font-size: 28px;
            font-family: "MontserratBold";
            text-transform: uppercase;
        }
    </style>
@endpush

@section('content')
    <main class="form-signin container pt-5">
        <div class="mb-5">
            <div class="d-flex justify-content-center">
                <img class="mb-1" src="{{ asset('favicon.svg') }}" alt="" width="92">
            </div>
            <h4>CENTRO DE NEGOCIOS</h4>
        </div>

        <div class="mb-4 text-sm text-gray-600">
            @lang('translation.forgot_password')
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form class="needs-validation" method="POST" action="{{ route('password.email') }}" novalidate>
            @csrf

            <!-- Email Address -->
            <div>
                <div class="form-floating mb-3">
                <x-text-input id="floatingInput" class="form-control" type="email" name="email" :value="old('email')"
                     autofocus required/>
                <x-input-label for="floatingInput" :value="__('Email')" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
            </div>

            <div class="d-flex justify-content-start">
                <x-primary-button>
                    @lang('translation.btn_reset_password')
                </x-primary-button>
                <div class="mt-3 mx-md-3">
                    <a class="" href="{{route('login')}}">Ir al Login</a>
                </div>
            </div>
        </form>
    </main>
@endsection
