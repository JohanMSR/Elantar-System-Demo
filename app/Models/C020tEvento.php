<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class C020tEvento
 * 
 * @property int $co_evento
 * @property string $tx_titulo
 * @property string $tx_descripcion
 * @property Carbon $fe_evento
 * @property Carbon $fe_registro
 * @property string $tx_agregado_por
 * 
 * @property Collection|C021tFotosEvento[] $c021t_fotos_eventos
 *
 * @package App\Models
 */
class C020tEvento extends Model
{
	protected $table = 'c020t_eventos';
	protected $primaryKey = 'co_evento';
	public $timestamps = false;

	protected $casts = [
		'fe_evento' => 'datetime',
		'fe_registro' => 'datetime'
	];

	protected $fillable = [
		'tx_titulo',
		'tx_descripcion',
		'fe_evento',
		'fe_registro',
		'tx_agregado_por'
	];

	public function c021t_fotos_eventos()
	{
		return $this->hasMany(C021tFotosEvento::class, 'co_evento');
	}
}
