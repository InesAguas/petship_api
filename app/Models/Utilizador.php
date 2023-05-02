<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Utilizador extends Model
{
    use HasFactory, HasApiTokens;
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
        'telefone',
        'fotografia',
        'website',
        'facebook',
        'instagram',
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

}
