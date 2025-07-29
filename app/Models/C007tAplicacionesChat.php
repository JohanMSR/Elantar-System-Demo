<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class C007tAplicacionesChat
 * 
 * @property int $co_chat_apl
 * @property int $co_aplicacion
 * @property int $co_usuario
 * @property string $tx_comentario
 * @property Carbon $fe_comentario
 * @property time without time zone $ho_comentario
 * 
 * @property C001tAplicacione $c001t_aplicacione
 *
 * @package App\Models
 */
class C007tAplicacionesChat extends Model
{
	protected $table = 'c007t_aplicaciones_chats';
	public $incrementing = false;
	public $timestamps = false;
	protected $primaryKey = 'co_chat_apl';

	protected $casts = [
		'co_chat_apl' => 'int',
		'co_aplicacion' => 'int',
		'co_usuario' => 'int',
		'fe_comentario' => 'datetime',
		'ho_comentario' => 'datetime'
	];

	protected $fillable = [
		'co_chat_apl' => 'int',
		'co_aplicacion' => 'int',
		'co_usuario' => 'int',
		'tx_comentario' => 'string',
		'fe_comentario' => 'datetime',
		'ho_comentario' => 'datetime'
	];

	public function c001t_aplicacione()
	{
		return $this->belongsTo(C001tAplicacione::class, 'co_aplicacion');
	}
	public function user()
	{
		return $this->belongsTo(User::class, 'co_usuario', 'id');
	}

	public function usuarioChatAplicacion()
	{
		return $this->belongsTo(C033tUsuariosChatAplicacione::class, 'co_usuario', 'co_usuario');
	}
	// ... existing code ...

	public function repliedMessage()
	{
    	return $this->belongsTo(C007tAplicacionesChat::class, 'reply_to', 'co_chat_apl');
	}
}
