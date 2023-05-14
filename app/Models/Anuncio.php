<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anuncio extends Model
{
    use HasFactory;
    protected $table = 'anuncios';

    protected $fillable = [
        'distrito',
        'etiqueta',
        'descricao',
        'fotografias'
    ];

        protected $casts = [
            'fotografias' => 'array'
        ];
    
        public function fotografiasUrls()
        {
            $fotos = [];
            if($this->fotografias != null) {
                $this->fotografias = json_decode($this->fotografias);
                for ($i = 0; $i < count($this->fotografias); $i++) {
                    $fotos[] = asset('storage/img/animais/' . $this->fotografias[$i]);
                }
            }
            return $fotos;
        }
}
