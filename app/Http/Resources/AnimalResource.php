<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Traducao;
use App\Models\Anuncio;
use Illuminate\Support\Facades\App;

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
        $lang = App::getLocale();

        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'sexo' => Traducao::where('id', $this->sexo)->where('tipo', 'sexo')->first()->$lang,
            'especie' => Traducao::where('id', $this->especie)->where('tipo', 'especie')->first()->$lang,
            'raca' => Traducao::where('id', $this->raca)->where('tipo', $this->especie == 1 ? 'raca_caes':'raca_gatos')->first()->$lang,
            'porte' => Traducao::where('id', $this->porte)->where('tipo', 'porte')->first()->$lang,
            'idade' => Traducao::where('id', $this->idade)->where('tipo', 'idade')->first()->$lang,
            'cor' => Traducao::where('id', $this->cor)->where('tipo', 'cor')->first()->$lang,
            'ferido' => $this->ferido,
            'agressivo' => $this->agressivo,
            'data_recolha' => $this->data_recolha,
            'local_captura' => $this->local_captura,
            'chip' => $this->chip,
            'temperatura' => $this->temperatura,
            'desparasitacao' => $this->desparasitacao,
            'medicacao' => $this->medicacao,
            'fotografia' => $this->fotografia ? asset('storage/img/animais/'.$this->fotografia) : null,
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
            'chip' => $this->chip,
            'temperatura' => $this->temperatura,
            'desparasitacao' => $this->desparasitacao,
            'medicacao' => $this->medicacao,
            'fotografia' =>  $this->fotografia ? asset('storage/img/animais/'.$this->fotografia) : null,
            'anunciado' => Anuncio::where('id_animal', $this->id)->exists() ? 1 : 0,
        ];
    }
}
