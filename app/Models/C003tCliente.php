<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class C003tCliente
 * 
 * @property int $co_cliente
 * @property int|null $nu_documento_id
 * @property string|null $tx_licencia
 * @property Carbon|null $fe_vencimto_lic
 * @property string|null $tx_primer_nombre
 * @property string|null $tx_segundo_nombre
 * @property string|null $tx_primer_apellido
 * @property string|null $tx_segundo_apellido
 * @property string|null $tx_telefono
 * @property string|null $tx_email
 * @property string|null $tx_direccion1
 * @property string|null $tx_direccion2
 * @property int|null $co_zip
 * @property string|null $tx_notas
 * @property Carbon|null $fe_fecha_cita
 * @property Carbon|null $fe_fecha_nacimiento
 * @property bytea|null $im_foto_id
 * @property bytea|null $im_firma
 * @property string|null $tx_nombre_trabajo
 * @property int|null $nu_ingreso_principal
 * @property int|null $nu_otros_ingresos
 * @property int|null $nu_tiempo_trabajo
 * @property string|null $tx_cargo
 * @property string|null $tx_telefono_trabajo
 * @property string|null $tx_direccion1_trabajo
 * @property string|null $tx_direccion2_trabajo
 * @property string|null $tx_zip_trabajo
 * @property bool|null $bo_tipo_cliente
 * @property string|null $tx_zip
 * @property string|null $tx_ciudad
 * @property string|null $tx_estado
 * @property Carbon|null $fe_creacion
 * @property int|null $co_usuario
 * @property int|null $co_estado
 * @property int|null $co_ryve_usuario
 * @property int|null $co_quick_base_id
 * 
 * @property C002tUsuario|null $c002t_usuario
 * @property Collection|C001tAplicacione[] $c001t_aplicaciones
 * @property Collection|C015tBancosAplicacionesCliente[] $c015t_bancos_aplicaciones_clientes
 *
 * @package App\Models
 */
class C003tCliente extends Model
{
	protected $table = 'c003t_clientes';
	protected $primaryKey = 'co_cliente';
	public $timestamps = false;

	protected $casts = [
		'nu_documento_id' => 'int',
		'fe_vencimto_lic' => 'datetime',
		'co_zip' => 'int',
		'fe_fecha_cita' => 'datetime',
		'fe_fecha_nacimiento' => 'datetime',
		'im_foto_id' => 'bytea',
		'im_firma' => 'bytea',
		'nu_ingreso_principal' => 'int',
		'nu_otros_ingresos' => 'int',
		'nu_tiempo_trabajo' => 'int',
		'bo_tipo_cliente' => 'bool',
		'fe_creacion' => 'datetime',
		'co_usuario' => 'int',
		'co_estado' => 'int',
		'co_ryve_usuario' => 'int',
		'co_quick_base_id' => 'int'
	];

	protected $fillable = [
		'nu_documento_id',
		'tx_licencia',
		'fe_vencimto_lic',
		'tx_primer_nombre',
		'tx_segundo_nombre',
		'tx_primer_apellido',
		'tx_segundo_apellido',
		'tx_telefono',
		'tx_email',
		'tx_direccion1',
		'tx_direccion2',
		'co_zip',
		'tx_notas',
		'fe_fecha_cita',
		'fe_fecha_nacimiento',
		'im_foto_id',
		'im_firma',
		'tx_nombre_trabajo',
		'nu_ingreso_principal',
		'nu_otros_ingresos',
		'nu_tiempo_trabajo',
		'tx_cargo',
		'tx_telefono_trabajo',
		'tx_direccion1_trabajo',
		'tx_direccion2_trabajo',
		'tx_zip_trabajo',
		'bo_tipo_cliente',
		'tx_zip',
		'tx_ciudad',
		'tx_estado',
		'fe_creacion',
		'co_usuario',
		'co_estado',
		'co_ryve_usuario',
		'co_quick_base_id'
	];

	public function c002t_usuario()
	{
		return $this->belongsTo(C002tUsuario::class, 'co_usuario');
	}

	public function c001t_aplicaciones()
	{
		return $this->hasMany(C001tAplicacione::class, 'co_cliente2');
	}

	public function c015t_bancos_aplicaciones_clientes()
	{
		return $this->hasMany(C015tBancosAplicacionesCliente::class, 'co_cliente');
	}

	public function i020t_tipo_fuente_cliente(){
		return $this->hasOne(I020tTipoFuenteCliente::class,'co_fuente');
	}
}
