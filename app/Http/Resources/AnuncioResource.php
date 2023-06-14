<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Animal;
use App\Models\Traducao;
use Illuminate\Support\Facades\App;

class AnuncioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $animal = Animal::where('id', $this->id_animal)->first();
        $lang = App::getLocale();

        return [
            'id' => $this->id,
            'nome' => $animal->nome,
            'sexo' => Traducao::where('id', $animal->sexo)->where('tipo', 'sexo')->first()->$lang,
            'especie' => Traducao::where('id', $animal->especie)->where('tipo', 'especie')->first()->$lang,
            'raca' => Traducao::where('id', $animal->raca)->where('tipo', $animal->especie == 1 ? 'raca_caes':'raca_gatos')->first()->$lang,
            'porte' => Traducao::where('id', $animal->porte)->where('tipo', 'porte')->first()->$lang,
            'idade' => Traducao::where('id', $animal->idade)->where('tipo', 'idade')->first()->$lang,
            'cor' => Traducao::where('id', $animal->cor)->where('tipo', 'cor')->first()->$lang,
            'distrito' => $this->distrito,
            'etiqueta' => Traducao::where('id', $this->etiqueta)->where('tipo', 'etiqueta')->first()->$lang,
            'descricao' => $this->descricao,
            'estado' => $this->estado ? ($lang == 'pt' ? 'Ativo' : 'Active') : ($lang == 'pt' ? 'Inativo' : 'Inactive'),
            'fotografias' => $this->fotografiasUrls(),
            'created_at' => $this->created_at->format('d-m-Y'),
        ];
    }

    public function toArrayNumeric() {
        $animal = Animal::where('id', $this->id_animal)->first();

        return [
            'id' => $this->id,
            'nome' => $animal->nome,
            'sexo' =>$animal->sexo,
            'especie' => $animal->especie,
            'raca' => $animal->raca,
            'porte' => $animal->porte,
            'idade' => $animal->idade,
            'cor' => $animal->cor,
            'distrito' => $this->distrito,
            'etiqueta' => $this->etiqueta,
            'descricao' => $this->descricao,
            'estado' => $this->estado,
            'fotografias' => $this->fotografiasUrls(),
            'created_at' => $this->created_at->format('d-m-Y'),
        ];
    }
}
