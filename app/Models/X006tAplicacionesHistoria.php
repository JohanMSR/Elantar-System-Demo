<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class X006tAplicacionesHistoria
 * 
 * @property int $id
 * @property string $tx_descripcion
 *
 * @package App\Models
 */
class X006tAplicacionesHistoria extends Model
{
	protected $table = 'x006t_aplicaciones_historias';
	public $timestamps = false;

	protected $fillable = [
		'tx_descripcion'
	];
}
