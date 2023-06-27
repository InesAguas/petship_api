<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Animal;
use App\Models\Traducao;
use Illuminate\Support\Facades\App;

class AnuncioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

     /**
 * @OA\Schema(
 *    schema="Anuncio",
 *    @OA\Property(
 *        property="id",
 *        type="integer",
 *        description="ID do anuncio",
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
 * property="distrito",
 * type="string",
 * description="Distrito do animal",
 * nullable=false,
 * example="Porto"
 * ),
 * @OA\Property(
 * property="etiqueta",
 * type="string",
 * description="Etiqueta do animal",
 * nullable=false,
 * example="Adoção"
 * ),
 * @OA\Property(
 * property="descricao",
 * type="string",
 * description="Descrição do animal",
 * nullable=true,
 * example="O Rufus é um cão muito meigo e brincalhão"
 * ),
 * @OA\Property(
 * property="estado",
 * type="string",
 * description="Estado do anuncio",
 * nullable=false,
 * example="Ativo"
 * ),
 * @OA\Property(
 * property="fotografias",
 * type="array",
 * description="Fotografias do animal",
 * nullable=true,
 * @OA\Items(
 *     type="string",
 *    example="https://i.imgur.com/3bY1f9R.jpg"
 * )
 * ),
 * @OA\Property(
 * property="created_at",
 * type="string",
 * description="Data de criação do anuncio",
 * nullable=false,
 * format="date-time"
 * ),
 * )
 * 
 *  @return array
 */
    public function toArray($request)
    {
        $animal = Animal::where('id', $this->id_animal)->first();
        $lang = App::getLocale();

        return [
            'id' => $this->id,
            'nome' => $animal->nome,
            'sexo' => Traducao::where('id', $animal->sexo)->where('tipo', 'sexo')->first()->$lang,
            'especie' => Traducao::where('id', $animal->especie)->where('tipo', 'especie')->first()->$lang,
            'raca' => Traducao::where('id', $animal->raca)->where('tipo', $animal->especie == 1 ? 'raca_caes':'raca_gatos')->first()->$lang,
            'porte' => Traducao::where('id', $animal->porte)->where('tipo', 'porte')->first()->$lang,
            'idade' => Traducao::where('id', $animal->idade)->where('tipo', 'idade')->first()->$lang,
            'cor' => Traducao::where('id', $animal->cor)->where('tipo', 'cor')->first()->$lang,
            'distrito' => $this->distrito,
            'etiqueta' => Traducao::where('id', $this->etiqueta)->where('tipo', 'etiqueta')->first()->$lang,
            'descricao' => $this->descricao,
            'estado' => $this->estado ? ($lang == 'pt' ? 'Ativo' : 'Active') : ($lang == 'pt' ? 'Inativo' : 'Inactive'),
            'fotografias' => $this->fotografiasUrls(),
            'created_at' => $this->created_at->format('d-m-Y'),
        ];
    }



    /**
 * @OA\Schema(
 *    schema="AnuncioNum",
 *    @OA\Property(
 *        property="id",
 *        type="integer",
 *        description="ID do anuncio",
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
 * property="distrito",
 * type="string",
 * description="Distrito do animal",
 * nullable=false,
 * example="Porto"
 * ),
 * @OA\Property(
 * property="etiqueta",
 * type="integer",
 * description="Etiqueta do animal",
 * nullable=false,
 * example="1"
 * ),
 * @OA\Property(
 * property="descricao",
 * type="string",
 * description="Descrição do animal",
 * nullable=true,
 * example="O Rufus é um cão muito meigo e brincalhão"
 * ),
 * @OA\Property(
 * property="estado",
 * type="integer",
 * description="Estado do anuncio",
 * nullable=false,
 * example="1"
 * ),
 * @OA\Property(
 * property="fotografias",
 * type="array",
 * description="Fotografias do animal",
 * nullable=true,
 * @OA\Items(
 *     type="string",
 *    example="https://i.imgur.com/3bY1f9R.jpg"
 * )
 * ),
 * @OA\Property(
 * property="created_at",
 * type="string",
 * description="Data de criação do anuncio",
 * nullable=false,
 * format="date-time"
 * ),
 * )
 * 
 *  @return array
 */
    public function toArrayNumeric() {
        $animal = Animal::where('id', $this->id_animal)->first();

        return [
            'id' => $this->id,
            'nome' => $animal->nome,
            'sexo' =>$animal->sexo,
            'especie' => $animal->especie,
            'raca' => $animal->raca,
            'porte' => $animal->porte,
            'idade' => $animal->idade,
            'cor' => $animal->cor,
            'distrito' => $this->distrito,
            'etiqueta' => $this->etiqueta,
            'descricao' => $this->descricao,
            'estado' => $this->estado,
            'fotografias' => $this->fotografiasUrls(),
            'created_at' => $this->created_at->format('d-m-Y'),
        ];
    }
}
