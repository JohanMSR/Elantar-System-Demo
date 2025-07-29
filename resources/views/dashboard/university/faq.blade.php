@extends('layouts.master')

@section('title')
    @lang('translation.faq_title') - @lang('translation.business-school')
@endsection

@push('css')
<link href="{{ asset('vendor/data-tables-bootstrap5/datatables.min.css') }}" rel="stylesheet" />
<link href="{{ asset('vendor/data-picker-bootstrap5/gijgo.css') }}" rel="stylesheet" type="text/css" />
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
    --transition-normal: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --transition-slow: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    --transition-fast: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    --radius-sm: 4px;
    --radius-md: 8px;
    --radius-lg: 15px;
}

.container-fluid {
    padding: 0rem;
    max-width: 1500px;
    margin: 0 auto;
    position: relative;
}

.container-1 {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 2rem;
    background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
    box-shadow: var(--shadow-card);
    padding: 2rem;
    position: relative;
    overflow: hidden;
    border-radius: var(--radius-lg);
    transition: var(--transition-normal);
    z-index: 1;
    width: 100% !important;
    margin: 0 auto 0.2rem auto;
}

.container-1:hover {
    box-shadow: 0 15px 35px rgba(19, 192, 230, 0.35);
    transform: translateY(-2px);
}

.container-1::after {
    content: '';
    position: absolute;
    bottom: 0;
    right: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, var(--color-primary), transparent);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.5s ease;
}

.container-1:hover::after {
    transform: scaleX(1);
}

.flex-menu {
    display: flex;
    align-items: center;
    width: 100%;
}

#principal-head {
    font-size: 1.8rem;
    font-weight: 600;
    margin: 0;
    color: white;
    text-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
    letter-spacing: 0.5px;
    position: relative;
    display: flex;
    align-items: center;
}

#principal-head::before {
    content: '';
    display: inline-block;
    width: 2rem;
    height: 2rem;
    margin-right: 15px;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z'%3E%3C/path%3E%3Cpath d='M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z'%3E%3C/path%3E%3C/svg%3E");
    background-size: contain;
    background-repeat: no-repeat;
}

#principal-head::after {
    content: '';
    position: absolute;
    bottom: -7px;
    left: 0;
    width: 50px;
    height: 3px;
    background: white;
    border-radius: 3px;
}

.breadcrumb-container {
    margin-left: auto;
    width: 40%;
    min-width: 300px;
    background-color: white;
    border-radius: 30px;
    padding: 0.4rem 1.2rem;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    display: flex;
    justify-content: center;
    align-items: center;
}

.breadcrumb-list {
    display: flex;
    list-style-type: none;
    padding: 0;
    margin: 0;
    align-items: center;
}

.breadcrumb-list li {
    display: flex;
    align-items: center;
    color: var(--color-light-text);
}

.breadcrumb-list li:not(:last-child)::after {
    content: '';
    display: inline-block;
    width: 0.8rem;
    height: 0.8rem;
    margin: 0 0.4rem;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%236c757d' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='9 18 15 12 9 6'%3E%3C/polyline%3E%3C/svg%3E");
    background-size: contain;
    background-repeat: no-repeat;
}

.breadcrumb-list a {
    color: var(--color-light-text);
    text-decoration: none;
    transition: var(--transition-normal);
    font-size: 0.9rem;
}

.breadcrumb-list a:hover {
    color: var(--color-primary);
}

.breadcrumb-list li:last-child a {
    color: var(--color-dark);
    font-weight: 600;
}

main {
    padding: 1rem 0;
}

.faq-section {
    margin-bottom: 2.5rem;
    background: var(--color-input-bg);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-input);
    padding: 1.5rem;
    transition: var(--transition-normal);
}

.faq-section:hover {
    box-shadow: var(--shadow-card);
    transform: translateY(-3px);
}

