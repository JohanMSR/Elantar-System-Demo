<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credit Application</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 6px;
            text-align: left;            
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
        }
        .section-title {
            background-color: #d9d9d9;
            font-weight: bold;
        }
        .div-title {
            padding: 1px;
            font-weight: bold;
            font-size: 10px;
        }
        .text-row {
            font-size: 8px;
        }
        .signature {
            margin-top: 10px;
            text-align: center; /* Centrar el texto */
        }
        .signature-line {
            border-top: 1px solid #000; /* Línea de firma */
            width: 100%; /* Ancho de la línea */
            margin-bottom: 20px; /* Espacio entre líneas */
        }
        .td-no-border {
            border: 0;
        }
        .page-break {
            page-break-after: always; /* Salto de página */
        }
        .header {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100px;
        }
        .header-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .header-section img {
            max-width: 350px; /* Imagen más grande */
            height: 80px;
            margin-bottom: 5px; /* Más espacio */
        }
        .header-section-text {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 10px;
        }
        .header-section-text h1 {
            margin: 0;
            font-size: 14px; /* Texto ligeramente más grande */
            font-weight: bold; /* Texto en negrita */
            color: #333; /* Color de texto */
        }
        .footer {
            position: fixed; /* Fijar el footer en la parte inferior */
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center; /* Centrar el texto horizontalmente */
            padding: 10px; /* Añadir algo de padding */
            font-size: 11px; /* Tamaño de fuente pequeño */
        }
        .centered-bold-div {
            text-align: center; /* Centrar texto horizontalmente */
            font-weight: bold; /* Texto en negrita */
            padding: 3px; /* Padding de 3px en todos los lados */
        }
        .firma-imagen {
            width: 100px; /* Ancho de la imagen */
            height: 30px; /* Alto de la imagen */
            object-fit: cover; /* Mantener la proporción y recortar si es necesario */
        }
    </style>
