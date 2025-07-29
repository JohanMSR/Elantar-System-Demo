<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class C040tBasePrecalificacion
 * 
 * @property int $co_base
 * @property int $co_idioma
 * @property int $co_metodo_pago_proyecto
 * @property float|null $nu_precio_total
 * @property float|null $nu_down_payment
 * @property string|null $tx_metodo_pago
 * @property int|null $co_metodo_down_payment
 * @property float|null $nu_precio_financiado
 * @property bool|null $bo_co_signer
 * @property int|null $co_producto
 * @property int|null $nu_cantidad_adultos
 * @property int|null $nu_cantidad_ninos
 * @property int|null $co_tipo_agua
 * @property Carbon|null $fe_instalacion
 * @property string|null $tx_hora_instalacion
 * @property string|null $tx_primer_nombre_c1
 * @property string|null $tx_inicial_segundo_c1
 * @property string|null $tx_apellido_c1
 * @property string|null $tx_telefono_c1
 * @property string|null $tx_email_c1
 * @property string|null $tx_direccion1_c1
 * @property string|null $tx_direccion2_c1
 * @property string|null $tx_ciudad_c1
 * @property int|null $co_estado_c1
 * @property string|null $tx_zip_c1
 * @property Carbon|null $fe_fecha_nacimiento_c1
 * @property int|null $tx_social_security_number_c1
 * @property string|null $tx_licencia_c1
 * @property Carbon|null $fe_vencimto_licencia_c1
 * @property string|null $tx_url_img_photoid_c1
 * @property string|null $tx_nombre_trabajo_c1
 * @property int|null $nu_tiempo_trabajo_c1
 * @property string|null $tx_puesto_c1
 * @property int|null $nu_salario_mensual_c1
 * @property string|null $tx_direc1_trab_c1
 * @property string|null $tx_direc2_trab_c1
 * @property string|null $tx_ciudad_trab_c1
 * @property int|null $co_estado_trab_c1
 * @property string|null $tx_zip_trab_c1
 * @property string|null $tx_tlf_trab_c1
 * @property int|null $nu_ingresos_alter_c1
 * @property string|null $tx_relacion_c2_con_c1
 * @property string|null $tx_primer_nombre_c2
 * @property string|null $tx_inicial_segundo_c2
 * @property string|null $tx_apellido_c2
 * @property string|null $tx_telefono_c2
 * @property string|null $tx_email_c2
 * @property Carbon|null $fe_fecha_nacimiento_c2
 * @property int|null $tx_social_security_number_c2
 * @property string|null $tx_licencia_c2
 * @property Carbon|null $fe_vencimto_licencia_c2
 * @property string|null $tx_url_img_photoid_c2
 * @property string|null $tx_nombre_trabajo_c2
 * @property int|null $nu_tiempo_trabajo_c2
 * @property string|null $tx_puesto_c2
 * @property int|null $nu_salario_mensual_c2
 * @property string|null $tx_direc1_trab_c2
 * @property string|null $tx_direc2_trab_c2
 * @property string|null $tx_ciudad_trab_c2
 * @property int|null $co_estado_trab_c2
 * @property string|null $tx_zip_trab_c2
 * @property string|null $tx_tlf_trab_c2
 * @property int|null $nu_ingresos_alter_c2
 * @property string|null $tx_hipoteca_estatus
 * @property string|null $tx_hipoteca_company
 * @property int|null $nu_hipoteca_renta
 * @property int|null $nu_hipoteca_tiempo
 * @property string|null $tx_ref1_nombre
 * @property string|null $tx_ref1_telefono
 * @property string|null $tx_ref1_relacion
 * @property string|null $tx_ref2_nombre
 * @property string|null $tx_ref2_telefono
 * @property string|null $tx_ref2_relacion
 * @property string|null $tx_url_img_signature_c1
 * @property string|null $tx_url_img_signature_c2
 * @property Carbon|null $fe_registro
 * @property int|null $co_qb_setter
 * @property int|null $co_qb_owner
 * @property int|null $co_cliente
 * @property string|null $tx_idioma
 * @property int|null $co_estatus_aplicacion
 * @property int|null $co_qb_id_proyecto
 * @property string|null $tx_url_orden_trabajo
 * @property string|null $tx_hipoteca_tiempo
 *
 * @package App\Models
 */
class C040tBasePrecalificacion extends Model
{
	protected $table = 'c040t_base_precalificacion';
	protected $primaryKey = 'co_base';
	public $timestamps = false;

