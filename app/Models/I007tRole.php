<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class I007tRole
 * 
 * @property int $co_rol
 * @property string $tx_nombre
 * @property string|null $tx_descripcion
 * @property bool|null $in_metas
 * 
 * @property Collection|C014tUsuariosRole[] $c014t_usuarios_roles
 *
 * @package App\Models
 */
class I007tRole extends Model
{
	protected $table = 'i007t_roles';
	protected $primaryKey = 'co_rol';
	public $timestamps = false;

	protected $casts = [
		'in_metas' => 'bool'
	];

	protected $fillable = [
		'tx_nombre',
		'tx_descripcion',
		'in_metas'
	];

	public function c014t_usuarios_roles()
	{
		return $this->hasMany(C014tUsuariosRole::class, 'co_rol');
	}
}
