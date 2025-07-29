<style>
body {
    margin: 0;

}

.favicon {
    filter: drop-shadow(0px 0px 1px rgba(0, 0, 0, 1)) drop-shadow(3px 3px 10px rgba(0, 0, 0, 0.4));
}
.text-banner-img{
    filter: drop-shadow(0px 0px 1px rgba(0, 0, 0, 1)) drop-shadow(3px 3px 10px rgba(0, 0, 0, 0.2)) opacity(88%);
}


.meneu-cuenta,
.container-imagen-profile,
.texto-perfil-inferior,
.texto-perfil {
    filter: drop-shadow(3px 3px 5px rgba(0, 0, 0, 0.2));
}

.header {
    position: relative;
    text-align: center;
    background-color: white;
    color: #fff;
}

.mini-favicon{
    position:absolute;
    width: 50px;
    margin-bottom: 0px;
}
.content {
    position: relative;
    height: 20vh;
    text-align: center;
    background-color: #fff;
}

.flex {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    text-align: center;
}

.waves {
    position: relative;
    width: 100%;
    height: 7vh;
    margin-bottom: -7px;
    filter: drop-shadow(-35px 0px 19px rgb(192, 227, 255, 0.75));

}

.parallax>use {
    -webkit-animation: moveForever 30s cubic-bezier(.55, .5, .45, .5) infinite;
    animation: moveForever 30s cubic-bezier(.55, .5, .45, .5) infinite;
}

.parallax>use:nth-child(1) {
    -webkit-animation-delay: -2s;
    animation-delay: -2s;
    -webkit-animation-duration: 7s;
    animation-duration: 7s;
}

.parallax>use:nth-child(2) {
    -webkit-animation-delay: -3s;
    animation-delay: -3s;
    -webkit-animation-duration: 10s;
    animation-duration: 10s;
}

.parallax>use:nth-child(3) {
    -webkit-animation-delay: -4s;
    animation-delay: -4s;
    -webkit-animation-duration: 13s;
    animation-duration: 13s;
}

.parallax>use:nth-child(4) {
    -webkit-animation-delay: -5s;
    animation-delay: -5s;
    -webkit-animation-duration: 20s;
    animation-duration: 20s;
}

@-webkit-keyframes moveForever {
    0% {
        -webkit-transform: translate3d(-90px, 0, 0);
        transform: translate3d(-90px, 0, 0);
    }

    100% {
        -webkit-transform: translate3d(85px, 0, 0);
        transform: translate3d(85px, 0, 0);
    }
}

@keyframes moveForever {
    0% {
        -webkit-transform: translate3d(-90px, 0, 0);
        transform: translate3d(-90px, 0, 0);
    }

    100% {
        -webkit-transform: translate3d(85px, 0, 0);
        transform: translate3d(85px, 0, 0);
    }
}

/*
@media (max-width : 768px) {
    h1 {
        font-size: 24px;
    }
    .content {
        height: 30vh;
    }
    .waves {
        position: absolute;
        height: 40px;
        min-height: 40px;
    }
}*/
/*.text-banner {
  position: absolute;
  left: 0;
  right: 0;
  margin: 0 auto;
}*/
.img-perfil {
    width: 82px;
    height: 82px;
    gap: 0px;
    border: 1px solid #8fd4f2;
    position: absolute;
    left: -150px;
    cursor: pointer;
    box-shadow: rgba(0, 0, 0, 0.05) 0px 0px 0px 1px;
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}
.img-perfil:hover{
    transform: scale(1.05);
}
.meneu-cuenta {
    position: absolute;
    right: 20px;
    top: 85px;
}
.texto-perfil {
    font-size: 15px;
    font-family: "MontserratBold";
    position: absolute;
    bottom: 130px;
    right: 0;
    transform: translateX(-35%);
    width: 150px;
    height: 30px;
    text-align: center;
    color: rgb(192, 227, 255, 1);
}
.favicon{
    position:relative;
    width: 130px;
    margin-bottom: 10px;
    margin-top: 10px;
}
.mini-favicon{
    position:absolute;
    width: 50px;
    margin-bottom: 0px;
}
.text-banner-img{
    width: 480px;
}

@media (max-width: 600px) {
    .text-banner {
        font-size: 28px;
    }
    .text-banner-img{
        width: 310px;
    }
}

