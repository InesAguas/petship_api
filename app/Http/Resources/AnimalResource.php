<?php

namespace App\Http\Resources;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'sexo' => DB::table('traducoes')->where('id', $this->sexo)->where('tipo', 'sexo')->first()->pt,
            'especie' => DB::table('traducoes')->where('id', $this->especie)->where('tipo', 'especie')->first()->pt,
            'raca' => DB::table('traducoes')->where('id', $this->raca)->where('tipo', $this->especia == 1 ? 'raca_caes':'raca_gatos')->first()->pt,
            'porte' => DB::table('traducoes')->where('id', $this->porte)->where('tipo', 'porte')->first()->pt,
            'idade' => DB::table('traducoes')->where('id', $this->idade)->where('tipo', 'idade')->first()->pt,
            'cor' => DB::table('traducoes')->where('id', $this->cor)->where('tipo', 'cor')->first()->pt,
            'distrito' => $this->distrito,
            'etiqueta' => DB::table('traducoes')->where('id', $this->etiqueta)->where('tipo', 'etiqueta')->first()->pt,
            'descricao' => $this->descricao,
            'fotografias' => $this->fotografiasUrls(),
            'created_at' => $this->created_at->format('d-m-Y'),
        ];

    }


    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArrayEnglish()
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'sexo' => DB::table('traducoes')->where('id', $this->sexo)->where('tipo', 'sexo')->first()->en,
            'especie' => DB::table('traducoes')->where('id', $this->especie)->where('tipo', 'especie')->first()->en,
            'raca' => DB::table('traducoes')->where('id', $this->raca)->where('tipo', $this->especia == 1 ? 'raca_caes':'raca_gatos')->first()->en,
            'porte' => DB::table('traducoes')->where('id', $this->porte)->where('tipo', 'porte')->first()->en,
            'idade' => DB::table('traducoes')->where('id', $this->idade)->where('tipo', 'idade')->first()->en,
            'cor' => DB::table('traducoes')->where('id', $this->cor)->where('tipo', 'cor')->first()->en,
            'distrito' => $this->distrito,
            'etiqueta' => DB::table('traducoes')->where('id', $this->etiqueta)->where('tipo', 'etiqueta')->first()->en,
            'descricao' => $this->descricao,
            'fotografias' => $this->fotografiasUrls(),
            'created_at' => $this->created_at->format('d-m-Y'),
        ];

    }
}
