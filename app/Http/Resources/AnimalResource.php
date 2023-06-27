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

    /**
 * @OA\Schema(
 *    schema="Animal",
 *    @OA\Property(
 *        property="id",
 *        type="integer",
 *        description="ID do animal",
 *        nullable=false,
 *        example="1"
 *    ),
 *    @OA\Property(
 *        property="nome",
 *        type="string",
 *        description="Nome do animal",
 *        nullable=false,
 *        example="Rufus"
 *    ),
 *   @OA\Property(
 *      property="sexo",
 *      type="string",
 *     description="Sexo do animal",
 *   nullable=false,
 * example="Macho"
 * ),
 * @OA\Property(
 *  property="especie",
 * type="string",
 * description="Especie do animal",
 * nullable=false,
 * example="Cão"
 * ),
 * @OA\Property(
 * property="raca",
 * type="string",
 * description="Raça do animal",
 * nullable=false,
 * example="Labrador"
 * ),
 * @OA\Property(
 * property="porte",
 * type="string",
 * description="Porte do animal",
 * nullable=false,
 * example="Médio"
 * ),
 * @OA\Property(
 * property="idade",
 * type="string",
 * description="Idade do animal",
 * nullable=false,
 * example="Adulto"
 * ),
 * @OA\Property(
 * property="cor",
 * type="string",
 * description="Cor do animal",
 * nullable=false,
 * example="Preto"
 * ),
 * @OA\Property(
 * property="ferido",
 * type="boolean",
 * description="Se o animal está ferido",
 * nullable=false,
 * example="true"
 * ),
 * @OA\Property(
 * property="agressivo",
 * type="boolean",
 * description="Se o animal é agressivo",
 * nullable=false,
 * example="false"
 * ),
 * @OA\Property(
 * property="data_recolha",
 * type="string",
 * description="Data de recolha do animal",
 * nullable=false,
 * format="date-time",
 * ),
 * @OA\Property(
 * property="local_captura",
 * type="string",
 * description="Local de captura do animal",
 * nullable=false,
 * example="Rua da Liberdade, 4000-001 Porto"
 * ),
 * @OA\Property(
 * property="chip",
 * type="integer",
 * description="Número do chip do animal",
 * nullable=true,
 * example="123456789012345"
 * ),
 * @OA\Property(
 * property="temperatura",
 * type="string",
 * description="Temperatura rectal do animal",
 * nullable=true,
 * example="38.5"
 * ),
 * @OA\Property(
 * property="desparasitacao",
 * type="string",
 * description="Data da última desparasitação do animal",
 * nullable=true,
 * format="date-time",
 * ),
 * @OA\Property(
 * property="medicacao",
 * type="string",
 * description="Medicação que o animal está a tomar",
 * nullable=true,
 * example="Bravecto 3x por dia"
 * ),
 * @OA\Property(
 * property="fotografia",
 * type="string",
 * description="Fotografia do animal",
 * nullable=true,
 * example="https://api.petship.pt/storage/img/animais/1.jpg"
 * ),
 * @OA\Property(
 * property="anunciado",
 * type="boolean",
 * description="Se o animal está anunciado",
 * nullable=false,
 * example="true"
 * ),
 * )
 * 
 *  @return array
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



        /**
 * @OA\Schema(
 *    schema="AnimalNum",
 *    @OA\Property(
 *        property="id",
 *        type="integer",
 *        description="ID do animal",
 *        nullable=false,
 *        example="1"
 *    ),
 *    @OA\Property(
 *        property="nome",
 *        type="string",
 *        description="Nome do animal",
 *        nullable=false,
 *        example="Rufus"
 *    ),
 *   @OA\Property(
 *      property="sexo",
 *      type="integer",
 *     description="Sexo do animal",
 *   nullable=false,
 * example="1"
 * ),
 * @OA\Property(
 *  property="especie",
 * type="integer",
 * description="Especie do animal",
 * nullable=false,
 * example="1"
 * ),
 * @OA\Property(
 * property="raca",
 * type="integer",
 * description="Raça do animal",
 * nullable=false,
 * example="1"
 * ),
 * @OA\Property(
 * property="porte",
 * type="integer",
 * description="Porte do animal",
 * nullable=false,
 * example="1"
 * ),
 * @OA\Property(
 * property="idade",
 * type="integer",
 * description="Idade do animal",
 * nullable=false,
 * example="1"
 * ),
 * @OA\Property(
 * property="cor",
 * type="integer",
 * description="Cor do animal",
 * nullable=false,
 * example="1"
 * ),
 * @OA\Property(
 * property="ferido",
 * type="boolean",
 * description="Se o animal está ferido",
 * nullable=false,
 * example="true"
 * ),
 * @OA\Property(
 * property="agressivo",
 * type="boolean",
 * description="Se o animal é agressivo",
 * nullable=false,
 * example="false"
 * ),
 * @OA\Property(
 * property="data_recolha",
 * type="string",
 * description="Data de recolha do animal",
 * nullable=false,
 * format="date-time",
 * ),
 * @OA\Property(
 * property="local_captura",
 * type="string",
 * description="Local de captura do animal",
 * nullable=false,
 * example="Rua da Liberdade, 4000-001 Porto"
 * ),
 * @OA\Property(
 * property="chip",
 * type="integer",
 * description="Número do chip do animal",
 * nullable=true,
 * example="123456789012345"
 * ),
 * @OA\Property(
 * property="temperatura",
 * type="string",
 * description="Temperatura rectal do animal",
 * nullable=true,
 * example="38.5"
 * ),
 * @OA\Property(
 * property="desparasitacao",
 * type="string",
 * description="Data da última desparasitação do animal",
 * nullable=true,
 * format="date-time",
 * ),
 * @OA\Property(
 * property="medicacao",
 * type="string",
 * description="Medicação que o animal está a tomar",
 * nullable=true,
 * example="Bravecto 3x por dia"
 * ),
 * @OA\Property(
 * property="fotografia",
 * type="string",
 * description="Fotografia do animal",
 * nullable=true,
 * example="https://api.petship.pt/storage/img/animais/1.jpg"
 * ),
 * @OA\Property(
 * property="anunciado",
 * type="boolean",
 * description="Se o animal está anunciado",
 * nullable=false,
 * example="true"
 * ),
 * )
 * 
 *  @return array
 */
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