.texto-perfil-inferior {
    font-size: 12px;
    font-family: "MontserratLight";
    position: absolute;
    bottom: 128px;
    right: 0;
    transform: translateX(-35%);
    width: 150px;
    height: 30px;
    text-align: center;
    color: #ffffff;
}
.favicon{
    position:relative;
    width: 150px;
    margin-bottom: 10px;
    margin-top: 10px;
}

@media (max-width: 1366px) {
    .img-perfil {
        left: -100px;
        width: 70px;
        height: 70px;
    }
    .texto-perfil,
    .texto-perfil-inferior {
        transform: translateX(-18%);
        width: 120px;
    }
    .texto-perfil {
        font-size: 13px;
    }
    .texto-perfil-inferior {
        font-size: 11px;
    }
}

@media (max-width: 768px) {
    .img-perfil {
        left: -80px;
        width: 60px;
        height: 60px;
    }
    .texto-perfil,
    .texto-perfil-inferior {
        transform: translateX(-10%);
        width: 100px;
    }
    .meneu-cuenta {
        top: 75px;
    }
}
</style>
{{-- Banner de la app --}}
<div class="banner-img">
    <div class="position-relative">
        <div class="d-flex justify-content-start pt-1 ps-1">
            <img class="mini-favicon" src="{{ asset('mini-favicon.png') }}" alt="">
        </div>
        <div class="d-flex justify-content-center" style="filter: opacity(88%);">
            <img class="favicon" src="{{ asset('favicon7.png') }}" alt="">
        </div>
        <h4 class="text-center align-items-center justify-content-center d-flex text-banner"><img
                class="text-banner-img" src="{{ asset('textfavicon6.png') }}"></h4>
        <div>
            <svg class="waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
                <defs>
                    <path id="gentle-wave"
                        d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" />
                </defs>
                <g class="parallax">
                    <use xlink:href="#gentle-wave" x="48" y="1" fill="rgb(192, 227, 255, 0.7)" />
                    <use xlink:href="#gentle-wave" x="48" y="1" fill="rgb(192, 227, 255, 0.5)" />
                    <use xlink:href="#gentle-wave" x="48" y="1" fill="rgb(192, 227, 255, 0.3)" />
                    <use xlink:href="#gentle-wave" x="48" y="1" fill="rgb(192, 227, 255, 0.7)" />
                    <use xlink:href="#gentle-wave" x="48" y="1" fill="rgb(192, 227, 255, 0.5)" />
                    <use xlink:href="#gentle-wave" x="48" y="1" fill="rgb(192, 227, 255, 0.3)" />

                    <div class="container-imagen-profile">
                        @php
                        $inicial = '';
                        $name = '';
                        if(Auth::check()) {
                            if(Auth::user()->surname) {
                                $inicial = ucwords(Auth::user()->surname[0]) . '.';
                            }
                            $name = ucwords(Auth::user()->name);
                        }
                        if (session()->has('rol_userlogin')) {
                            $rol = session('rol_userlogin');
                        }else{
                            $rol = "..";
                        }
                        @endphp
                        <p class="texto-perfil text-uppercase text-nowrap">{{ $name }}
                            {{ $inicial }}</p>
                        <p class="texto-perfil-inferior text-uppercase text-nowrap"><br> {{$rol}}</p>
                        {{-- menu cuenta --}}
                    </div>
                    <div class="meneu-cuenta">
                        <div class="dropdown">
                            @if (Auth::user() && Auth::user()->image_path)
                            <img class="rounded-circle img-perfil dropdown-toggle " data-bs-toggle="dropdown"
                                aria-expanded="false" src="{{ url('storage/'. Auth::user()->image_path) }}"
                                alt="img profile">
                            @else
                            <img class="rounded-circle img-perfil dropdown-toggle" data-bs-toggle="dropdown"
                                aria-expanded="false" src="{{ asset('img/profile/no.png') }}" alt="img profile">
                            @endif
                            <ul class="dropdown-menu">
                                {{-- <li><a class="dropdown-item" href="{{route('editacces')}}">@lang('translation.profile')</a>
                                </li> --}}
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                            @lang('translation.logout')</a></li>
                                </form>
                            </ul>

                        </div>

                    </div>

                    {{-- --- --}}
        </div>

    </div>
</div>