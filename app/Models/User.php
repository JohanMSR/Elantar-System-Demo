<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'image_path',
        'lenguaje_principal',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function usuarioEstado()
    {
        return $this->hasOne(C002tUsuario::class, 'co_usuario', 'id');
    }

    public function tipoUsuario()
    {
        return $this->hasOneThrough(C013tTipoUsuario::class, C002tUsuario::class, 'co_usuario', 'co_tipo_usuario', 'id', 'co_tipo_usuario');
    }


    public function isActive()
    {
        return $this->usuarioEstado->co_estatus_usuario === 1;
    }

    public function getCoTipoUsuarioAttribute()
    {
        return $this->tipoUsuario->co_tipo_usuario;
    }


    public function getTxTipoUsuarioAttribute()
    {
       return $this->tipoUsuario->tx_tipo_usuario;
    }
}
