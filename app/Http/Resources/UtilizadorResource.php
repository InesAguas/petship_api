<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UtilizadorResource extends JsonResource
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
            'email' => $this->email,
            'tipo' => $this->tipo,
            'localizacao' => $this->localizacao,
            'telefone' => $this->telefone,
            'fotografia' => $this->fotografiaUrl(),
            'website' => $this->website,
            'facebook' => $this->facebook,
            'instagram' => $this->instagram,
            'horario' => $this->getHorario(),

        ];
    }
}
