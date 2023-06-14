<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidatura extends Model
{
    use HasFactory;
    protected $table = 'candidaturas';

    protected $fillable = [
        'id_anuncio',
        'id_utilizador',
        'cc',
        'estado'
    ];

    
}
