@component('mail::message')
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ $logourl }}" alt="Logo" width="80" height="80" style="display: block; margin: 0 auto;">
</div>

<h1 style="color: black; font-size: 1.8em; font-family: 'Montserrat', sans-serif; text-align: center;">¡Bienvenido a la familia Aquafeel Solutions! </h1>
<p style="font-family: 'Montserrat', sans-serif; color: black;">Estimado/a <strong>{{ $clientName }}</strong></p>

<p style="font-family: 'Montserrat', sans-serif; color: black;">Gracias por elegir Aquafeel Solutions. Esperamos servirle y agradecemos su confianza en nosotros. Su solicitud con número de aplicación <strong>{{ $applicationCode }}</strong> está en manos de nuestro analista de agua, <strong>{{ $analystName }}</strong>, quien se asegurará de brindarle la mejor atención.</p>

<p style="font-family: 'Montserrat', sans-serif; color: black;">Atentamente,</p>

<p style="font-family: 'Montserrat', sans-serif; color: black;">El equipo de Aquafeel Solutions</p>

@endcomponent