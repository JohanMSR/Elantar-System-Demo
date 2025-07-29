<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class C025tBlog
 * 
 * @property int $co_blog
 * @property string|null $tx_titulo
 * @property string|null $tx_descripcion
 * @property Carbon|null $fe_registro
 * @property string|null $tx_agregado_por
 *
 * @package App\Models
 */
class C025tBlog extends Model
{
	protected $table = 'c025t_blog';
	protected $primaryKey = 'co_blog';
	public $timestamps = false;

	protected $casts = [
		'fe_registro' => 'datetime'
	];

	protected $fillable = [
		'tx_titulo',
		'tx_descripcion',
		'fe_registro',
		'tx_agregado_por'
	];
}
