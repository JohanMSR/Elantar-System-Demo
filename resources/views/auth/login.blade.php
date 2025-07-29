<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@lang('Login - Centro de Negocio')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'ulm': ['Mont', 'Helvetica', 'Arial', 'sans-serif'],
                    },
                    animation: {
                        'float': 'float 8s ease-in-out infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' },
                        }
                    },
                    backgroundImage: {
                        'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
                    }
                }
            }
        }
    </script>
    <style>
        /* Mont Font Family */
        @font-face {
            font-family: 'Mont';
            src: url('{{ asset("font/Mont Font Family/Mont-Regular.ttf") }}') format('truetype');
            font-weight: 400;
            font-style: normal;
        }
        
        @font-face {
            font-family: 'Mont';
            src: url('{{ asset("font/Mont Font Family/Mont-Bold.ttf") }}') format('truetype');
            font-weight: 700;
            font-style: normal;
        }
        
        @font-face {
            font-family: 'Mont';
            src: url('{{ asset("font/Mont Font Family/Mont-Light.ttf") }}') format('truetype');
            font-weight: 300;
            font-style: normal;
        }
        
        @font-face {
            font-family: 'Mont';
            src: url('{{ asset("font/Mont Font Family/Mont-SemiBold.ttf") }}') format('truetype');
            font-weight: 600;
            font-style: normal;
        }
        
        .card {
            background: linear-gradient(135deg, #002270 0%, #002270 90%);
            background-image: radial-gradient(at 100% 100%,#000a29 0,transparent 50%), radial-gradient(at 0 0,#000a29 0,transparent 50%);
        }

        .logo {
            filter: drop-shadow(0 0 12px rgba(0, 0, 0, 0.3));
        }

        /* Error styles */
        .error-message {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 0.75rem;
            margin-left: 0.5rem;
            animation: slideInError 0.4s ease;
            font-weight: 500;
        }

        @keyframes slideInError {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .input-error {
            border-color: #dc2626 !important;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
        }

        /* Custom focus effect that excludes left border */
        .input-focus {
            border-top-color: #3b82f6 !important;
            border-right-color: #3b82f6 !important;
            border-bottom-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
        }

        /* Success message styles */
        .success-message {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(34, 197, 94, 0.05));
            border-left: 4px solid #22c55e;
            padding: 1.25rem 1.5rem;
            border-radius: 1rem;
            margin-bottom: 2rem;
            animation: slideInAlert 0.5s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        @keyframes slideInAlert {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        /* Loading state */
        .loading {
            opacity: 0.7;
            pointer-events: none;
        }

        .spinner {
            border: 2px solid #f3f4f6;
            border-top: 2px solid #3b82f6;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .logo-menu {
            filter: drop-shadow(0 0 12px rgba(0, 0, 0, 0.3));
        }
        
        body {
            font-family: 'Mont', 'Helvetica', 'Arial', sans-serif;
        }
        
        * {
            font-family: 'Mont', 'Helvetica', 'Arial', sans-serif;
        }
    </style>
</head>
<body class="m-0 bg-gray-100 font-ulm flex justify-center items-center min-h-screen transition-all duration-500 ease-in-out">
    <div id="tsparticles" class="fixed inset-0 w-full h-full -z-10"></div>
    
    <div id="card" class="card text-center bg-blue-900 p-16 rounded-3xl shadow-2xl self-center mx-auto transition-all duration-500 ease-in-out z-10">
        <div class="flex flex-col items-center justify-center py-9 px-8">
            <img src="{{ asset('iconoElantar.png') }}" alt="AQUAFEEL Logo" class="logo animate-float pt-10 w-48 h-48">
            <div id="lottie-container" class="w-64 h-64"></div>
        </div>
    </div>  
    <div id="loginForm" class="self-center mx-auto h-[650px] absolute text-center bg-white/95 backdrop-blur-sm p-3 rounded-3xl shadow-2xl shadow-gray-900/20 border border-gray-100 flex items-center justify-center left-[63%] top-1/2 w-[950px] -translate-x-1/2 -translate-y-1/2 z-10 opacity-0 scale-0 pointer-events-none transition-all duration-500 ease-in-out hover:shadow-3xl hover:shadow-gray-900/30">
        <form method="POST" action="{{ route('login') }}" class="w-full p-6" id="loginFormElement">
            @csrf
            <div class="flex flex-col items-center justify-center">
                <img src="{{ asset('logo.svg') }}" class="logo-menu h-[200px]">
            </div>
            <h3 class="text-2xl font-bold text-[#002270] mb-8 tracking-tight drop-shadow-sm">Iniciar sesión</h3>
            
            @if (session('status'))
            <div class="success-message mb-4" role="alert" aria-live="polite">
                <div class="flex items-center">
                    <svg width="20" height="20" fill="currentColor" class="mr-2 text-green-600" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                    </svg>
                    {{ session('status') }}
                </div>
            </div>
            @endif

            @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4" role="alert">
                <div class="flex">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <div class="mb-6">
                <div class="flex">
                    <div class="w-10 z-10 pl-1 text-center pointer-events-none flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                        </svg>
                    </div>
                    <input 
                        type="email"
                        name="email" 
                        id="email"
                        value="{{ old('email') }}"
                        class="w-full -ml-10 pl-10 pr-3 py-4 rounded-full border-2 border-gray-200 outline-none focus:border-[#002270] hover:border-[#000a29] transition-colors duration-200 @error('email') input-error @enderror"
                        placeholder="@lang('translation.email')"
                        required
                        autofocus
                        aria-describedby="emailError"
                    >
                </div>
                
                @error('email')
                <div id="emailError" class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-6 relative">
                <div class="flex">
                    <div class="w-10 z-10 pl-1 text-center pointer-events-none flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <input 
                        type="password" 
                        name="password"
                        id="password"
                        class="w-full -ml-10 pl-10 pr-3 py-4 rounded-full border-2 border-gray-200 outline-none focus:border-[#002270] hover:border-[#000a29] transition-colors duration-200 @error('password') input-error @enderror"
                        placeholder="@lang('translation.password')"
                        required
                        aria-describedby="passwordError"
                    >
                    <button 
                        type="button" 
                        id="togglePassword" 
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-[#000a29] focus:outline-none transition-colors duration-200"
                        aria-label="Toggle password visibility"
                    >
                        <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </div>
                @error('password')
                <div id="passwordError" class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-6 pl-3 flex items-center">
                <div class="inline-flex items-center">
                    <label class="flex items-center cursor-pointer relative">
                        <input 
                            type="checkbox"
                            name="remember"
                            id="remember"
                            value="1"
                            {{ old('remember') ? 'checked' : '' }}
                            class="peer h-5 w-5 cursor-pointer transition-all appearance-none rounded shadow hover:shadow-md border border-slate-300 checked:bg-slate-800 checked:border-slate-800"
                        >
                        <span class="absolute text-white opacity-0 peer-checked:opacity-100 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" stroke="currentColor" stroke-width="1">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </span>
                    </label>
                    <label for="remember" class="ml-3 text-sm text-gray-700 font-medium cursor-pointer">
                        @lang('translation.remember_me')
                    </label>
                </div>
            </div>
            <div class="mb-6 flex items-center justify-center w-full">
            <button 
                type="submit" 
                id="loginButton"
                class="w-1/2 px-8 py-4 rounded-full bg-[#002270] hover:bg-[#000a29] text-white text-base font-semibold transition-all duration-300 flex items-center justify-center shadow-lg hover:shadow-xl transform hover:scale-105 active:scale-95"
            >
                <span id="loginText">Iniciar sesión</span>
                <div id="loginSpinner" class="spinner ml-2 hidden"></div>
            </button>
</div>
            @if (Route::has('password.request'))
            <div class="mt-6 text-center">
                <a href="{{ route('password.request') }}" class="text-[#002270] hover:text-[#000a29] text-sm transition-all duration-200 hover:underline font-medium">
                    @lang('translation.have_you_forgotten_your_password')
                </a>
            </div>
            @endif
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tsparticles@2.12.0/tsparticles.bundle.min.js"></script>
    <script>
        // Load tsParticles
        tsParticles.load("tsparticles", {
            fpsLimit: 60,
            particles: {
                number: {
                    value: 50,
                    density: {
                        enable: true,
                        value_area: 800
                    }
                },
                color: {
                    value: ["#002270", "#0044cc", "#0066ff"]
                },
                shape: {
                    type: "circle"
                },
                opacity: {
                    value: 0.3,
                    random: false,
                    anim: {
                        enable: false,
                        speed: 1,
                        opacity_min: 0.1,
                        sync: false
                    }
                },
                size: {
                    value: 3,
                    random: true,
                    anim: {
                        enable: false,
                        speed: 40,
                        size_min: 0.1,
                        sync: false
                    }
                },
                line_linked: {
                    enable: true,
                    distance: 150,
                    color: "#002270",
                    opacity: 0.2,
                    width: 1
                },
                move: {
                    enable: true,
                    speed: 0.8,
                    direction: "none",
                    random: false,
                    straight: false,
                    out_mode: "out",
                    bounce: false,
                    attract: {
                        enable: false,
                        rotateX: 600,
                        rotateY: 1200
                    }
                }
            },
            interactivity: {
                detect_on: "canvas",
                events: {
                    onhover: {
                        enable: true,
                        mode: "grab"
                    },
                    onclick: {
                        enable: true,
                        mode: "push"
                    },
                    resize: true
                },
                modes: {
                    grab: {
                        distance: 140,
                        line_linked: {
                            opacity: 0.5
                        }
                    },
                    push: {
                        particles_nb: 4
                    }
                }
            },
            retina_detect: true
        });

        // Load the Lottie animation
        const animation = lottie.loadAnimation({
            container: document.getElementById('lottie-container'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: '{{ asset("Rising Graph Bar Chart Line Loader (1).json") }}'
        });

        // Handle animation events
        animation.addEventListener('DOMLoaded', function() {
            console.log('Animation loaded successfully');
        });

        animation.addEventListener('error', function(error) {
            console.error('Animation error:', error);
        });

        // Password toggle functionality
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                `;
            }
        });

        // Form submission handling
        document.getElementById('loginFormElement').addEventListener('submit', function(e) {
            const button = document.getElementById('loginButton');
            const text = document.getElementById('loginText');
            const spinner = document.getElementById('loginSpinner');
            
            // Show loading state
            button.classList.add('loading');
            text.textContent = 'Iniciando sesión...';
            spinner.classList.remove('hidden');
        });

        // Real-time validation


        function validateField(input) {
            const errorElement = document.getElementById(input.id + 'Error');
            
            if (input.validity.valid) {
                input.classList.remove('input-error');
                if (errorElement) {
                    errorElement.style.display = 'none';
                }
            } else {
                input.classList.add('input-error');
                if (errorElement) {
                    errorElement.style.display = 'block';
                }
            }
        }

        // Paso 1: Mostrar card en el centro (ya está visible por defecto)
        
        // Paso 2: Mover a la izquierda en desktop, hacia arriba en mobile después de 2 segundos
        setTimeout(() => {
          const card = document.getElementById('card');
          // Detectar si es mobile (ancho menor a 768px)
          if (window.innerWidth < 768) {
            card.classList.add('-translate-y-[250%]', 'transition-transform', 'duration-1000', 'ease-in-out');
          } else {
            card.classList.add('-translate-x-[110%]', 'transition-transform', 'duration-1000', 'ease-in-out');
          }
          
          // Paso 3: Mostrar el formulario en el centro después de la animación
          setTimeout(() => {
            const loginForm = document.getElementById('loginForm');
            loginForm.classList.remove('opacity-0', 'scale-0', 'pointer-events-none');
            loginForm.classList.add('opacity-100', 'scale-100', 'pointer-events-auto');
          }, 500);
        }, 1000);

        // Handle custom messages with SweetAlert2 if available
        @if(session('custom_message'))
        window.addEventListener("DOMContentLoaded", (event) => {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atención',
                    html: '{!! session('custom_message') !!}<br><small class="text-muted">Contacte al administrador</small>',
                    showConfirmButton: true,
                    confirmButtonText: 'Entendido',
                    buttonsStyling: false,
                    showCloseButton: true,
                    customClass: {
                        confirmButton: 'bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200',
                        popup: 'rounded-xl'
                    },
                    backdrop: 'rgba(0,0,0,0.4)',
                    allowOutsideClick: true,
                    allowEscapeKey: true
                });
            }
        });
        @endif

        // Responsive handling
        window.addEventListener('resize', function() {
            const card = document.getElementById('card');
            const loginForm = document.getElementById('loginForm');
            
            // Reset animations on resize
            card.classList.remove('-translate-x-[130%]', '-translate-y-[250%]');
            loginForm.classList.remove('opacity-100', 'scale-100', 'pointer-events-auto');
            loginForm.classList.add('opacity-0', 'scale-100', 'pointer-events-none');
            
            // Re-apply animations after a short delay
            setTimeout(() => {
                if (window.innerWidth < 768) {
                    card.classList.add('-translate-y-[250%]', 'transition-transform', 'duration-1000', 'ease-in-out');
                } else {
                    card.classList.add('-translate-x-[130%]', 'transition-transform', 'duration-1000', 'ease-in-out');
                }
                
                setTimeout(() => {
                    loginForm.classList.remove('opacity-0', 'scale-0', 'pointer-events-none');
                    loginForm.classList.add('opacity-100', 'scale-100', 'pointer-events-auto');
                }, 100);
            });
        });
    </script>
</body>
</html>