<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Stock;

class StockResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {   
        $stock = Stock::where('id', $this->id_stock)->first();

        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'descricao' => $this->descricao,
            'qnt_atual' => $this->qnt_atual,
            'qnt_min' => $this->qnt_min,
            'observacoes' => $this->observacoes,

    
        ];
      
    }
}
