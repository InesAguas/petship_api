<?php

namespace App\Http\Resources;

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
            'sexo' => $this->sexo,
            'especie' => $this->especie,
            'raca' => $this->raca,
            'porte' => $this->porte,
            'idade' => $this->idade,
            'cor' => $this->cor,
            'distrito' => $this->distrito,
            'etiqueta' => $this->etiqueta,
            'descricao' => $this->descricao,
            'fotografias' => $this->fotografiasUrls(),
            'created_at' => $this->created_at->format('d-m-Y'),
        ];

    }
}
