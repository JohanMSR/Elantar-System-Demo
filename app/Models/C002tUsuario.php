<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class C002tUsuario
 * 
 * @property int $co_usuario
 * @property string $tx_primer_nombre
 * @property string $tx_segundo_nombre
 * @property string $tx_primer_apellido
 * @property string $tx_segundo_apellido
 * @property string $tx_telefono
 * @property string $tx_email
 * @property string $tx_password
 * @property string $tx_direccion1
 * @property string $tx_direccion2
 * @property int|null $co_usuario_padre
 * @property int|null $co_usuario_reclutador
 * @property int $co_estatus_usuario
 * @property Carbon $fe_fecha_creacion
 * @property Carbon|null $fe_ultima_mofidicacion
 * @property int $co_tipo_usuario
 * @property string $co_zip
 * 
 * @property I014tEstatusUsuario $i014t_estatus_usuario
 * @property C013tTipoUsuario $c013t_tipo_usuario
 * @property Collection|C001tAplicacione[] $c001t_aplicaciones
 * @property Collection|C010tOrdene[] $c010t_ordenes
 * @property Collection|C012tUsuariosOficina[] $c012t_usuarios_oficinas
 * @property Collection|C014tUsuariosRole[] $c014t_usuarios_roles
 * @property Collection|C016tMeta[] $c016t_metas
 *
 * @package App\Models
 */
class C002tUsuario extends Model
{
	protected $table = 'c002t_usuarios';
	protected $primaryKey = 'co_usuario';
	public $timestamps = false;

	protected $casts = [
		'co_usuario_padre' => 'int',
		'co_usuario_reclutador' => 'int',
		'co_estatus_usuario' => 'int',
		'fe_fecha_creacion' => 'datetime',
		'fe_ultima_mofidicacion' => 'datetime',
		'co_tipo_usuario' => 'int'
	];

	protected $hidden = [
		'tx_password'
	];

	protected $fillable = [
		'tx_primer_nombre',
		'tx_segundo_nombre',
		'tx_primer_apellido',
		'tx_segundo_apellido',
		'tx_telefono',
		'tx_email',
		'tx_password',
		'tx_direccion1',
		'tx_direccion2',
		'co_usuario_padre',
		'co_usuario_reclutador',
		'co_estatus_usuario',
		'fe_fecha_creacion',
		'fe_ultima_mofidicacion',
		'co_tipo_usuario',
		'co_zip'
	];

	public function i014t_estatus_usuario()
	{
		return $this->belongsTo(I014tEstatusUsuario::class, 'co_estatus_usuario');
	}

	public function c013t_tipo_usuario()
	{
		return $this->belongsTo(C013tTipoUsuario::class, 'co_tipo_usuario');
	}

	public function c001t_aplicaciones()
	{
		return $this->hasMany(C001tAplicacione::class, 'co_usuario');
	}

	public function c010t_ordenes()
	{
		return $this->hasMany(C010tOrdene::class, 'co_plomero');
	}

	public function c012t_usuarios_oficinas()
	{
		return $this->hasMany(C012tUsuariosOficina::class, 'co_usuario');
	}

	public function c014t_usuarios_roles()
	{
		return $this->hasMany(C014tUsuariosRole::class, 'co_usuario');
	}

	public function c016t_metas()
	{
		return $this->hasMany(C016tMeta::class, 'co_usuario');
	}
}
