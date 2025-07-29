<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Usersreg
 * 
 * @property int $co_reg
 * @property int $id
 * @property Carbon $fe_registro
 * 
 * @property User $user
 *
 * @package App\Models
 */
class Usersreg extends Model
{
	protected $table = 'usersreg';
	protected $primaryKey = 'co_reg';
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'fe_registro' => 'datetime'
	];

	protected $fillable = [
		'id',
		'fe_registro'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'id');
	}
}
