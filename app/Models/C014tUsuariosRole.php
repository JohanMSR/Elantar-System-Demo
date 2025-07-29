<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class C014tUsuariosRole
 * 
 * @property int $co_usuario
 * @property int $co_rol
 * @property Carbon $fe_vigencia
 * 
 * @property C002tUsuario $c002t_usuario
 * @property I007tRole $i007t_role
 *
 * @package App\Models
 */
class C014tUsuariosRole extends Model
{
	protected $table = 'c014t_usuarios_roles';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'co_usuario' => 'int',
		'co_rol' => 'int',
		'fe_vigencia' => 'datetime'
	];

	protected $fillable = [
		'fe_vigencia'
	];

	public function c002t_usuario()
	{
		return $this->belongsTo(C002tUsuario::class, 'co_usuario');
	}

	public function i007t_role()
	{
		return $this->belongsTo(I007tRole::class, 'co_rol');
	}
}
