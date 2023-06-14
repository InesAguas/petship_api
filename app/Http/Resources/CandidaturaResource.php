<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Candidatura;

class CandidaturaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $candidatura = Candidatura::where('id', $this->id_candidatura)->first();

        return[
            'id' => $this->id,
            'id_anuncio' => $this->id_anuncio,
            'id_utilizador' => $this->id_utilizador,
            'cc' => $this->cc,
            'estado' => $this->estado
        ];
    }
}
