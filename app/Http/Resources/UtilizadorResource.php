<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OAS\Schema(type="object")
 */
class UtilizadorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

     /**
 * @OA\Schema(
 *    schema="Utilizador",
 *    @OA\Property(
 *        property="id",
 *        type="integer",
 *        description="User ID",
 *        nullable=false,
 *        example="1"
 *    ),
 *    @OA\Property(
 *        property="nome",
 *        type="string",
 *        description="Nome do utilizador/associacao",
 *        nullable=false,
 *        example="Canil Municipal de Lisboa"
 *    ),
 *    @OA\Property(
 *        property="email",
 *        type="string",
 *        description="Email do utilizador/associacao",
 *        nullable=false,
 *        format="email"
 *    ),
 *   @OA\Property(
 *       property="tipo",
 *      type="integer",
 *     description="Tipo de utilizador",
 *    nullable=false,
 *  example="2"
 * ),
 * @OA\Property(
 *    property="localizacao",
 *  type="string",
 * description="Localizacao do utilizador/associacao",
 * nullable=true,
 * example="Lisboa"
 * ),
 * @OA\Property(
 *   property="distrito",
 * type="string",
 * description="Distrito do utilizador/associacao",
 * nullable=true,
 * example="Lisboa"
 * ),
 * @OA\Property(
 *  property="codigo_postal",
 * type="string",
 * description="Codigo postal do utilizador/associacao",
 * nullable=true,
 * example="1000-001"
 * ),
 * @OA\Property(
 * property="telefone",
 * type="string",
 * description="Telefone do utilizador/associacao",
 * nullable=true,
 * example="912345678"
 * ),
 * @OA\Property(
 * property="fotografia",
 * type="string",
 * description="Fotografia do utilizador/associacao",
 * nullable=true,
 * example="https://api.petship.pt/storage/img/utilizadores/1.jpg"
 * ),
 * @OA\Property(
 * property="website",
 * type="string",
 * description="Website do utilizador/associacao",
 * nullable=true,
 * example="https://www.user.pt"
 * ),
 * @OA\Property(
 * property="facebook",
 * type="string",
 * description="Facebook do utilizador/associacao",
 * nullable=true,
 * example="https://www.facebook.com/user"
 * ),
 * @OA\Property(
 * property="instagram",
 * type="string",
 * description="Instagram do utilizador/associacao",
 * nullable=true,
 * example="https://www.instagram.com/user"
 * ),
 * @OA\Property(
 * property="horario",
 * type="string",
 * description="Horario do utilizador/associacao",
 * nullable=true,
 * example=""
 * ),
 * @OA\Property(
 * property="iban",
 * type="string",
 * description="IBAN do utilizador/associacao",
 * nullable=true,
 * example="PT50000000000000000000000"
 * ),
 * )
 * 
 *  @return array
 */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'email' => $this->email,
            'tipo' => $this->tipo,
            'localizacao' => $this->localizacao,
            'distrito' => $this->distrito,
            'codigo_postal' => $this->codigo_postal,
            'telefone' => $this->telefone,
            'fotografia' => $this->fotografiaUrl(),
            'website' => $this->website,
            'facebook' => $this->facebook,
            'instagram' => $this->instagram,
            'horario' => $this->getHorario(),
            'iban' => $this->iban,

        ];
    }
}
