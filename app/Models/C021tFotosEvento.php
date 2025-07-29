<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class C021tFotosEvento
 * 
 * @property int $co_foto
 * @property int $co_evento
 * @property bytea $im_foto
 * 
 * @property C020tEvento $c020t_evento
 *
 * @package App\Models
 */
class C021tFotosEvento extends Model
{
	protected $table = 'c021t_fotos_evento';
	public $timestamps = false;

	protected $casts = [
		'co_evento' => 'int',
		'im_foto' => 'bytea'
	];

	protected $fillable = [
		'im_foto'
	];

	public function c020t_evento()
	{
		return $this->belongsTo(C020tEvento::class, 'co_evento');
	}
}