	protected $casts = [
		'co_idioma' => 'int',
		'co_metodo_pago_proyecto' => 'int',
		'nu_precio_total' => 'float',
		'nu_down_payment' => 'float',
		'co_metodo_down_payment' => 'int',
		'nu_precio_financiado' => 'float',
		'bo_co_signer' => 'bool',
		'co_producto' => 'int',
		'nu_cantidad_adultos' => 'int',
		'nu_cantidad_ninos' => 'int',
		'co_tipo_agua' => 'int',
		'fe_instalacion' => 'datetime',
		'co_estado_c1' => 'int',
		'fe_fecha_nacimiento_c1' => 'datetime',
		'tx_social_security_number_c1' => 'int',
		'fe_vencimto_licencia_c1' => 'datetime',
		'nu_tiempo_trabajo_c1' => 'int',
		'nu_salario_mensual_c1' => 'int',
		'co_estado_trab_c1' => 'int',
		'nu_ingresos_alter_c1' => 'int',
		'fe_fecha_nacimiento_c2' => 'datetime',		
		'fe_vencimto_licencia_c2' => 'datetime',
		'nu_tiempo_trabajo_c2' => 'int',
		'nu_salario_mensual_c2' => 'int',
		'co_estado_trab_c2' => 'int',
		'nu_ingresos_alter_c2' => 'int',
		'nu_hipoteca_renta' => 'int',
		'nu_hipoteca_tiempo' => 'int',
		'fe_registro' => 'datetime',
		'co_qb_setter' => 'int',
		'co_qb_owner' => 'int',
		'co_cliente' => 'int',
		'co_estatus_aplicacion' => 'int',
		'co_qb_id_proyecto' => 'int',
		'co_aplicacion' => 'int',
		'co_usuario_log' => 'int',
		'co_tipo_cuenta' => 'int',		
	];
	
	protected $fillable = [
		'co_idioma',
		'co_metodo_pago_proyecto',
		'nu_precio_total',
		'nu_down_payment',
		'tx_metodo_pago',
		'co_metodo_down_payment',
		'nu_precio_financiado',
		'bo_co_signer',
		'co_producto',
		'nu_cantidad_adultos',
		'nu_cantidad_ninos',
		'co_tipo_agua',
		'fe_instalacion',
		'tx_hora_instalacion',
		'tx_primer_nombre_c1',
		'tx_inicial_segundo_c1',
		'tx_apellido_c1',
		'tx_telefono_c1',
		'tx_email_c1',
		'tx_direccion1_c1',
		'tx_direccion2_c1',
		'tx_ciudad_c1',
		'co_estado_c1',
		'tx_zip_c1',
		'fe_fecha_nacimiento_c1',
		'tx_social_security_number_c1',
		'tx_licencia_c1',
		'fe_vencimto_licencia_c1',
		'tx_url_img_photoid_c1',
		'tx_nombre_trabajo_c1',
		'nu_tiempo_trabajo_c1',
		'tx_puesto_c1',
		'nu_salario_mensual_c1',
		'tx_direc1_trab_c1',
		'tx_direc2_trab_c1',
		'tx_ciudad_trab_c1',
		'co_estado_trab_c1',
		'tx_zip_trab_c1',
		'tx_tlf_trab_c1',
		'nu_ingresos_alter_c1',
		'tx_relacion_c2_con_c1',
		'tx_primer_nombre_c2',
		'tx_inicial_segundo_c2',
		'tx_apellido_c2',
		'tx_telefono_c2',
		'tx_email_c2',
		'fe_fecha_nacimiento_c2',
		'tx_social_security_number_c2',
		'tx_licencia_c2',
		'fe_vencimto_licencia_c2',
		'tx_url_img_photoid_c2',
		'tx_nombre_trabajo_c2',
		'nu_tiempo_trabajo_c2',
		'tx_puesto_c2',
		'nu_salario_mensual_c2',
		'tx_direc1_trab_c2',
		'tx_direc2_trab_c2',
		'tx_ciudad_trab_c2',
		'co_estado_trab_c2',
		'tx_zip_trab_c2',
		'tx_tlf_trab_c2',
		'nu_ingresos_alter_c2',
		'tx_hipoteca_estatus',
		'tx_hipoteca_company',
		'nu_hipoteca_renta',
		'nu_hipoteca_tiempo',
		'tx_ref1_nombre',
		'tx_ref1_telefono',
		'tx_ref1_relacion',
		'tx_ref2_nombre',
		'tx_ref2_telefono',
		'tx_ref2_relacion',
		'tx_url_img_signature_c1',
		'tx_url_img_signature_c2',
		'fe_registro',
		'co_qb_setter',
		'co_qb_owner',
		'co_cliente',
		'tx_idioma',
		'co_estatus_aplicacion',
		'co_qb_id_proyecto',
		'tx_url_orden_trabajo',
		'tx_hipoteca_tiempo',
		'co_aplicacion',
		'co_usuario_log',
		'tx_nombre_banco',
		'tx_numero_cuenta',
		'tx_numero_ruta',
		'co_tipo_cuenta',
		'tx_titular_cuenta',
	];
}
