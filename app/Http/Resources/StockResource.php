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

    /**
     * @OA\Schema(
     *    schema="Stock",
     *    @OA\Property(
     *        property="id",
     *        type="integer",
     *        description="ID do stock",
     *        nullable=false,
     *        example="1"
     *    ),
     *    @OA\Property(
     *        property="nome",
     *        type="string",
     *        description="Nome do produto em stock",
     *        nullable=false,
     *        example="Ração"
     *    ),
     *   @OA\Property(
     *      property="descricao",
     *    type="string",
     * description="Descrição do produto em stock",
     * nullable=true,
     * example="Ração para cão"
     * ),
     * @OA\Property(
     *   property="qnt_atual",
     * type="integer",
     * description="Quantidade atual do produto em stock",
     * nullable=false,
     * example="10"
     * ),
     * @OA\Property(
     * property="qnt_min",
     * type="integer",
     * description="Quantidade mínima do produto em stock",
     * nullable=false,
     * example="5"
     * ),
     * @OA\Property(
     * property="observacoes",
     * type="string",
     * description="Observações do produto em stock",
     * nullable=true,
     * example="Ração para cão de porte médio"
     * ),
     * )
     * 
     *  @return array
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