.faq-section h3 {
    color: var(--color-dark);
    margin: 0 0 1.5rem;
    font-family: "MontserratBold", sans-serif;
    position: relative;
    padding-bottom: 0.8rem;
    font-size: 1.5rem;
}

.faq-section h3::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 50px;
    height: 3px;
    background: linear-gradient(90deg, var(--color-primary), var(--color-secondary));
    border-radius: 3px;
}

.section {
    text-align: left;
    cursor: pointer;
    border: 1px solid var(--color-border);
    padding: 1.5rem;
    border-radius: var(--radius-md);
    margin: 1rem 0;
    font-weight: 500;
    transition: var(--transition-normal);
    background: var(--color-input-bg);
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
}

.section::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 3px;
    background: linear-gradient(to bottom, var(--color-primary), var(--color-secondary));
    transform: scaleY(0);
    transition: transform 0.3s ease;
}

.section:hover::before,
.section.active::before {
    transform: scaleY(1);
}

.section:hover {
    border-color: var(--color-primary);
    background: var(--color-input-bg-hover);
    transform: translateX(5px);
    padding-left: 2rem;
    box-shadow: var(--shadow-input);
}

.section::after {
    content: '';
    width: 10px;
    height: 10px;
    border-right: 2px solid var(--color-text);
    border-bottom: 2px solid var(--color-text);
    transform: rotate(45deg);
    transition: var(--transition-normal);
    margin-left: 10px;
}

.section.active {
    background: linear-gradient(45deg, rgba(19, 192, 230, 0.05), rgba(70, 135, 230, 0.05));
    border-color: var(--color-primary);
    box-shadow: var(--shadow-input);
}

.section.active::after {
    transform: rotate(-135deg) translateY(-3px);
}

.info {
    display: none;
    margin: 0 1rem 1.5rem;
    padding: 1.5rem;
    border-radius: var(--radius-md);
    background: var(--color-input-bg-hover);
    color: var(--color-text);
    line-height: 1.6;
    position: relative;
    overflow: hidden;
    border-left: 3px solid var(--color-primary);
    box-shadow: var(--shadow-input);
    transition: var(--transition-normal);
}

.info.show {
    display: block;
    animation: fadeIn 0.3s ease-in-out forwards;
}

@media (max-width: 768px) {
    .container-fluid {
        padding: 1rem;
    }
    
    .container-1 {
        padding: 1rem;
    }
    
    #principal-head {
        font-size: 1.5rem;
    }
    
    .section {
        padding: 1.2rem;
    }
    
    .info {
        padding: 1.2rem;
    }
    
    .faq-section h3 {
        font-size: 1.3rem;
    }
    
    #principal-head::after {
        width: 40px;
        bottom: -5px;
    }
}

@media (max-width: 480px) {
    .container-1 {
        flex-direction: column;
    }
    
    .flex-menu {
        flex-direction: column;
        gap: 1rem;
    }
    
    #principal-head {
        font-size: 1.3rem;
        text-align: center;
    }
    
    .section {
        padding: 1rem;
    }
    
    .info {
        padding: 1rem;
    }
    
    .faq-section h3 {
        font-size: 1.1rem;
    }
    
    #principal-head::after {
        width: 30px;
    }
}

@media (max-width: 992px) {
    .breadcrumb-container {
        width: 80%;
        margin: 0.8rem auto 0;
    }
    
    #principal-head {
        flex-direction: column;
        align-items: flex-start;
    }
}

@media (max-width: 576px) {
    .breadcrumb-container {
        width: 100%;
        min-width: auto;
    }
    
    .breadcrumb-list a {
        font-size: 0.8rem;
    }
}

@keyframes fadeIn {
    0% { opacity: 0; transform: translateY(10px); }
    100% { opacity: 1; transform: translateY(0); }
}

