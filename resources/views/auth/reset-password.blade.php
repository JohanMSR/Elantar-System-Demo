@extends('layouts.master-auth')

@section('title')
    @lang('translation.reset_password') - Centro de Negocio
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

<main class="form-signin w-100 m-auto pt-5 mb-5">
    <form method="POST" action="{{ route('password.store') }}" novalidate>
        @csrf

        <div class="mb-5">
            <div class="d-flex justify-content-center">
                <img class="mb-1" src="{{ asset('favicon.svg') }}" alt="" width="92">
            </div>
            <h4>CENTRO DE NEGOCIOS</h4>
        </div>

        <div class="mb-4 text-sm ">
            @lang('translation.reset_you_password')
        </div>

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="" type="email" name="email" :value="old('email', $request->email)" required
                autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="" type="password" name="password" required
                autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="mt-1" type="password" name="password_confirmation"
                required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                @lang('translation.reset_password')
            </x-primary-button>
        </div>
    </form>
</div>
@endsection
