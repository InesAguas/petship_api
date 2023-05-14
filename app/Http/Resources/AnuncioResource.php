<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Animal;
use App\Models\Traducao;

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

        return [
            'id' => $this->id,
            'nome' => $animal->nome,
            'sexo' => Traducao::where('id', $animal->sexo)->where('tipo', 'sexo')->first()->pt,
            'especie' => Traducao::where('id', $animal->especie)->where('tipo', 'especie')->first()->pt,
            'raca' => Traducao::where('id', $animal->raca)->where('tipo', $this->especie == 1 ? 'raca_caes':'raca_gatos')->first()->pt,
            'porte' => Traducao::where('id', $animal->porte)->where('tipo', 'porte')->first()->pt,
            'idade' => Traducao::where('id', $animal->idade)->where('tipo', 'idade')->first()->pt,
            'cor' => Traducao::where('id', $animal->cor)->where('tipo', 'cor')->first()->pt,
            'distrito' => $this->distrito,
            'etiqueta' => Traducao::where('id', $this->etiqueta)->where('tipo', 'etiqueta')->first()->pt,
            'descricao' => $this->descricao,
            'fotografias' => $this->fotografiasUrls(),
            'created_at' => $this->created_at->format('d-m-Y'),
        ];
    }

    public function toArrayEnglish($request)
    {
        $animal = Animal::where('id', $this->id_animal)->first();

        return [
            'id' => $this->id,
            'nome' => $animal->nome,
            'sexo' =>Traducao::where('id', $animal->sexo)->where('tipo', 'sexo')->first()->en,
            'especie' => Traducao::where('id', $animal->especie)->where('tipo', 'especie')->first()->en,
            'raca' =>Traducao::where('id', $animal->raca)->where('tipo', $this->especie == 1 ? 'raca_caes':'raca_gatos')->first()->en,
            'porte' => Traducao::where('id', $animal->porte)->where('tipo', 'porte')->first()->en,
            'idade' => Traducao::where('id', $animal->idade)->where('tipo', 'idade')->first()->en,
            'cor' => Traducao::where('id', $animal->cor)->where('tipo', 'cor')->first()->en,
            'distrito' => $this->distrito,
            'etiqueta' => Traducao::where('id', $this->etiqueta)->where('tipo', 'etiqueta')->first()->en,
            'descricao' => $this->descricao,
            'fotografias' => $this->fotografiasUrls(),
            'created_at' => $this->created_at->format('d-m-Y'),
        ];
    }
}