</head>
<body>
    @php
        use Carbon\Carbon;
        $precal->fe_fecha_nacimiento = Carbon::parse($precal->fe_fecha_nacimiento)->format('m/d/Y');
        $precal->c2_fe_fecha_nacimiento = Carbon::parse($precal->c2_fe_fecha_nacimiento)->format('m/d/Y');
        $precal->fe_vencimto_lic = Carbon::parse($precal->fe_vencimto_lic)->format('m/d/Y');
        $precal->c2_fe_vencimto_lic = Carbon::parse($precal->c2_fe_vencimto_lic)->format('m/d/Y');
        $precal->fecha = Carbon::parse($precal->fecha)->format('m/d/Y');
    @endphp
    {{-- Pagina de encabezado 1 --}}
    <div class="header">
        <div class="header-section">
            <img src="{{ $logoPath }}" alt="Logo Base64"> 
            <div class="header-section-text">
                <h1>CREDIT APPLICATION/ APLICACION DE CREDITO</h1>
        </div>
    </div>    
    <table>

        <tr class="section-title">
            <th colspan="4">Applicant / Aplicante</th>
            <th colspan="4">Co-Applicant / Co-Aplicante</th>
        </tr>
        <tr>
            <td colspan="3">
                <div class="row">
                    <div class="div-title">
                        Last Name / Apellido:
                    </div>
                    <div>
                        {{$precal->tx_primer_apellido}}
                    </div>        
                </div>
            </td>
            <td colspan="1">
                <div class="row">
                    <div class="div-title">
                        First Name / Nombre:
                    </div>
                    <div>
                        {{$precal->tx_primer_nombre}}
                    </div>        
                </div>
            </td>
            <td colspan="3">
                <div class="row">
                    <div class="div-title">
                        Last Name / Apellido:
                    </div>
                    <div>
                        {{$precal->c2_tx_primer_apellido}}
                    </div>        
                </div>
            </td>
            <td colspan="1">
                <div class="row">
                    <div class="div-title">
                        First Name / Nombre:
                    </div>
                    <div>
                        {{$precal->c2_tx_primer_nombre}}
                    </div>        
                </div>
            </td>
            
        </tr>
        <tr>
            <td colspan="3">
                <div class="row">
                    <div class="div-title">
                        SS# / Seguro Social:
                    </div>
                    <div>
                       {{$precal->nu_documento_id}}
                    </div>        
                </div>
            </td>
            <td colspan="1">
                <div class="row">
                    <div class="div-title">
                        Date of Birth / Fecha de Nacimiento:
                    </div>
                    <div>
                        {{$precal->fe_fecha_nacimiento}}
                    </div>        
                </div>
            </td>
            <td colspan="3">
                <div class="row">
                    <div class="div-title">
                        SS# / Seguro Social:
                    </div>
                    <div>
                        {{$precal->c2_nu_documento_id}}
                    </div>        
                </div>
            </td>
            <td colspan="1">
                <div class="row">
                    <div class="div-title">
                        Date of Birth / Fecha de Nacimiento:
                    </div>
                    <div>
                        {{$precal->c2_fe_fecha_nacimiento}}
                    </div>        
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <div class="row">
                    <div class="div-title">
                        DL OR ID#:
                    </div>
                    <div>
                        {{$precal->tx_licencia}}
                    </div>        
                </div>
            </td>
            <td colspan="1">
                <div class="row">
                    <div class="div-title">
                        Exp:
                    </div>
                    <div>
                        {{$precal->fe_vencimto_lic}}
                    </div>        
                </div>
            </td>
            <td colspan="3">
                <div class="row">
                    <div class="div-title">
                        DL OR ID#:
                    </div>
                    <div>
                        {{$precal->c2_tx_licencia}}
                    </div>        
                </div>
            </td>
            <td colspan="1">
                <div class="row">
                    <div class="div-title">
                        Exp:
                    </div>
                    <div>
                        {{$precal->c2_fe_vencimto_lic}}
                    </div>        
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <div class="row">
                    <div class="div-title">
                        CELL PH# / TELEFONO CELULAR
                    </div>
                    <div>
                        {{$precal->tx_telefono}}
                    </div>        
                </div>
            </td>
            <td colspan="1">
                <div class="row">
                    <div class="div-title">
                        HOME PH# / TELEFONO CASA
                    </div>
                    <div>
                       
                    </div>        
                </div>
            </td>
            <td colspan="3">
                <div class="row">
                    <div class="div-title">
                        CELL PH# / TELEFONO CELULAR
                    </div>
                    <div>
                        {{$precal->c2_tx_telefono}}
                    </div>        
                </div>
            </td>
            <td colspan="1">
                <div class="row">
                    <div class="div-title">
                        Home PH# / TELEFONO CASA
                    </div>
                    <div>
                        
                    </div>        
                </div>
            </td>            
        </tr>
        <tr>
            <td colspan="4">
                <div class="row">
                    <div class="div-title">
                        Address / Dirección:
                    </div>
                    <div>
                        {{$precal->direccion}}
                    </div>        
                </div>
            </td>
            <td colspan="4">
                <div class="row">
                    <div class="div-title">
                        Address / Dirección:
                    </div>
                    <div>
                        {{$precal->c2_direccion}}
                    </div>        
                </div>
            </td>
        </tr>
        {{-- Fila de direccon--}}
        <tr>
            <td colspan="2">
                <div class="row">
                    <div class="div-title">
                        CITY / CIUDAD
                    </div>
                    <div>
                        {{$precal->tx_ciudad}}
                    </div>        
                </div>
            </td>
            <td colspan="1">
                <div class="row">
                    <div class="div-title">
                        ST / ESTADO:
                    </div>
                    <div>
                        {{$precal->estado}}
                    </div>        
                </div>
            </td>
            <td colspan="1">
                <div class="row">
                    <div class="div-title">
                        ZIP / C POSTAL
                    </div>
                    <div>
                        {{$precal->tx_zip}}
                    </div>        
                </div>
            </td>
            <td colspan="2">
                <div class="row">
                    <div class="div-title">
                        CITY / CIUDAD
                    </div>
                    <div>
                        {{$precal->c2_tx_ciudad}}
                    </div>        
                </div>
            </td>
            <td colspan="1">
                <div class="row">
                    <div class="div-title">
                        ST / ESTADO:
                    </div>
                    <div>
                        {{$precal->c2_estado}}
                    </div>        
                </div>
            </td>
            <td colspan="1">
                <div class="row">
                    <div class="div-title">
                        ZIP / C POSTAL
                    </div>
                    <div>
                        {{$precal->c2_tx_zip}}
                    </div>        
                </div>
            </td>
        </tr>
        {{-- Fin Fila de direccion--}}
        <tr>
            <td colspan="4">
                <div class="row">
                    <div class="div-title">
                        EMAIL
                    </div>
                    <div>
                        {{$precal->tx_email}}
                    </div>        
                </div>
            </td>
            <td colspan="1">
                <div class="row">
                    <div class="div-title">
                        RELATIONSHIP / RELACION
                    </div>
                    <div>
                        {{$precal->tx_relacion_c2_con_c1}}
                    </div>        
                </div>
            </td>
            <td colspan="3">
                <div class="row">
                    <div class="div-title">
                        EMAIL
                    </div>
                    <div>
                        {{$precal->c2_tx_email}}
                    </div>        
                </div>
            </td>
        </tr>
        {{--}}
        <tr>
            <td colspan="4">
                <div class="row">
                    <div class="div-title">
                        # of adults at home/
                        # adultos en casa
                    </div>
                    <div>
                        {{$precal->personas}}
                    </div>        
                </div>
            </td>
            <td colspan="4">
                <div class="row">
                    <div class="div-title">
                        # of kids at home/
                        # niños en casa
                    </div>
                    <div>
                        {{$precal->personas}}
                    </div>        
                </div>
            </td>            
        </tr>
        --}}
    </table>
    <table>
        <tr class="section-title">
            <th colspan="6">Mortgage Information / Información Sobre Su Casa</th>
        </tr>
        <tr>
            <td colspan="1">
                <div class="div-title">
                    Status:
                </div>
                <div>
                    {{$precal->tx_hipoteca_estatus}}
                </div>
            </td>
            <td colspan="1">
                <div class="div-title">
                    Mortgaged Company:
                </div>
                <div>
                    {{$precal->tx_hipoteca_company}}
                </div>
            </td>
            <td colspan="1">
                <div class="div-title">
                    Monthly Payment:
                </div>
                <div>
                    {{$precal->nu_hipoteca_renta}}
                </div>
            </td>
            <td colspan="1">
                <div class="div-title">
                    How Long Here
                </div>
                <div>
                    {{$precal->nu_hipoteca_tiempo}}
                </div>
            </td>
            <td colspan="1">
                <div class="div-title">
                    # of adults at home/ # adultos en casa
                </div>
                <div>
                    {{$precal->personas}}
                </div>
            </td>
            <td colspan="1">
                <div class="div-title">
                    # of kids at home/ # niños en casa
                </div>
                <div>
                    {{$precal->personas}}
                </div>
            </td>
        </tr>        
    </table>
    <table>
        <tr class="section-title">
            <th colspan="4">Income Info / Información de Ingresos</th>
            <th colspan="4">Income Info / Información de Ingresos</th>
        </tr>
        <tr>
            <td colspan="2">
                <div class="div-title">
                    Employer:
                </div>
                <div>
                    {{$precal->tx_nombre_trabajo}}
                </div>
            </td>
            <td colspan="1">
                <div class="div-title">
                    Years:
                </div>
                <div>
                    {{$precal->nu_tiempo_trabajo}}
                </div>
            </td>
            <td colspan="1">
                <div class="div-title">
                    Salary / Salario:
                </div>
                <div>
                    {{$precal->nu_ingreso_principal}}
                </div>
            </td>
            <td colspan="2">
                <div class="div-title">
                    Employer:
                </div>
                <div>
                    {{$precal->c2_tx_nombre_trabajo}}
                </div>
            </td>
            <td colspan="1">
                <div class="div-title">
                    Years:
                </div>
                <div>
                    {{$precal->c2_nu_tiempo_trabajo}}
                </div>
            </td>
            <td colspan="1">
                <div class="div-title">
                    Salary / Salario:
                </div>
                <div>
                    {{$precal->c2_nu_ingreso_principal}}
                </div>
            </td>
        </tr>        
        <tr>
            <td colspan="2">
                <div class="div-title">
                    Position:
                </div>
                <div>
                    {{$precal->tx_cargo}}
                </div>
            </td>
            <td colspan="2">
                <div class="div-title">
                    Bussines PH / Telefono de trabajo
                </div>
                <div>
                    {{$precal->tx_telefono_trabajo}}
                </div>
            </td>
            <td colspan="2">
                <div class="div-title">
                    Position:
                </div>
                <div>
                    {{$precal->c2_tx_cargo}}
                </div>
            </td>
            <td colspan="2">
                <div class="div-title">
                    Bussines PH / Telefono de trabajo 
                </div>
                <div>
                    {{$precal->c2_tx_telefono_trabajo}}
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <div class="div-title">
                    Employment Address / Dirección de Empleo:
                </div>
                <div>
                    {{$precal->tx_direccion1_trabajo}} {{$precal->tx_direccion2_trabajo}}
                </div>
            </td>
            <td colspan="4">
                <div class="div-title">
                    Employment Address / Dirección de Empleo:
                </div>
                <div>
                    {{$precal->c2_tx_direccion1_trabajo}} {{$precal->c2_tx_direccion2_trabajo}}
                </div>
            </td>
        </tr>        
    </table>
    <table>
        <tr>
            <td colspan="8" class="text-row">
                Alimony or child support of separate maintenance payments are optional information and need not to be revealed if you do not choose to rely on such income in applying for credit.
                Pensión alimenticia o manutención de menores son información opcional y no necesita ser revelado si usted no desea que se tengan en cuenta esos ingresos en la solicitud de crédito
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <div class="div-title">
                    SOURCE OF OTHER ICOME / ORIGEN DE OTRO INGRESO
                </div>
                <div>
                    {{$precal->nu_otros_ingresos}}
                </div>
            </td>
            <td colspan="4">
                <div class="div-title">
                    SOURCE OF OTHER ICOME / ORIGEN DE OTRO INGRESO
                </div>
                <div>
                    {{$precal->c2_nu_otros_ingresos}}
                </div>
            </td>
        </tr>
    </table>

    <table>
        <tr class="section-title">
            <th colspan="4">Personal References / Referencias Personales</th>
        </tr>
        <tr>
            <td colspan="2">
                <div class="div-title">
                    Name / NOMBRE
                </div>
                <div>
                    {{$precal->tx_ref1_nom}}
                </div>
            </td>
            <td colspan="1">
                <div class="div-title">
                    RELATIONSHIP / RELACION
                </div>
                <div>
                    {{$precal->tx_ref1_rel}}
                </div>
            </td>
            <td colspan="1">
                <div class="div-title">
                    PHONE / TELEFONO
                </div>
                <div>
                    {{$precal->tx_ref1_tlf}}
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="div-title">
                    NAME / NOMBRE
                </div>
                <div>
                    {{$precal->tx_ref2_nom}}
                </div>
            </td>
            <td colspan="1">
                <div class="div-title">
                    RELATIONSHIP / RELACION
                </div>
                <div>
                    {{$precal->tx_ref2_rel}}
                </div>
            </td>
            <td colspan="1">
                <div class="div-title">
                    PHONE / TELEFONO
                </div>
                <div>
                    {{$precal->tx_ref2_tlf}}
                </div>
            </td>
        </tr>
    </table>
    <div>
        <div class="row text-row">
            By signing below, you certify that any information in the application is true and complete. You authorize us to confirm this information in this application and to give out information about you
            or your account to credit report agencies and others who are allowed to received it. You authorize and instruct us to request and receive credit information about you from any credit report
            agency third party.
        </div>
        <br>
        <div class="row text-row">
            Al firmar, usted certifica que toda la información en la solicitudes verdadera y completa. Usted nos autoriza a confirmar la información en esta solicitud y dar a conocer información sabre
            usted o su cuenta a las agencias de reporte de crédito y otras personas a quienes se permite recibirlo. Usted nos autoriza y da instrucciones de solicitar y recibir información de crédito
            acerca de usted de cualquier agencia de crédito. 
        </div>
    </div>
       <div>
        <table style="width: 100%; border-collapse: collapse; border: 0;">
            <tr>
                <td class="td-no-border">
                    @if ($precal->firma_cliente1)
                    <div>
                        <img aria-expanded="false" src="{{  $precal->firma_cliente1 }}" class="firma-imagen"> 
                    </div>
                    @endif
                    <div class="signature-line">
                        SIGNATURE / FIRMA
                    </div>
                </td>
                <td class="td-no-border">
                    {{$precal->fecha}}
                    <div class="signature-line">
                        DATE / FECHA
                    </div>
                </td>
                <td class="td-no-border">
                    @if ($precal->firma_cliente2)
                    <img aria-expanded="false" src="{{  $precal->firma_cliente2 }}" class="firma-imagen"> 
                    @endif
                    <div class="signature-line">
                        SIGNATURE / FIRMA
                    </div>
                </td>
                <td class="td-no-border">
                    {{$precal->fecha}}
                    <div class="signature-line">
                        DATE / FECHA
                    </div>
                </td>
    </tr>
    </table>
