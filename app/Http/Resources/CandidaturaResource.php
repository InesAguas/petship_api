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


     /**
 * @OA\Schema(
 *    schema="Candidatura",
 *    @OA\Property(
 *        property="id",
 *        type="integer",
 *        description="ID da candidatura",
 *        nullable=false,
 *        example="1"
 *    ),
 *    @OA\Property(
 *        property="id_anuncio",
 *       type="integer",
 *     description="ID do anuncio",
 *   nullable=false,
 * example="1"
 * ),
 * @OA\Property(
 *   property="id_utilizador",
 * type="integer",
 * description="ID do utilizador que fez a candidatura",
 * nullable=false,
 * example="1"
 * ),
 * @OA\Property(
 * property="cc",
 * type="integer",
 * description="Cartão de cidadão do utilizador que fez a candidatura",
 * nullable=false,
 * example="12345678"
 * ),
 * @OA\Property(
 * property="estado",
 * type="boolean",
 * description="Estado da candidatura",
 * nullable=false,
 * example="1"
 * ),
 * )
 * 
 *  @return array
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