.fade-in {
    opacity: 0;
    animation: fadeIn 0.6s ease-in-out forwards;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    @php
        $faqTitle = __('translation.faq_title');
    @endphp
    <x-page-header :title="$faqTitle" icon="help-circle">
        <div class="breadcrumb-container">
            <ol class="breadcrumb-list">
                <li><a href="{{ route('university') }}">@lang('translation.university_title')</a></li>
                <li><a href="#">@lang('translation.faq_title')</a></li>
            </ol>
        </div>
    </x-page-header>
    
    <div class="row justify-content-center">
        <div class="col-12">
            <main class="fade-in">
                <div class="faq-section">
                    <h3>Sobre Centro de Negocios Global</h3>

                    <div class="section">¿Qué es el Centro de Negocios Global?</div>
                    <div class="info">
                        El Centro de Negocios Global es una plataforma diseñada para ayudar a los usuarios a
                        gestionar y potenciar su negocio a través de herramientas de ventas, capacitaciones,
                        recursos y soporte.
                    </div>

                    <div class="section">¿Cómo accedo al Centro de Negocios Global?</div>
                    <div class="info">En tu navegador, entra a la URL: https://aquafeelglobal.net, usa tu correo electrónico y
                        contraseña.</div>

                    <div class="section">¿Cómo se recupera la contraseña si la he olvidado?</div>
                    <div class="info">Haz clic en "¿Haz olvidado la contraseña?", ingresa tu correo electrónico y sigue las
                        instrucciones enviadas por correo electrónico para restablecerla.</div>
                </div>

                <div class="faq-section">
                    <h3>Dashboard</h3>

                    <div class="section">¿Qué muestra el dashboard al iniciar sección?</div>
                    <div class="info">El dashboard muestra el progreso del negocio y las últimas novedades, encontrarás un
                        gráfico de barras que muestra las ventas mensuales y un gráfico de pastel que muestra
                        las ventas del mes actual.</div>

                    <div class="section">¿Qué información proporciona el gráfico de ventas
                        mensuales?</div>
                    <div class="info">El gráfico de barras muestra las ventas mensuales de los últimos 6 meses, incluyendo
                        ventas propias y las ventas de los miembros del equipo.</div>

                    <div class="section">¿Qué información proporciona el gráfico de pastel en el
                        dashboard?</div>
                    <div class="info">El gráfico de pastel muestra las ventas del mes actual del top 5 de la oficina o las
                        ventas de los miembros del equipo.</div>
                    
                    <div class="section">¿Cómo puedo mantenerme informado sobre las
                        novedades de Aquafeel global?</div>
                    <div class="info">El panel lateral te mantendrá informado sobre las últimas novedades, eventos,
                        capacitaciones y actividades relevantes de Aquafeel global.</div>
                </div>

                <div class="faq-section">
                    <h3>Escuela de Negocios</h3>

                    <div class="section">¿Qué es la Escuela de Negocios?</div>
                    <div class="info">La Escuela de Negocios proporciona capacitaciones y recomendaciones de la mano de
                        expertos de la industria a través de documentos, videos, blogs y preguntas frecuentes.</div>

                    <div class="section">¿Qué recursos están disponibles en la Escuela de
                        Negocios?</div>
                    <div class="info">En la sección de Escuela de Negocios están disponibles recursos de capacitaciones y
                        recomendaciones en diversos formatos: documentos descargables, videos de
                        capacitaciones, blogs con artículos y preguntas frecuentes.</div>
                    
                    <div class="section">¿Qué tipo de recursos se encuentran en la sección de
                        Documentos?</div>
                    <div class="info">La sección de Documentos proporciona acceso a herramientas e información
                        descargables relacionadas con el negocio para ayudar en el logro de tus objetivos de
                        desarrollo.</div>

                    <div class="section">¿Qué tipo de contenido se encuentra en la sección de
                        Videos?</div>
                    <div class="info">La sección de Videos contiene recursos audiovisuales de capacitaciones e
                        informaciones de expertos y líderes de la industria, con técnicas, consejos y
                        recomendaciones.</div>

                    <div class="section">¿Cómo puedo resolver dudas comunes y Preguntas
                        Frecuentes?</div>
                    <div class="info">Ayuda a resolver dudas comunes de manera rápida y sencilla, recopilando las
                        preguntas más comunes que surgen al utilizar la plataforma del Centro de Negocios.</div>
                    
                    <div class="section">¿Qué incluye la sección de Contactos?</div>
                    <div class="info">La sección de contacto es un directorio para establecer conexiones y obtener apoyo
                        directo de líderes, managers de oficinas, equipo administrativo, contacto financiero y
                        soporte de la plataforma.</div>

                    <div class="section">¿Qué hago si necesito ayuda o soporte técnico?</div>
                    <div class="info">La sección de contactos puedes obtener soporte técnico contactando a soporte a
                        través del correo soporte@aquafeelglobal.net, llamando o utilizando WhatsApp al
                        725-239-8467.</div>

                    <div class="section">¿Qué puedo encontrar en la sección de Blog de la
                        Escuela de Negocios?</div>
                    <div class="info">En la sección de Blog puedes encontrar artículos escritos por expertos sobre técnicas y
                        mejores prácticas, historias de éxito, estudios de caso, y tendencias de la industria.</div>
                </div>
                
                <div class="faq-section">
                    <h3>Leads</h3>

                    <div class="section">¿Cómo puedo gestionar mis leads?</div>
                    <div class="info">Mis Leads, permite crear y acceder a todos los prospectos y potenciales clientes que
                        han mostrado interés en los productos de Aquafeel. Puedes gestionar tus leads
                        creando nuevos prospectos, organizándolos por fechas, ciudad o estado, y enviando
                        aplicaciones para el programa de apoyo desde la sección de Mis Leads.</div>

                    <div class="section">¿Cómo puedo crear un nuevo lead?</div>
                    <div class="info">Utiliza el botón "Nuevo Lead" en la sección de Mis Leads y completa la información
                        requerida del prospecto.</div>

                    <div class="section">¿Qué información se solicita para un nuevo lead?</div>
                    <div class="info">Te va a solicitar las siguientes informaciones: Nombres, apellidos, correo electrónico,
                        ciudad, estado, código postal, dirección y número de teléfono, también puedes asignar
                        un analista o mentor a un lead o prospecto. Para guardar los cambios dale clic en el
                        botón "Subir Lead".</div>

                    <div class="section">¿Cómo puedo editar un lead?</div>
                    <div class="info">En la lista de Mis Leads, utiliza el icono de editar para modificar los datos del lead o
                        prospecto. Para guardar los cambios dale clic en el botón "Salvar".</div>
                    
                    <div class="section">¿Cómo puedo asignar el lead a otra persona?</div>
                    <div class="info">La opción "Dueño del Proyecto" permite asignar el lead a uno mismo o a otra persona
                        del equipo. Para guardar los cambios dale clic en el botón "Salvar".</div>

                    <div class="section">¿Cómo organizo mis leads?</div>
                    <div class="info">Se pueden organizar por fechas, ciudad o estado, utilizando los campos y opciones
                        disponibles. Utilizando las opciones disponibles puedes organizar y ver tus leads de
                        una forma diferente.</div>

                    <div class="section">¿Cómo envío la precalificación o aplicación del lead
                        para el programa de apoyo?</div>
                    <div class="info">Utiliza el ícono de enviar en la lista de Mis Leads y utiliza el botón "Pre-Calificación"
                        para enviar la precalificación del prospecto. En la pantalla completar los datos en el
                        formulario de precalificación y dar clic en el botón "Submit Form".</div>
                    
                    <div class="section">¿Cómo puedo modificar los datos del cliente después
                        de enviar la precalificación o aplicación?</div>
                    <div class="info">Utiliza el botón "Editar Pre-Calificación" para modificar los datos en el formulario y
                        enviarlo nuevamente para aprobación. Para guardar los cambios dale clic en el botón
                        "Submit Form".</div>

                    <div class="section">¿Tengo que enviar una nueva aplicación si los datos
                        del cliente están incorrectos?</div>
                    <div class="info">No debes enviar una nueva aplicación, sólo utiliza el botón "Editar Pre-Calificación"
                        para modificar los datos en el formulario y enviarlo nuevamente para aprobación. Para
                        guardar los cambios dale clic en el botón "Submit Form".</div>
                </div>
                
                <div class="faq-section">
                    <h3>Proyectos</h3>

                    <div class="section">¿Qué información se puede visualizar en la sección
                        de Mis Proyectos?</div>
                    <div class="info">Puedes visualizar todas las aplicaciones de clientes y el estado actual en cada una de
                        las etapas de ventas.</div>

                    <div class="section">¿Cómo organizar, buscar y visualizar mis proyectos?</div>
                    <div class="info">Puedes visualizar todos tus proyectos y el estado actual de cada uno en la sección de
                        Mis Proyectos. También puedes realizar búsquedas por fecha y ordenar los datos por
                        ciudad, fecha de creación o estado.</div>

                    <div class="section">¿Cómo puedo buscar información específica en mis
                        proyectos?</div>
                    <div class="info">Utiliza las opciones de búsqueda por fecha y ordena los datos por ciudad, fecha de
                        creación o estado en la sección de Mis Proyectos.</div>
                    
                    <div class="section">¿Cómo puedes organizar y visualizar los proyectos?</div>
                    <div class="info">Utilizando los campos "Desde" y "Hasta" y luego dando clic en el botón "Buscar".
                        Se pueden ordenar por ciudad, fecha de creación o estado, seleccionando la opción
                        deseada y dando clic en el botón "Buscar".</div>
                </div>

                <div class="faq-section">
                    <h3>Ajustes</h3>

                    <div class="section">¿Cómo puedo cambiar mi contraseña?</div>
                    <div class="info">Puedes cambiar tu contraseña accediendo a la sección de Cambio de Clave. Debes
                        ingresar la nueva en los campos de "Clave" y "Confirmar Clave", la nueva contraseña
                        debe tener al menos 8 caracteres incluyendo letras, números y caracteres especiales,
                        confirmar y dar clic en el botón "Guardar".</div>

                    <div class="section">¿Cómo se sube una imagen de perfil?</div>
                    <div class="info">Se debe utilizar el botón "Imagen" para subir una imagen desde el dispositivo y luego
                        dar clic en "Guardar".</div>
                    
                    <div class="section">¿Cómo puedo actualizar mi perfil?</div>
                    <div class="info">Puedes actualizar tu perfil en la sección Mi Perfil, donde puedes modificar tu dirección,
                        número de teléfono, foto de perfil y otros datos personales.</div>
                </div>
            </main>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
    $(document).ready(function() {
        // Añadir clase para animación de entrada
        $('main').addClass('fade-in');
        
        // Funcionalidad de acordeón para las secciones FAQ
        $(document).on('click', '.section', function(e) {
            var $this = $(this);
            var $info = $this.next('.info');
            var isOpen = $this.hasClass('active');
            
            // Alternar el estado actual sin cerrar otras secciones
            if (isOpen) {
                // Cerrar esta sección
                $this.removeClass('active');
                $info.removeClass('show').slideUp(300);
            } else {
                // Abrir esta sección
                $this.addClass('active');
                $info.slideDown(300, function() {
                    $(this).addClass('show');
                });
            }
            
            e.preventDefault();
            return false;
        });
        
        // Desactivamos las funciones problemáticas o las reemplazamos con versiones vacías
        // para evitar errores en la consola
        window.activateOption = window.activateOption || function() { 
            console.log("Función activateOption reemplazada para evitar errores");
        };
        
        window.activateOption2 = window.activateOption2 || function() {
            console.log("Función activateOption2 reemplazada para evitar errores");
        };
    });
    </script>
@endpush
