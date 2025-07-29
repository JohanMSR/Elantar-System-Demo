<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class C001tAplicacione
 * 
 * @property int $co_aplicacion
 * @property int $co_usuario
 * @property int|null $co_usuario_2
 * @property int $co_cliente
 * @property int|null $co_cliente2
 * @property int $co_idioma
 * @property float $nu_precio_total
 * @property int $nu_cantidad_adultos
 * @property int $nu_cantidad_ninos
 * @property int $co_tipo_agua
 * @property Carbon $fe_instalacion
 * @property time without time zone $ho_instalacion
 * @property int|null $co_orden
 * @property bytea|null $im_declaracion_impuesto
 * @property bytea|null $im_comprobante_ingreso_m1
 * @property bytea|null $im_comprobante_ingreso_m2
 * @property bytea|null $im_comprobante_ingreso_m3
 * @property bytea|null $im_documentacion_propiedad
 * @property bytea|null $im_cheque_anulado
 * @property int $co_hipoteca
 * @property int $co_estatus_financiero
 * @property int $co_estatus_aplicacion
 * @property Carbon $fe_creacion
 * @property time without time zone $ho_creacion
 * @property string|null $tx_ref1_nom
 * @property string|null $tx_ref1_tlf
 * @property string|null $tx_ref1_rel
 * @property string|null $tx_ref2_nom
 * @property string|null $tx_ref2_tlf
 * @property string|null $tx_ref2_rel
 * @property bool $bo_financiado
 * 
 * @property C002tUsuario $c002t_usuario
 * @property C003tCliente|null $c003t_cliente
 * @property I012tIdioma $i012t_idioma
 * @property I013tTiposAgua $i013t_tipos_agua
 * @property C010tOrdene|null $c010t_ordene
 * @property C004tHipoteca $c004t_hipoteca
 * @property I002tEstatusFinanciero $i002t_estatus_financiero
 * @property I001tEstatusAplicacione $i001t_estatus_aplicacione
 * @property C015tBancosAplicacionesCliente $c015t_bancos_aplicaciones_cliente
 * @property Collection|C007tAplicacionesChat[] $c007t_aplicaciones_chats
 * @property Collection|C008tAplicacionesProducto[] $c008t_aplicaciones_productos
 * @property Collection|C009tAplicacionesMetodosPago[] $c009t_aplicaciones_metodos_pagos
 * @property I018tFinanciera $financiera
 * @property I019tTipoFinanciamiento $tipoFinanciamiento
 *
 * @package App\Models
 */
class C001tAplicacione extends Model
{
	protected $table = 'c001t_aplicaciones';
	protected $primaryKey = 'co_aplicacion';
	public $timestamps = false;

	protected $casts = [
		'co_usuario' => 'int',
		'co_usuario_2' => 'int',
		'co_cliente' => 'int',
		'co_cliente2' => 'int',
		'co_idioma' => 'int',
		'nu_precio_total' => 'float',
		'nu_cantidad_adultos' => 'int',
		'nu_cantidad_ninos' => 'int',
		'co_tipo_agua' => 'int',
		'fe_instalacion' => 'datetime',
		'ho_instalacion' => 'time without time zone',
		'co_orden' => 'int',
		'im_declaracion_impuesto' => 'bytea',
		'im_comprobante_ingreso_m1' => 'bytea',
		'im_comprobante_ingreso_m2' => 'bytea',
		'im_comprobante_ingreso_m3' => 'bytea',
		'im_documentacion_propiedad' => 'bytea',
		'im_cheque_anulado' => 'bytea',
		'co_hipoteca' => 'int',
		'co_estatus_financiero' => 'int',
		'co_estatus_aplicacion' => 'int',
		'fe_creacion' => 'datetime',
		'ho_creacion' => 'time without time zone',
		'bo_financiado' => 'bool',
		'co_usuario_log' => 'int',
		'co_data' => 'int',
		'co_tipo_cuenta' => 'int',
		'co_financiera' => 'int',
		'co_tipo_financiamiento' => 'int',		
		'nu_tasa_interes' => 'float',
		'nu_pago_mensual' => 'float'

	];

