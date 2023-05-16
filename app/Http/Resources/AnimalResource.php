<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Traducao;
use App\Models\Anuncio;

class AnimalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'sexo' => Traducao::where('id', $this->sexo)->where('tipo', 'sexo')->first()->pt,
            'especie' => Traducao::where('id', $this->especie)->where('tipo', 'especie')->first()->pt,
            'raca' => Traducao::where('id', $this->raca)->where('tipo', $this->especie == 1 ? 'raca_caes':'raca_gatos')->first()->pt,
            'porte' => Traducao::where('id', $this->porte)->where('tipo', 'porte')->first()->pt,
            'idade' => Traducao::where('id', $this->idade)->where('tipo', 'idade')->first()->pt,
            'cor' => Traducao::where('id', $this->cor)->where('tipo', 'cor')->first()->pt,
            'ferido' => $this->ferido,
            'agressivo' => $this->agressivo,
            'data_recolha' => $this->data_recolha,
            'local_captura' => $this->local_captura,
            'fotografia' => $this->fotografia ? asset('storage/img/animais/'.$this->fotografia) : null,
            'anunciado' => Anuncio::where('id_animal', $this->id)->exists(),
        ];
    }

    public function toArrayEnglish($request)
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'sexo' => Traducao::where('id', $this->sexo)->where('tipo', 'sexo')->first()->en,
            'especie' => Traducao::where('id', $this->especie)->where('tipo', 'especie')->first()->en,
            'raca' => Traducao::where('id', $this->raca)->where('tipo', $this->especie == 1 ? 'raca_caes':'raca_gatos')->first()->en,
            'porte' => Traducao::where('id', $this->porte)->where('tipo', 'porte')->first()->en,
            'idade' => Traducao::where('id', $this->idade)->where('tipo', 'idade')->first()->en,
            'cor' => Traducao::where('id', $this->cor)->where('tipo', 'cor')->first()->en,
            'ferido' => $this->ferido,
            'agressivo' => $this->agressivo,
            'data_recolha' => $this->data_recolha,
            'local_captura' => $this->local_captura,
            'fotografia' =>  $this->fotografia ? asset('storage/img/animais/'.$this->fotografia) : null,
            'anunciado' => Anuncio::where('id_animal', $this->id)->exists(),
        ];
    }

    public function toArrayNumeric() {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'sexo' => $this->sexo,
            'especie' => $this->especie,
            'raca' => $this->raca,
            'porte' => $this->porte,
            'idade' => $this->idade,
            'cor' => $this->cor,
            'ferido' => $this->ferido ? 1 : 0,
            'agressivo' => $this->agressivo ? 1 : 0,
            'data_recolha' => $this->data_recolha,
            'local_captura' => $this->local_captura,
            'fotografia' =>  $this->fotografia ? asset('storage/img/animais/'.$this->fotografia) : null,
            'anunciado' => Anuncio::where('id_animal', $this->id)->exists() ? 1 : 0,
        ];
    }
}
