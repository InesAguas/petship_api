<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;

use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Auth\Passwords\CanResetPassword as PasswordsCanResetPassword;


class Utilizador extends Authenticatable implements CanResetPassword, MustVerifyEmail
{
    use HasFactory, HasApiTokens, Notifiable, PasswordsCanResetPassword;
    protected $table = 'utilizadores';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'email',
        'password',
        'tipo',
        'localizacao',
        'distrito',
        'codigo_postal',
        'telefone',
        'fotografia',
        'website',
        'facebook',
        'instagram',
        'horario',
    ];

    protected $hidden = [
        'password',
    ];

    public function fotografiaUrl()
    {
        if($this->fotografia != null) {
            $this->fotografia = asset('storage/img/utilizadores/' . $this->fotografia);
        }
        return $this->fotografia;
    }

    public function mensagensEnviadas()
    {
        return $this->hasMany(Mensagem::class, 'id_envia');
    }

    public function mensagensRecebidas() 
    {
        return $this->hasMany(Mensagem::class, 'id_recebe');
    }

    public function isParticular() 
    {
        return $this->tipo == 1;
    }

    public function isAssociacao() 
    {
        return $this->tipo == 2;
    }

    public function getHorario() {
        return json_decode($this->horario);
    }

}
