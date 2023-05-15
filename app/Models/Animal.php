<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    use HasFactory;
    protected $table = 'animais';

    protected $fillable = [
        'nome',
        'sexo',
        'especie',
        'raca',
        'porte',
        'idade',
        'cor',
        'ferido',
        'agressivo',
        'data_recolha',
        'local_captura',
        'fotografia'
    ];

}
