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
            padding: 0px;
            font-weight: bold;
            font-size: 8px;
        }
        .div{
            padding: 0px;          
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
            font-size: 8px;
        }
        .header-section-text h1 {
            margin: 0;
            font-size: 10px; /* Texto ligeramente más grande */
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
            padding: 0px; /* Padding de 3px en todos los lados */
        }
        .firma-imagen {
            width: 100px; /* Ancho de la imagen */
            height: 30px; /* Alto de la imagen */
            object-fit: cover; /* Mantener la proporción y recortar si es necesario */
        } 
        body {
            font-family: Arial, Helvetica, sans-serif;
    }      
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
                        {{$precal->fecha}}
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
                        
                    </div>
                    <div class="signature-line">
                            COMPANY REPRESENTATIVE
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
                    <div class="signature-line">
                        DATE / FECHA
                    </div>
                </td>
            </tr>
            <tr>
                <td class="td-no-border" style="text-align: center;" colspan="2">
                    <div>
                        {{$precal->aquafeel_analist}}
                    </div>
                    <div class="signature-line">
                        AQUAFEEL ANAYLST/ AQUAFEEL ANALIST
                    </div>
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

