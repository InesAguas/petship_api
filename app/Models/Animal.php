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
        'distrito',
        'etiqueta',
        'descricao',
        'fotografias'
    ];

    protected $casts = [
        'fotografias' => 'array'
    ];

}
