@extends('layouts.master')

@section('title')
    @lang('translation.updateuser_password') - @lang('translation.business-center')
@endsection

@push('css')
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
        height: 54px;
        padding: 0.75rem 1.5rem;
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

    .input-icon-wrapper {
        position: relative;
        margin-bottom: 1.5rem;
        transition: var(--transition-normal);
    }
    
    .input-icon-wrapper:hover .input-icon {
        color: var(--color-primary);
    }
    
    .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--color-primary);
        z-index: 1;
        pointer-events: none;
        transition: var(--transition-normal);
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
        height: 54px;
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
    
    .invalid-feedback {
        color: #dc3545;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        animation: fadeIn 0.3s ease;
    }

    .alert-status {
        background-color: rgba(220, 53, 69, 0.1);
        border-left: 3px solid #dc3545;
        padding: 0.75rem 1rem;
        border-radius: var(--radius-sm);
        margin-bottom: 1.5rem;
        animation: fadeIn 0.4s ease;
    }
    
    @keyframes fadeIn {
        0% { opacity: 0; transform: translateY(-5px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    
    .fade-in {
        opacity: 0;
        animation: fadeIn 0.6s ease-in-out forwards;
    }
    
    .password-toggle-icon {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: var(--color-light-text);
        transition: var(--transition-normal);
        z-index: 2;
    }
    
    .password-toggle-icon:hover {
        color: var(--color-primary);
    }
    
    .password-wrapper {
        position: relative;
    }
    
    .page-header {
        background: linear-gradient(to right, var(--color-primary), var(--color-secondary));
        border-radius: var(--radius-lg);
        padding: 1.5rem 2rem;
        margin-bottom: 2rem;
        color: white;
        box-shadow: var(--shadow-card);
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
    }
    
    .page-header h4 {
        margin: 0;
        font-size: 1.8rem;
        font-weight: 600;
        display: flex;
        align-items: center;
    }
    
    .page-header h4 i {
        margin-right: 1rem;
        background: rgba(255, 255, 255, 0.2);
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
    
    @media (max-width: 768px) {
        .dashboard-card {
            padding: 1.5rem;
        }
        
        .page-header {
            padding: 1rem 1.5rem;
        }
        
        .page-header h4 {
            font-size: 1.5rem;
        }
    }
    
    @media (max-width: 576px) {
        .dashboard-card {
            padding: 1.25rem;
        }
        
        .btn-primary {
            width: 100%;
        }
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
        $pageTitle = __('translation.updateuser_password');
    @endphp
    <div class="container-fluid fade-in">
        <x-page-header :title="$pageTitle" icon="key" />
        <br>
        <div class="dashboard-card">
            <div class="card-header-custom">
                <div>
                    <h5 class="card-title"><i class="fas fa-lock"></i> @lang('translation.updateuser_password')</h5>
                    <p class="card-subtitle">Ingrese la nueva clave para su cuenta</p>
                </div>
            </div>
            
            @error('title')
                <div class="alert-status">{{ $message }}</div>
            @enderror
            
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <form method="POST" action="{{ route('updateacces') }}" class="row needs-validation" novalidate>
                        @csrf
                        
                        <div class="col-12 mb-4">
                            <label for="password" class="form-label">@lang('translation.password')</label>
                            <div class="input-icon-wrapper password-wrapper">
                                <input type="password" class="form-control" id="password" name="password"
                                    autocomplete="new-password" required>
                                <i class="fas fa-lock input-icon"></i>
                                <span class="password-toggle-icon" onclick="togglePassword('password')">
                                    <i class="fas fa-eye"></i>
                                </span>
                                @if ($errors->get('password'))
                                    <div class="d-block invalid-feedback">
                                        <div class="d-flex flex-column">
                                            @foreach ((array) $errors->get('password') as $message)
                                                <div>{{ $message }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-12 mb-4">
                            <label for="password_confirmation" class="form-label">@lang('translation.confirm_password')</label>
                            <div class="input-icon-wrapper password-wrapper">
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" autocomplete="new-password" required>
                                <i class="fas fa-lock input-icon"></i>
                                <span class="password-toggle-icon" onclick="togglePassword('password_confirmation')">
                                    <i class="fas fa-eye"></i>
                                </span>
                                @if ($errors->get('password_confirmation'))
                                    <div class="d-block invalid-feedback">
                                        <div class="d-flex flex-column">
                                            @foreach ((array) $errors->get('password_confirmation') as $message)
                                                <div>{{ $message }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-12 d-flex justify-content-center mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Guardar cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling.nextElementSibling.querySelector('i');
            
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        document.getElementById('password').focus();
        const bandRregistro = "{{ $bandRregistro }}";
        const registroMensaje = "{{ $registroMensaje }}";
        
        window.onload = function() {
            if (bandRregistro == "1") {
                Swal.fire({
                    title: "Éxito!",
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
            
            // Animación al hacer focus en los campos
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', () => {
                    input.parentElement.classList.add('active');
                });
                
                input.addEventListener('blur', () => {
                    if (!input.value) {  // Solo quitar la clase si el campo está vacío
                        input.parentElement.classList.remove('active');
                    }
                });
            });
        };
    </script>
@endpush
