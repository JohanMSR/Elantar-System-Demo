<?php

namespace App\Services\Dashboard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TeamDetailService extends Model 
{
    use HasFactory;


    protected $co_aplicacion;       


    public function getData($co_aplicacion){
        
        $sql = "SELECT 
        ap1.co_idioma,
        ap1.co_metodo_pago_proyecto,
        ap1.nu_precio_total,
        ap1.nu_monto_inicial       as nu_down_payment,
        mpayp.tx_nombre            as tx_metodo_pago,
        ap1.co_metodo_down_payment as co_metodo_down_payment,
        (ap1.nu_precio_total - ap1.nu_monto_inicial) as nu_precio_financiado,
        ap1.bo_co_signer,
        ap1.co_producto as co_producto,
        ap1.nu_cantidad_adultos,
        ap1.nu_cantidad_ninos,
        ap1.co_tipo_agua,
        ap1.fe_instalacion,
        ap1.tx_instalacion    as tx_hora_instalacion,
        c1.tx_primer_nombre   as tx_primer_nombre_c1,
        c1.tx_segundo_nombre  as tx_inicial_segundo_c1,
        c1.tx_primer_apellido as tx_apellido_c1,
        c1.tx_telefono        as tx_telefono_c1,
        c1.tx_email           as tx_email_c1,
        c1.tx_direccion1      as tx_direccion1_c1,
        c1.tx_direccion2      as tx_direccion2_c1,
        c1.tx_ciudad          as tx_ciudad_c1,
        c1.co_estado          as co_estado_c1,
        c1.tx_zip             as tx_zip_c1,
        c1.fe_fecha_nacimiento as fe_fecha_nacimiento_c1,
        c1.nu_documento_id     as tx_social_security_number_c1,
        c1.tx_licencia         as tx_licencia_c1,
        c1.fe_vencimto_lic     as fe_vencimto_licencia_c1,
        c1.tx_url_img_photoid    as tx_url_img_photoid_c1,
        c1.tx_nombre_trabajo     as tx_nombre_trabajo_c1,
        c1.nu_tiempo_trabajo     as nu_tiempo_trabajo_c1,
        c1.tx_cargo              as tx_puesto_c1,
        c1.nu_ingreso_principal  as nu_salario_mensual_c1,
        c1.tx_direccion1_trabajo as tx_direc1_trab_c1,
        c1.tx_direccion2_trabajo as tx_direc2_trab_c1,
        c1.tx_ciudad_trab        as tx_ciudad_trab_c1,
        c1.co_estado_trab        as co_estado_trab_c1,
        c1.tx_zip_trabajo        as tx_zip_trab_c1,
        c1.tx_telefono_trabajo   as tx_tlf_trab_c1,
        c1.nu_otros_ingresos     as nu_ingresos_alter_c1,
        ap1.tx_relacion_c2_con_c1,

        c2.tx_primer_nombre   as tx_primer_nombre_c2,
        c2.tx_segundo_nombre  as tx_inicial_segundo_c2,
        c2.tx_primer_apellido as tx_apellido_c2,
        c2.tx_telefono        as tx_telefono_c2,
            c2.tx_email           as tx_email_c2,
            c2.fe_fecha_nacimiento as fe_fecha_nacimiento_c2,
            c2.nu_documento_id     as tx_social_security_number_c2,
            c2.tx_licencia         as tx_licencia_c2,
            c2.fe_vencimto_lic     as fe_vencimto_licencia_c2,
            c2.tx_url_img_photoid    as tx_url_img_photoid_c2,
            c2.tx_nombre_trabajo     as tx_nombre_trabajo_c2,
            c2.nu_tiempo_trabajo     as nu_tiempo_trabajo_c2,
            c2.tx_cargo              as tx_puesto_c2,
            c2.nu_ingreso_principal  as nu_salario_mensual_c2,
            c2.tx_direccion1_trabajo as tx_direc1_trab_c2,
            c2.tx_direccion2_trabajo as tx_direc2_trab_c2,
            c2.tx_ciudad_trab        as tx_ciudad_trab_c2,
            c2.co_estado_trab        as co_estado_trab_c2,
            c2.tx_zip_trabajo        as tx_zip_trab_c2,
            c2.tx_telefono_trabajo   as tx_tlf_trab_c2,
            c2.nu_otros_ingresos     as nu_ingresos_alter_c2,

            ap1.tx_hipoteca_estatus as tx_hipoteca_estatus,
            ap1.tx_hipoteca_company as tx_hipoteca_company,
            ap1.nu_hipoteca_renta   as nu_hipoteca_renta,
            ap1.nu_hipoteca_tiempo  as nu_hipoteca_tiempo,
            ap1.tx_ref1_nom as tx_ref1_nombre,
            ap1.tx_ref1_tlf as tx_ref1_telefono,
            ap1.tx_ref1_rel as tx_ref1_relacion,
            ap1.tx_ref2_nom as tx_ref2_nombre,
            ap1.tx_ref2_tlf as tx_ref2_telefono,
            ap1.tx_ref2_rel as tx_ref2_relacion,

            c1.tx_url_img_signature as tx_url_img_signature_c1,
            c2.tx_url_img_signature as tx_url_img_signature_c2,
            
            
            u2.co_ryve_usuario as co_qb_setter,
            u1.co_ryve_usuario as co_qb_owner,
            c1.co_cliente    as co_cliente,
            ap1.tx_idioma    as tx_idioma,
            ap1.co_estatus_aplicacion as co_estatus_aplicacion,
            ap1.co_qb_id_proyecto     as co_qb_id_proyecto,
            ap1.tx_url_orden_trabajo  as tx_url_orden_trabajo,
            ap1.tx_hipoteca_tiempo    as tx_hipoteca_tiempo,
            ap1.co_aplicacion         as co_aplicacion,
            ap1.tx_nombre_banco,
            ap1.tx_numero_cuenta,
            ap1.tx_numero_ruta,
            ap1.co_tipo_cuenta,
            ap1.tx_titular_cuenta,

            ap1.tx_url_img_contract as contrato,
            tipocuenta.tx_tipo_cuenta as tx_tipo_cuenta,
            ap1.co_financiera,
			ap1.co_tipo_financiamiento,
			ap1.tx_meses,
			ap1.nu_tasa_interes,
			ap1.nu_pago_mensual,		    
            financiera.tx_nombre as tx_financiera,
            tipo_financiamiento.tx_nombre as tx_financiamiento
            
            FROM c001t_aplicaciones as ap1 
            INNER JOIN public.c003t_clientes as c1 on (c1.co_cliente  = ap1.co_cliente) 
            LEFT JOIN public.i010t_estados as est1 on (est1.co_estado = c1.co_estado) 
            LEFT JOIN public.c002t_usuarios as u1 on (u1.co_usuario  = ap1.co_usuario) 
            LEFT JOIN public.c002t_usuarios as u2 on (u2.co_usuario  = ap1.co_usuario_2) 
            LEFT JOIN public.c002t_usuarios as u3 on (u3.co_usuario  = u1.co_office_manager) 
            LEFT JOIN public.i001t_estatus_aplicaciones as edoap on (edoap.co_estatus_aplicacion  = ap1.co_estatus_aplicacion) 
            LEFT JOIN public.i001t_estatus_aplicaciones as edoapsig on (edoapsig.co_estatus_aplicacion  = edoap.co_estatus_siguiente) 
            LEFT JOIN public.i002t_estatus_financieros as edoapf on (edoapf.co_estatus_financiero  = ap1.co_estatus_financiero) 
            LEFT JOIN public.i002t_estatus_financieros as edoapfsig on (edoapfsig.co_estatus_financiero  = edoapf.co_estatus_siguiente) 
            LEFT JOIN public.c003t_clientes as c2 on (c2.co_cliente  = ap1.co_cliente2) 
            LEFT JOIN public.i006t_metodos_pagos as mpayp on (mpayp.co_metodo_pago  = ap1.co_metodo_pago_proyecto) 
            LEFT JOIN public.i013t_tipos_aguas as tipoagua on (tipoagua.co_tipo_agua  = ap1.co_tipo_agua)
            LEFT JOIN public.i005t_productos as promo on (promo.co_producto  = ap1.co_producto)
            LEFT JOIN public.i025t_tipo_cuenta   as tipocuenta on (tipocuenta.co_tipo_cuenta   = ap1.co_tipo_cuenta)
            LEFT JOIN public.i018t_financiera as financiera on (financiera.co_financiera = ap1.co_financiera)
            LEFT JOIN public.i019t_tipo_financiamiento as tipo_financiamiento on (tipo_financiamiento.co_tipo_financiamiento = ap1.co_tipo_financiamiento)
            where ap1.co_aplicacion = $co_aplicacion
        ";
        //ORDER BY edoap.in_orden, ap1.fe_creacion ASC
        
        $datos = DB::select($sql);        
        if(count($datos)>0){
            $datos = $datos[0];
        }    
        return $datos;
    }    
    
}