	protected $fillable = [
		'co_usuario',
		'co_usuario_2',
		'co_cliente',
		'co_cliente2',
		'co_idioma',
		'nu_precio_total',
		'nu_cantidad_adultos',
		'nu_cantidad_ninos',
		'co_tipo_agua',
		'fe_instalacion',
		'ho_instalacion',
		'co_orden',
		'im_declaracion_impuesto',
		'im_comprobante_ingreso_m1',
		'im_comprobante_ingreso_m2',
		'im_comprobante_ingreso_m3',
		'im_documentacion_propiedad',
		'im_cheque_anulado',
		'co_hipoteca',
		'co_estatus_financiero',
		'co_estatus_aplicacion',
		'fe_creacion',
		'ho_creacion',
		'tx_ref1_nom',
		'tx_ref1_tlf',
		'tx_ref1_rel',
		'tx_ref2_nom',
		'tx_ref2_tlf',
		'tx_ref2_rel',
		'bo_financiado',
		'co_usuario_log',
		'tx_url_img_compago1',
		'tx_url_img_compago2',
		'tx_url_img_compago3',
		'tx_url_img_checknull',
		'tx_url_img_compropiedad',
		'tx_url_img_declaraimpuesto',
		'co_data',
		'tx_nombre_banco',
		'tx_numero_cuenta',
		'tx_numero_ruta',
		'co_tipo_cuenta',
		'tx_titular_cuenta',
		'tx_url_img_contract',
		'co_financiera',
		'co_tipo_financiamiento',
		'nu_meses',
		'nu_tasa_interes',
		'nu_pago_mensual'		
	];

	public function c002t_usuario()
	{
		return $this->belongsTo(C002tUsuario::class, 'co_usuario');
	}

	public function c003t_cliente()
	{
		return $this->belongsTo(C003tCliente::class, 'co_cliente2');
	}

	public function i012t_idioma()
	{
		return $this->belongsTo(I012tIdioma::class, 'co_idioma');
	}

	public function i013t_tipos_agua()
	{
		return $this->belongsTo(I013tTiposAgua::class, 'co_tipo_agua');
	}

	public function c010t_ordene()
	{
		return $this->belongsTo(C010tOrdene::class, 'co_orden');
	}

	public function c004t_hipoteca()
	{
		return $this->belongsTo(C004tHipoteca::class, 'co_hipoteca');
	}

	public function i002t_estatus_financiero()
	{
		return $this->belongsTo(I002tEstatusFinanciero::class, 'co_estatus_financiero');
	}

	public function i001t_estatus_aplicacione()
	{
		return $this->belongsTo(I001tEstatusAplicacione::class, 'co_estatus_aplicacion');
	}

	public function c015t_bancos_aplicaciones_cliente()
	{
		return $this->hasOne(C015tBancosAplicacionesCliente::class, 'co_aplicacion');
	}

	public function c007t_aplicaciones_chats()
	{
		return $this->hasMany(C007tAplicacionesChat::class, 'co_aplicacion');
	}

	public function c008t_aplicaciones_productos()
	{
		return $this->hasMany(C008tAplicacionesProducto::class, 'co_aplicacion');
	}

	public function c009t_aplicaciones_metodos_pagos()
	{
		return $this->hasMany(C009tAplicacionesMetodosPago::class, 'co_aplicacion');
	}
	public function tipoCuenta()
	{
		return $this->belongsTo(I025tTipoCuenta::class, 'co_tipo_cuenta', 'co_tipo_cuenta');
	}	
	public function financiera()
	{
		return $this->belongsTo(I018tFinanciera::class, 'co_financiera', 'co_financiera');
	}
	public function tipoFinanciamiento()
	{
		return $this->belongsTo(I019tTipoFinanciamiento::class, 'co_tipo_financiamiento', 'co_tipo_financiamiento');
	}

}