</div>
<div class="footer">
    230 Capcom Ave Ste. 103, Wake Forest NC 27587 • Ph (919) 790-5475 • Fax (919) 790-5476 www.aquafeelsolutions.com •
    info@aquafeelsolutions.com
</div>
<div class="page-break"></div> <!-- Salto de página -->
{{-- Segunda pagina del reporte --}}
<div class="header">
    <div class="header-section">
        <img src="{{ $logoPath }}" alt="Logo Base64"> 
        <div class="header-section-text">
            230 Capcom Ave Ste. 103, Wake Forest NC 27587
            PH (919) 790-5475 • FAX (919) 790-5476 • www.aquafeelsolutions.com • info@aquafeelsolutions.com
        </div>
    </div>
</div>        
<br>
<div class="centered-bold-div">
    PROPOSAL - WORK ORDER – AGREEMENT / PROPUESTA- ORDEN DE TRABAJO - ACUERDO
</div>
    <table>
        <tr>
            <td>
                <div class="row">
                    <div class="div-title">
                        PRIMARY CUSTOMER/ CLIENTE PRIMARIO
                    </div>
                    <div>
                        {{$precal->tx_primer_nombre}} {{$precal->tx_primer_apellido}}
                    </div>        
                </div>
            </td>
            <td>
                <div class="row">
                    <div class="div-title">
                        HOME PH#/ TELEFONO DE CASA
                    </div>
                    <div></div>        
                </div>
            </td>
            <td colspan="2">
                <div class="row">
                    <div class="div-title">
                        CELL PH#/ TELEFONO CELULAR
                    </div>
                    <div>
                        {{$precal->tx_telefono}}
                    </div>        
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="row">
                    <div class="div-title">
                        SECONDARY CUSTOMER/ CLIENTE SECUNDARIO
                    </div>
                    <div>
                        {{$precal->c2_tx_primer_nombre}} {{$precal->c2_tx_primer_apellido}}
                    </div>        
                </div>
            </td>
            <td>
                <div class="row">
                    <div class="div-title">
                        HOME PH#/ TELEFONO DE CASA
                    </div>
                    <div></div>        
                </div>
            </td>
            <td colspan="2">
                <div class="row">
                    <div class="div-title">
                        CELL PH#/ TELEFONO CELULAR
                    </div>
                    <div>
                        {{$precal->c2_tx_telefono}}
                    </div>        
                </div>
            </td>
        </tr>        
        <tr>
            <td>
                <div class="row">
                    <div class="div-title">
                        ADDRESS/ DIRECCION
                    </div>
                    <div>
                        {{$precal->direccion}}
                    </div>        
                </div>
            </td>
            <td>
                <div class="row">
                    <div class="div-title">
                        CITY/ CIUDAD
                    </div>
                    <div>
                        {{$precal->tx_ciudad}}
                    </div>        
                </div>
            </td>
            <td>
                <div class="row">
                    <div class="div-title">
                        STATE/ ESTADO
                    </div>
                    <div>
                        {{$precal->estado}}
                    </div>        
                </div>
            </td>
            <td>
                <div class="row">
                    <div class="div-title">
                        ZIP /C POSTAL
                    </div>
                    <div>
                        {{$precal->tx_zip}}
                    </div>        
                </div>
            </td>
        </tr>
    </table>
    <br>
    <div class="row text-row">
        Aquafeel Solutions hereby proposes to provide all the necessary products, materials, and labor for the installation as detailed below. The Buyer(s) acknowledges and agrees to place an
        order and purchase said products, materials, labor, and installation as specified below:
    </div>
    <div class="row text-row">
        Aquafeel Solutions propone proporcionar todos los productos, materiales y mano de obra necesarios para la instalación según se detalla a continuación. El(los) Comprador(es)
        reconoce(n) y acepta(n) realizar un pedido y adquirir dichos productos, materiales, mano de obra e instalación según se especifica a continuación:
    </div>
    <br>
    <table>
        <tr>
            <td>
                <div class="row">
                    <div class="div-title">
                        TREATMENT PLANT/ PLANTA DE TRATAMIENTO
                    </div>
                    <div>
                        WATER PURIFICATION SYSTEM
                    </div>        
                </div>
            </td>
            <td>
                <div class="row">
                    <div class="div-title">
                        BRAND/ MARCA
                    </div>
                    <div>
                        AQUAFEEL
                    </div>        
                </div>
            </td>
            <td colspan="2">
                <div class="row">
                    <div class="div-title">
                        MODEL/ MODELO
                    </div>
                    <div>
                        AQUAFEEL SYSTEM
                    </div>        
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="row">
                    <div class="div-title">
                        REVERSE OSMOSIS SYSTEM/ SISTEMA REVERSE OSMOSIS
                    </div>
                    <div>
                        R/O ALKALINE
                    </div>        
                </div>
            </td>
            <td>
                <div class="row">
                    <div class="div-title">
                        BRAND/ MARCA
                    </div>
                    <div>
                        WATTS
                    </div>        
                </div>
            </td>
            <td colspan="2">
                <div class="row">
                    <div class="div-title">
                        MODEL/ MODELO
                    </div>
                    <div>
                        W-525
                    </div>        
                </div>
            </td>
        </tr>        
        <tr>
            <td colspan="4">
                <div class="row">
                    <div class="div-title">
                        OTHER PRODUCTS OR PROMOTIONS INCLUDED/ OTROS PRODUCTOS O PROMOCIONES INCLUIDAS
                    </div>
                    <div>
                        {{$precal->producto_promocional}}
                    </div>        
                </div>
            </td>
        </tr>
    </table>
    <div class="centered-bold-div">
        INSTALLATION INSTRUCTIONS/ INSTRUCCIONES DE INSTALACION
    </div>
    <br>
    <div class="row text-row">
        The location of installation of the water treatment plant will be established by the personnel of Aquafeel Solutions and the Buyer(s), in the best possible location.
    </div>
    <div class="row text-row">
        El lugar de instalación de la planta de tratamiento de agua, va a ser establecido por el personal de Aquafeel Solutions y el Comprador(es}, en la mejor locación posible.
    </div>
    <br>
    <table>
        <tr>
            <td>
                <div class="row">
                    <div class="div-title">
                        NUMBER OF PEOPLE LIVING IN THE HOUSE/
                        NUMERO DE PERSONAS QUE VIVEN EN LA CASA
                    </div>
                    <div>
                       {{$precal->personas}}
                    </div>        
                </div>
            </td>
            <td>
                <div class="row">
                    <div class="div-title">
                        WATER SOURCE/ TIPO DE AGUA
                    </div>
                    <div>
                        {{$precal->tipo_agua}}
                    </div>        
                </div>
            </td>
            <td>
                <div class="row">
                    <div class="div-title">
                        ESTIMATED INSTALLATION DATE/
                        DIA ESTIMADO PARA INSTALACION
                    </div>
                    <div>
                        ** FALTA FECHA INSTALACION **
                    </div>        
                </div>
            </td>
            <td>
                <div class="row">
                    <div class="div-title">
                        TIME/ HORA
                    </div>
                    <div>
                        {{$precal->hora_instalacion}}
                    </div>        
                </div>
            </td>           
        </tr>
    </table>
    <div class="centered-bold-div">
        TERMS OR PAYMENT METHOD/ TERMINOS O METODO DE PAGO
    </div>
    <br>
    <table>
        <tr>
            <td>
                <div class="row">
                    <div class="div-title">
                        PATMENT METHOD/ METODO DE PAGO
                    </div>                    
                </div>
            </td>
            <td>
                <div class="row">
                    <div class="div-title">
                        CREDIT CARD USE / USO DE TARJETA DE CREDITO
                    </div>
                </div>
            </td>
            <td>
                <div class="row">
                    <div class="div-title">
                        RECIPIENT OF CHECKS/ DESTINATARIO DE LOS CHEQUES
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="row">
                    <div>
                        {{$precal->metodo_de_pago_proyecto}}
                    </div>        
                </div>
            </td>
            <td>
                <div class="row">
                    <div>
                        SEPARATE CC AUTH FORM / FIRME FORMA ADJUNTA
                    </div>        
                </div>
            </td>
            <td>
                <div class="row">
                    <div>
                        AQUAFEEL SOLUTIONS
                    </div>        
                </div>
            </td>
        </tr>
    </table>
    <br>
    <div class="row text-row">
        With your signature, you acknowledge that you agree to the terms of this contract, that you have applied for credit, and you authorize the seller to obtain a consumer credit report. You agree
        to pay monthly interest charges in addition to the sale price specified in the boxes.
    </div>
    <div class="row text-row">
        Con su firma usted(es) reconoce(n) que está de acuerdo con los términos de este contrato, que ha solicitado crédito y autoriza al vendedor a obtener un informe de crédito del consumidor.
        Usted(es) está(n) acepta(n) pagar cargos de interés mensuales, además del precio de venta especificado en las casillas
    </div>
    <br>
    <table>
        <tr>
            <td>
                <div class="row">
                    <div class="div-title">
                        Price of System/ Precio del
                        Sistema
                    </div>                    
                </div>
            </td>
            <td>
                <div class="row">
                    <div class="div-title">
                        Install/ Instalación
                    </div>
                </div>
            </td>
            <td>
                <div class="row">
                    <div class="div-title">
                        Tax/ Impuestos
                    </div>
                </div>
            </td>
            <td>
                <div class="row">
                    <div class="div-title">
                        Total Price/ Precio Total
                    </div>
                </div>
            </td>
            <td>
                <div class="row">
                    <div class="div-title">
                        Total Down Payment
                    </div>
                </div>
            </td>
            <td>
                <div class="row">
                    <div class="div-title">
                        Total Amount Financed/ Cantidad a Financiar
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="row">
                    <div>
                        {{$precal->precio_total_del_sistema}}
                    </div>        
                </div>
            </td>
            <td>
                <div class="row">
                    <div>
                        $0.00
                    </div>        
                </div>
            </td>
            <td>
                <div class="row">
                    <div>
                        $0.00
                    </div>        
                </div>
            </td>
            <td>
                <div class="row">
                    <div>
                        {{$precal->precio_total_del_sistema}}
                    </div>        
                </div>
            </td>
            <td>
                <div class="row">
                    <div>
                        $0.00
                    </div>        
                </div>
            </td>
            <td>
                <div class="row">
                    <div>
                        {{$precal->precio_total_del_sistema}}
                    </div>        
                </div>
            </td>
        </tr>
    </table>
    <br>
    <div class="row text-row">
        DO NOT SIGN THIS AGREEMENT UNTIL YOU HAVE READ IT AND ALL THE BLANKS ARE COMPLETED. YOU HAVE THE RIGHT TO RECEIVE A COMPLETE COPY OF THIS AGREEMENT. YOU HAVE THE
        RIGHT TO PREPAY THE FULL AMOUNT AT ANY TIME AND TO BE NOTIFIED OF THE FULL BALANCE DUE AND ANY REFUND AMOUNT IF THE CONTRACT IS PAID IN FULL.
    </div>
    <div class="row text-row">
        NO FIRME ESTE CONTRATO HASTA QUE LO HAYA LEIDO Y TODOS LOS ESPACIOS ESTEN COMPETOS. USTED TIENE EL DERECHO DE RECIBIR UNA COPIA COMPLETA DE ESTE CONTRATO. USTED
        TIENE EL DERECHO a PAGAR CON ANTICIPACION El MONTO TOTAL EN CUALOUIER MOMENTO Y SER NOTIFICADO DEL SALDO TOTAL A PAGAR Y CUALOUIER CANTIDAD DE REEMBOLSO SI EL
        CONTRATO ES PAGADO TOTALMENTE.
    </div>
    <div class="footer"></div>
    {{-- Pagina de firmas tercera pagina --}}   
    <div class="page-break"></div>
    <div class="header">
        <div class="header-section">
            <img src="{{ $logoPath }}" alt="Logo Base64"> 
            <div class="header-section-text">
                230 Capcom Ave Ste. 103, Wake Forest NC 27587
                PH (919) 790-5475 • FAX (919) 790-5476 • www.aquafeelsolutions.com • info@aquafeelsolutions.com
            </div>
        </div>
    </div>        
    <div>
        <div class="row">            
            <div class="centered-bold-div">
                <h1>SIGNATURE (S) / FIRMA(S)</h1>
            </div>
        </div>
        <br>
        <table style="width: 100%; border-collapse: collapse; border: 0;">
            <tr>
                <td class="td-no-border" style="text-align: center;">
                    <div>
                        {{$precal->tx_primer_nombre}} {{$precal->tx_primer_apellido }}
                    </div>
                    <div class="signature-line">
                        PRIMARY CUSTOMER/ CLIENTE PRIMARIO
                    </div>
                </td>
                <td class="td-no-border" style="text-align: center;">
                    <div>
                        {{$precal->c2_tx_primer_nombre}} {{$precal->c2_tx_primer_apellido }}
                    </div>
                    <div class="signature-line">
                        SECONDARY CUSTOMER/ CLIENTE SECUNDARIO
                    </div>
                </td>
            </tr>
            <tr>
                <td class="td-no-border" style="text-align: center;">
                    @if ($precal->firma_cliente1)
                        <img aria-expanded="false" src="{{  $precal->firma_cliente1 }}" class="firma-imagen"> 
                    @endif
                    <div class="signature-line">
                        SIGNATURE / FIRMA
                    </div>
                </td>
                <td class="td-no-border" style="text-align: center;">
                        @if ($precal->firma_cliente2)
                            <img aria-expanded="false" src="{{  $precal->firma_cliente2 }}" class="firma-imagen"> 
                        @endif    
                    <div class="signature-line">
                           
                        SIGNATURE/ FIRMA
                    </div>
                </td>
            </tr>
            <tr>
                <td class="td-no-border" style="text-align: center;">
                    <div>
                        {{$precal->fecha}}
                    </div>
                    <div class="signature-line">
                        DATE/ FECHA
                    </div>
                </td>
                <td class="td-no-border" style="text-align: center;">
                    <div>
                        {{$precal->fecha}}
                    </div>
                    <div class="signature-line">
                        DATE / FECHA
                    </div>
                </td>
            </tr>
            <tr>
                <td class="td-no-border" style="text-align: center;">
                    <div class="signature-line">
                        COMPANY REPRESENTATIVE
                    </div>
                </td>
                <td class="td-no-border" style="text-align: center;">
                    <div>
                        {{$precal->aquafeel_analist}}
                    </div>
                    <div class="signature-line">
                        AQUAFEEL ANAYLST/ AQUAFEEL ANALIST
                    </div>
                </td>
            </tr>
            <tr>
                <td class="td-no-border" style="text-align: center;">
                    <div class="signature-line">
                        SIGNATURE/ FIRMA
                    </div>
                </td>
                <td class="td-no-border">
                    <div></div>
                </td>
            </tr>
            <tr>
                <td class="td-no-border"  style="text-align: center;">
                    <div class="signature-line">
                        DATE/ FECHA
                    </div>
                </td>
                <td class="td-no-border">
                    <div></div>
                </td>
            </tr>
        </table>
    </div>
    <div>
        <h4>
            THIS CONTRACT SHALL BE DEEMED VALID SOLELY UPON OBTAINING THE APPROVAL SIGNATURE OF
        THE AUTHORIZED PERSONNEL FROM THE CENTRAL OFFICE OF AQUAFEEL SOLUTIONS. PLEASE
            REFER TO THE REVERSE SIDE OF THIS PAGE FOR ADDITIONAL TERMS AND CONDITIONS. IN THE
            EVENT OF CANCELLATION BEYOND THE THREE-DAY GRACE PERIOD, A CHARGE OF 20% SHALL BE
            APPLIED FOR SYSTEM RE-STORAGE, AS STIPULATED HEREIN, FOR THE PURPOSE OF PROTECTING
            THE LEGAL RIGHTS AND INTERESTS OF THE PARTIES INVOLVED.
        </h4>
    </div>
    <div>
        <h4>
            ESTE CONTRATO SERÁ CONSIDERADO VÁLIDO ÚNICAMENTE AL OBTENER LA FIRMA DE
            APROBACIÓN DEL PERSONAL AUTORIZADO DE LA OFICINA CENTRAL DE AQUAFEEL SOLUTIONS.
            POR FAVOR, CONSULTE LOS TÉRMINOS Y CONDICIONES ADICIONALES AL REVERSO DE ESTA
            PÁGINA. EN CASO DE CANCELACIÓN DESPUÉS DEL PERÍODO DE GRACIA DE TRES DÍAS, SE
            APLICARÁ UN CARGO DEL 20% POR EL ALMACENAMIENTO ADICIONAL DEL SISTEMA, SEGÚN LO
            ESTIPULADO AQUÍ, CON EL FIN DE PROTEGER LOS DERECHOS E INTERESES LEGALES DE LAS
            PARTES INVOLUCRADAS.
        </h4>
    </div>
    <div class="footer"></div>
</body>
</html>

