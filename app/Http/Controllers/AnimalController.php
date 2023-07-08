<?php

namespace App\Http\Controllers;

use App\Http\Resources\AnimalResource;
use App\Http\Resources\UtilizadorResource;
use Illuminate\Http\Request;
use App\Models\Utilizador;
use App\Models\Animal;
use App\Models\Anuncio;

class AnimalController extends Controller
{
    //

    /**
     * @OA\Post(
     *    path="/api/publicaranimal",
     *    tags={"Animais"},
     *    security={{ "token": {} }},
     *    summary="Registar um animal",
     *    description="",
     *    @OA\RequestBody(
     *         required=true,
     *         description="",
     *         @OA\JsonContent(
     *            required={"data_recolha", "ferido", "agressivo", "nome", "sexo", "especie", "raca", "porte", "idade", "cor"},
     *            @OA\Property(property="data_recolha",  example="Coimbra"),
     *            @OA\Property(property="ferido", example="1"),
     *            @OA\Property(property="agressivo",  example="Coimbra"),           
     *            @OA\Property(property="nome", example="Rufus"),
     *            @OA\Property(property="sexo",  example="1"),
     *            @OA\Property(property="especie", example="1"),
     *            @OA\Property(property="raca",  example="1"),
     *            @OA\Property(property="porte", example="1"),
     *            @OA\Property(property="idade",  example="1"),
     *            @OA\Property(property="cor", example="1"),
     *            
     *         ),
     *     ),
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *            @OA\Property(property="animal", ref="#/components/schemas/Animal"),
     *          )
     *       ),
     *   @OA\Response(
     *       response=422, description="Erro de validação",
     *     @OA\JsonContent(
     *      @OA\Property(property="message", type="string", description="Erro de validação", example="O campo data_recolha é obrigatório."),
     *    )
     * ),
     *  @OA\Response(
     *      response=401, description="Unauthenticated",
     *     @OA\JsonContent(
     *     @OA\Property(property="message", type="string", description="Unauthenticated", example="Unauthenticated"),
     *   )
     * ),
     *  )
     */
    function publicarAnimal(Request $request) {
        
        $validated = $request->validate([
            'data_recolha' => 'required|date',
            'ferido' => 'required|boolean',
            'agressivo' => 'required|boolean',
            'nome' => 'required',
            'sexo' => 'required',
            'especie' => 'required',
            'raca' => 'required',
            'porte' => 'required',
            'idade' => 'required',
            'cor' => 'required',
        ]);
        
        $animal = new Animal();
        $animal->id_utilizador = $request->user()->id;
        $animal->data_recolha = $validated['data_recolha'];
        $animal->ferido = $validated['ferido'];
        $animal->agressivo = $validated['agressivo'];
        $animal->nome = $validated['nome'];
        $animal->sexo = $validated['sexo'];
        $animal->especie = $validated['especie'];
        $animal->raca = $validated['raca'];
        $animal->porte = $validated['porte'];
        $animal->idade = $validated['idade'];
        $animal->cor = $validated['cor'];
        $animal->save();

        if($request->local_captura != null) {
            $animal->local_captura = $request->local_captura;
        }

        if($request->fotografia != null) {
            $nome_fotografia = $animal->id . $animal->nome . '.' . $request->fotografia->extension();
            $request->fotografia->move(public_path('storage/img/animais'), $nome_fotografia);
            $animal->fotografia = $nome_fotografia;
        }

        if($request->chip != null) {
            $animal->chip = $request->chip;
        }

        if($request->temperatura != null) {
            $animal->temperatura = $request->temperatura;
        }

        if($request->desparasitacao != null) {
            $animal->desparasitacao = $request->desparasitacao;
        }

        if($request->medicacao != null) {
            $animal->medicacao = $request->medicacao;
        }

        $animal->save();

        return response(['animal' => new AnimalResource($animal)], 200);
    }


     /**
     * @OA\Get(
     *    path="/api/associacao/animais",
     *    tags={"Animais"},
     *     security={{ "token": {} }},
     *    summary="Listar animais da associação logada",
     *    description="",
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *           @OA\Property(property="animais", type="array", @OA\Items(ref="#/components/schemas/Animal")),
     *          )
     *       ),
     *  @OA\Response(
     *     response=401, description="Unauthenticated",
     *    @OA\JsonContent(
     *   @OA\Property(property="message", type="string", description="Unauthenticated", example="Unauthenticated"),
     * )
     * ),
     *  )
     */
    function listarAnimaisAssociacao(Request $request) {
        $animais = Animal::where('id_utilizador', $request->user()->id)->orderBy('id', 'desc')->get();

        return response(['animais' => AnimalResource::collection($animais)], 200);
    }

    /**
     * @OA\Delete(
     *    path="/api/removeranimal/{id}",
     *    tags={"Animais"},
     *     security={{ "token": {} }},
     *    summary="Remover um animal",
     *    description="",
     *      @OA\Parameter(
     *          name="id",
     *          description="Id do animal",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *          @OA\Property(property="sucesso", type="string", example="Animal removido com sucesso"),
     *          )
     *       ),
     * @OA\Response(
     *    response=404, description="Animal não encontrado",
     *   @OA\JsonContent(
     *  @OA\Property(property="erro", type="string", example="Animal não encontrado"),
     * )
     * ),
     * @OA\Response(
     *   response=403, description="Sem permissões para remover o animal",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Sem permissões para remover o animal"),
     * )
     * ),
     * @OA\Response(
     *  response=401, description="Unauthenticated",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", description="Unauthenticated", example="Unauthenticated"),
     * )
     * ),
     *  )
     */
    function removerAnimal(Request $request) {
        $animal = Animal::where('id', $request->id)->first();
        if($animal == null) {
            return response(['erro' => __('custom.animal_nao_encontrado')], 404);
        }
        if($animal->id_utilizador != $request->user()->id) {
            return response(['message' => __('custom.permissoes_remover_animal')], 403);
        }

        Anuncio::where('id_animal', $animal->id)->delete();
        $animal->delete();
        return response(['sucesso' => __('custom.animal_removido')], 200);
    }


    /**
     * @OA\Get(
     *    path="/api/associacao/animal/num/{id}",
     *    tags={"Animais"},
     *     security={{ "token": {} }},
     *    summary="Obter dados numéricos de um animal",
     *    description="",
     *      @OA\Parameter(
     *          name="id",
     *          description="Id do animal",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *          @OA\Property(property="animal", type="object", ref="#/components/schemas/AnimalNum"),
     *          )
     *       ),
     * @OA\Response(
     *   response=404, description="Animal não encontrado",
     * @OA\JsonContent(
     * @OA\Property(property="erro", type="string", example="Animal não encontrado"),
     * )
     * ),
     * @OA\Response(
     *  response=403, description="Sem permissões para visualizar o animal",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Sem permissões para visualizar o animal"),
     * )
     * ),
     * @OA\Response(
     * response=401, description="Unauthenticated",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", description="Unauthenticated", example="Unauthenticated"),
     * )
     * ),
     * 
     *  )
     */
    function dadosAnimalNum(Request $request) {
        $animal = Animal::where('id', $request->id)->first();

        if($animal == null) {
            return response(['message' => __('custom.animal_nao_encontrado')], 404);
        }

        if($animal->id_utilizador != $request->user()->id) {
            return response(['message' => __('custom.permissoes_visualizacao_animal')], 403);
        }

        return response(['animal' => (new AnimalResource($animal))->toArrayNumeric()], 200);

    }

    
    /**
     * @OA\Post(
     *    path="/api/editaranimal/{id}",
     *    tags={"Animais"},
     *     security={{ "token": {} }},
     *    summary="Editar dados de um animal",
     *    description="",
     *  @OA\Parameter(
     *          name="id",
     *          description="Id do animal",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *    @OA\RequestBody(
     *         required=true,
     *         description="",
     *         @OA\JsonContent(
     *            required={"data_recolha", "ferido", "agressivo", "nome", "sexo", "especie", "raca", "porte", "idade", "cor"},
     *            @OA\Property(property="data_recolha",  example="Coimbra"),
     *            @OA\Property(property="ferido", example="1"),
     *            @OA\Property(property="agressivo",  example="Coimbra"),           
     *            @OA\Property(property="nome", example="Rufus"),
     *            @OA\Property(property="sexo",  example="1"),
     *            @OA\Property(property="especie", example="1"),
     *            @OA\Property(property="raca",  example="1"),
     *            @OA\Property(property="porte", example="1"),
     *            @OA\Property(property="idade",  example="1"),
     *            @OA\Property(property="cor", example="1"),
     *            
     *         ),
     *     ),
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *         @OA\Property(property="animal", type="object", ref="#/components/schemas/Animal"),
     *          )
     *       ),
     * @OA\Response(
     *   response=404, description="Animal não encontrado",
     * @OA\JsonContent(
     * @OA\Property(property="erro", type="string", example="Animal não encontrado"),
     * )
     * ),
     * @OA\Response(
     *  response=403, description="Sem permissões para editar o animal",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Sem permissões para editar o animal"),
     * )
     * ),
     * @OA\Response(
     * response=401, description="Unauthenticated",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", description="Unauthenticated", example="Unauthenticated"),
     * )
     * ),
     * )
     */
    function editarAnimal(Request $request) {
        $animal = Animal::where('id', $request->id)->first();

        if($animal == null) {
            return response(['message' => __('custom.animal_nao_encontrado')], 404);
        }

        if($animal->id_utilizador != $request->user()->id) {
            return response(['message' => __('custom.permissoes_alteracao_animal')], 403);
        }

        $validated = $request->validate([
            'data_recolha' => 'required|date',
            'ferido' => 'required|boolean',
            'agressivo' => 'required|boolean',
            'nome' => 'required',
            'sexo' => 'required',
            'especie' => 'required',
            'raca' => 'required',
            'porte' => 'required',
            'idade' => 'required',
            'cor' => 'required',
        ]);
        
        $animal->data_recolha = $validated['data_recolha'];
        $animal->ferido = $validated['ferido'];
        $animal->agressivo = $validated['agressivo'];
        $animal->nome = $validated['nome'];
        $animal->sexo = $validated['sexo'];
        $animal->especie = $validated['especie'];
        $animal->raca = $validated['raca'];
        $animal->porte = $validated['porte'];
        $animal->idade = $validated['idade'];
        $animal->cor = $validated['cor'];
        $animal->save();

        if($request->local_captura != null) {
            $animal->local_captura = $request->local_captura;
        }

        if($request->fotografia != null) {
            $nome_fotografia = $animal->id . $animal->nome . '.' . $request->fotografia->extension();
            $request->fotografia->move(public_path('storage/img/animais'), $nome_fotografia);
            $animal->fotografia = $nome_fotografia;
        }

        if($request->chip != null) {
            $animal->chip = $request->chip;
        }

        if($request->temperatura != null) {
            $animal->temperatura = $request->temperatura;
        }

        if($request->desparasitacao != null) {
            $animal->desparasitacao = $request->desparasitacao;
        }

        if($request->medicacao != null) {
            $animal->medicacao = $request->medicacao;
        }

        $animal->save();

        return response(['animal' => new AnimalResource($animal)], 200);

    }

}
