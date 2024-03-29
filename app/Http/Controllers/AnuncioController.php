<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animal;
use App\Models\Anuncio;
use App\Models\Utilizador;

use App\Http\Resources\AnuncioResource;
use App\Http\Resources\UtilizadorResource;



class AnuncioController extends Controller
{

    /**
     * @OA\Post(
     *    path="/api/novoanuncio",
     *    tags={"Anuncios"},
     *     security={{ "token": {} }},
     *    summary="Criar um anuncio",
     *    description="Rota para criar um anuncio, o utilizador tem de estar logado. É possivel enviar o id do animal para criar um anuncio para um animal já existente.",
     *    @OA\RequestBody(
     *         required=true,
     *         description="",
     *         @OA\JsonContent(
     *            required={"nome", "sexo", "especie", "raca", "porte", "idade", "cor", "distrito", "etiqueta"},
     *            @OA\Property(property="nome", example="Rufus"),
     *            @OA\Property(property="sexo",  example="1"),
     *            @OA\Property(property="especie", example="1"),
     *            @OA\Property(property="raca",  example="1"),
     *            @OA\Property(property="porte", example="1"),
     *            @OA\Property(property="idade",  example="1"),
     *            @OA\Property(property="cor", example="1"),
     *            @OA\Property(property="distrito",  example="Coimbra"),
     *            @OA\Property(property="etiqueta", example="1"),
     *         ),
     *     ),
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *            @OA\Property(property="anuncio", ref="#/components/schemas/Anuncio"),
     *          )
     *       ),
     *   @OA\Response(
     *       response=422, description="Erro de validação",
     *     @OA\JsonContent(
     *      @OA\Property(property="message", type="string", description="Erro de validação", example="O campo nome é obrigatório."),
     *    )
     * ),
     *  @OA\Response(
     *      response=403, description="Sem permissões",
     *    @OA\JsonContent(
     *     @OA\Property(property="message", type="string", description="Sem permissões", example="Não tem permissões para alterar este animal."),
     *  )
     * ),
     * @OA\Response(
     *     response=401, description="Não autenticado",
     *   @OA\JsonContent(
     *    @OA\Property(property="message", type="string", description="Não autorizado", example="Unauthenticated"),
     * )
     * ),
     *  )
     */
    function novoAnuncio(Request $request)
    {
        //funcao para inserir um animal na base de dados

        //Validar os dados que recebo
        $validated = $request->validate([
            'nome' => 'required|string',
            'sexo' => 'required|string',
            'especie' => 'required|string',
            'raca' => 'required|string',
            'porte' => 'required|string',
            'idade' => 'required|string',
            'cor' => 'required|string',
            'distrito' => 'required|string',
            'etiqueta' => 'required|string',
        ]);

        if ($request->animal_id != null) {
            $animal = Animal::find($request->animal_id);
            if ($animal->id_utilizador != $request->user()->id) {
                return response()->json(['message' => __('custom.permissoes_alteracao_animal')], 403);
            }
        } else {
            $animal = new Animal();
            $animal->id_utilizador = $request->user()->id;
        }
        //Criar novo objeto Animal

        $animal->nome = $validated['nome'];
        $animal->sexo = $validated['sexo'];
        $animal->especie = $validated['especie'];
        $animal->raca = $validated['raca'];
        $animal->porte = $validated['porte'];
        $animal->idade = $validated['idade'];
        $animal->cor = $validated['cor'];

        $animal->save();


        $anuncio = new Anuncio();
        $anuncio->id_animal = $animal->id;
        $anuncio->id_utilizador = $request->user()->id;
        $anuncio->distrito = $validated['distrito'];
        $anuncio->etiqueta = $validated['etiqueta'];
        $anuncio->estado = true;

        $anuncio->save();

        if ($request->descricao != null) {
            $anuncio->descricao = $request->descricao;
        }

        //guardar apenas o titulo de cada fotografia
        if ($request->fotografias != null) {
            $fotografias = [];
            $i = 0;
            foreach ($request->fotografias as $fotografia) {
                $i++;
                $nome_fotografia = $animal->id . $animal->nome . $i . '.' . $fotografia->extension();
                $fotografias[] = $nome_fotografia;
                $fotografia->move(public_path('storage/img/animais'), $nome_fotografia);
            }
            $anuncio->fotografias = json_encode($fotografias);
        }
        //Guardar na  base de dados
        $anuncio->save();

        return response(['anuncio' => new AnuncioResource($anuncio)], 200);
    }

    /**
     * @OA\Get(
     *    path="/api/adotar",
     *    tags={"Anuncios"},
     *    summary="Listar animais para adoção",
     *    description="",
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *           @OA\Property(property="animais", type="array", @OA\Items(ref="#/components/schemas/Anuncio")),
     *          )
     *       )
     *  )
     */
    function listarAnimaisAdocao(Request $request)
    {
        $anuncios = Anuncio::where('etiqueta', 1)->orderBy('id', 'desc')->where('estado', 1)->get();

        return response(['animais' => AnuncioResource::collection($anuncios)], 200);
    }

    /**
     * @OA\Get(
     *    path="/api/desaparecido",
     *    tags={"Anuncios"},
     *    summary="Listar animais desaparecidos",
     *    description="",
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *           @OA\Property(property="animais", type="array", @OA\Items(ref="#/components/schemas/Anuncio")),
     *          )
     *       )
     *  )
     */
    function listarAnimaisDesaparecidos(Request $request)
    {
        $anuncios = Anuncio::where('etiqueta', 2)->orderBy('id', 'desc')->where('estado', 1)->get();

        return response(['animais' => AnuncioResource::collection($anuncios)], 200);
    }

    /**
     * @OA\Get(
     *    path="/api/petsitting",
     *    tags={"Anuncios"},
     *    summary="Listar animais para petsitting",
     *    description="",
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *           @OA\Property(property="animais", type="array", @OA\Items(ref="#/components/schemas/Anuncio")),
     *          )
     *       )
     *  )
     */
    function listarAnimaisPetsitting(Request $request)
    {

        $anuncios = Anuncio::where('etiqueta', 3)->orderBy('id', 'desc')->where('estado', 1)->get();

        return response(['animais' => AnuncioResource::collection($anuncios)], 200);
    }

    /**
     * @OA\Get(
     *    path="/api/animal/{id}",
     *    tags={"Anuncios"},
     *    summary="Obter dados de um animal",
     *    description="",
     *     @OA\Parameter(
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
     *          @OA\Property(property="animal", type="object", ref="#/components/schemas/Anuncio"),
     *         @OA\Property(property="utilizador", type="object", ref="#/components/schemas/Utilizador"),
     *          )
     *       ),
     *    @OA\Response(
     *         response=404, description="Not Found",
     *        @OA\JsonContent(
     *          @OA\Property(property="message", type="string", example="Anuncio não encontrado.")
     *       )
     *   )
     * 
     *  )
     */
    function verAnuncioAnimal(Request $request, $id)
    {
        $anuncio = Anuncio::find($id);

        if ($anuncio == null || $anuncio->estado == 0) {
            return response(['message' => __('custom.anuncio_nao_encontrado')], 404);
        }

        $utilizador = Utilizador::find($anuncio->id_utilizador);

        return response(['animal' => new AnuncioResource($anuncio), 'utilizador' => new UtilizadorResource($utilizador)], 200);
    }

    /**
     * @OA\Get(
     *    path="/utilizador/anuncios",
     *     security={{ "token": {} }},
     *    tags={"Anuncios"},
     *    summary="Listar anuncios de um utilizador autenticado",
     *    description="",
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *          @OA\Property(property="anuncios", type="array", @OA\Items(ref="#/components/schemas/Anuncio")),
     *          )
     *       ),
     *   @OA\Response(
     *        response=401, description="Unauthorized",
     *       @OA\JsonContent(
     *        @OA\Property(property="message", type="string", example="Unauthenticated.")
     *      )
     *  )
     *  )
     */
    function listarAnunciosUtilizador(Request $request)
    {
        $anuncios = Anuncio::where('id_utilizador', $request->user()->id)->orderBy('id', 'desc')->get();

        return response(['anuncios' => AnuncioResource::collection($anuncios)], 200);
    }

    /**
     * @OA\Delete(
     *    path="/api/removeranuncio/{id}",
     *    tags={"Anuncios"},
     *     security={{ "token": {} }},
     *    summary="Remover anuncio",
     *    description="",
     *     @OA\Parameter(
     *          name="id",
     *          description="Id do anuncio",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *         @OA\Property(property="sucesso", type="string", example="Anuncio removido com sucesso"),
     *          )
     *       ),
     *   @OA\Response(
     *       response=404, description="Not Found",
     *      @OA\JsonContent(
     *      @OA\Property(property="message", type="string", example="Anuncio não encontrado.")
     *     )
     * ),
     *  @OA\Response(
     *      response=403, description="Forbidden",
     *    @OA\JsonContent(
     *   @OA\Property(property="message", type="string", example="Não tem permissões para remover este anuncio.")
     * )
     * ),
     * @OA\Response(
     *     response=401, description="Unauthenticated",
     *   @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Unauthenticated.")
     * )
     * )
     * 
     *  )
     */
    function removerAnuncio(Request $request)
    {
        $anuncio = Anuncio::find($request->id);

        if ($anuncio == null) {
            return response(['message' => __('custom.anuncio_nao_encontrado')], 404);
        }

        if ($anuncio->id_utilizador != $request->user()->id) {
            return response(['message' => __('custom.permissoes_remover_anuncio')], 403);
        }

        $anuncio->delete();

        return response(['sucesso' => 'Anuncio removido com sucesso'], 200);
    }


    /**
     * @OA\Get(
     *    path="/api/anuncio/num/{id}",
     *    tags={"Anuncios"},
     *     security={{ "token": {} }},
     *    summary="Dados de um anuncio (numéricos)",
     *    description="",
     *     @OA\Parameter(
     *          name="id",
     *          description="Id do anuncio",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *         @OA\Property(property="anuncio", type="object", ref="#/components/schemas/AnuncioNum"),
     *          )
     *       ),
     *  @OA\Response(
     *      response=404, description="Not Found",
     *   @OA\JsonContent(
     *  @OA\Property(property="message", type="string", example="Anuncio não encontrado.")
     * )
     * ),
     * @OA\Response(
     *    response=403, description="Forbidden",
     *  @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Não tem permissões para visualizar este anuncio.")
     * )
     * ),
     * @OA\Response(
     *    response=401, description="Unauthenticated",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Unauthenticated.")
     * )
     * )
     *  )
     */
    function dadosAnuncioNum(Request $request)
    {
        $anuncio = Anuncio::where('id', $request->id)->first();

        if ($anuncio == null) {
            return response(['message' => __('custom.anuncio_nao_encontrado')], 404);
        }

        if ($anuncio->id_utilizador != $request->user()->id) {
            return response(['message' => __('custom.permissoes_visualizacao')], 403);
        }

        return response(['anuncio' => (new AnuncioResource($anuncio))->toArrayNumeric()], 200);
    }


    /**
     * @OA\Post(
     *    path="/api/editaranuncio/{id}",
     *    tags={"Anuncios"},
     *     security={{ "token": {} }},
     *    summary="Editar anuncio",
     *    description="",
     *     @OA\Parameter(
     *          name="id",
     *          description="Id do anuncio",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *    @OA\RequestBody(
     *         required=true,
     *        description="Dados do anuncio",
     *       @OA\JsonContent(
     *     @OA\Property(property="nome", type="string", example="Nome do animal"),
     *    @OA\Property(property="sexo", type="integer", example="1"),
     *  @OA\Property(property="especie", type="integer", example="1"),
     * @OA\Property(property="raca", type="integer", example="1"),
     * @OA\Property(property="porte", type="integer", example="1"),
     * @OA\Property(property="idade", type="integer", example="1"),
     * @OA\Property(property="cor", type="integer", example="1"),
     * @OA\Property(property="distrito", type="string", example="Aveiro"),
     * @OA\Property(property="etiqueta", type="integer", example="1"),
     * )
     * ),
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *        @OA\Property(property="anuncio", type="object", ref="#/components/schemas/Anuncio"),
     *          )
     *       ),
     * @OA\Response(
     *     response=404, description="Not Found",
     *  @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Anuncio não encontrado.")
     * )
     * ),
     * @OA\Response(
     * 
     *   response=403, description="Forbidden",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Não tem permissões para editar este anuncio.")
     * )
     * ),
     * @OA\Response(
     *   response=401, description="Unauthenticated",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Unauthenticated.")
     * )
     * ),
     * @OA\Response(
     *  response=422, description="Unprocessable Entity",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Os dados recebidos não são válidos.")
     * )
     * )
     *  )
     */
    function editarAnuncio(Request $request)
    {
        $anuncio = Anuncio::find($request->id);

        if ($anuncio == null) {
            return response(['message' => __('custom.anuncio_nao_encontrado')], 404);
        }

        if ($anuncio->id_utilizador != $request->user()->id) {
            return response(['message' => __('custom.permissoes_alteracao')], 403);
        }

        $animal = Animal::where('id', $anuncio->id_animal)->first();

        //Validar os dados que recebo
        $validated = $request->validate([
            'nome' => 'required|',
            'sexo' => 'required|',
            'especie' => 'required|',
            'raca' => 'required|',
            'porte' => 'required|',
            'idade' => 'required|',
            'cor' => 'required|',
            'distrito' => 'required|',
            'etiqueta' => 'required|',
        ]);

        $animal->nome = $validated['nome'];
        $animal->sexo = $validated['sexo'];
        $animal->especie = $validated['especie'];
        $animal->raca = $validated['raca'];
        $animal->porte = $validated['porte'];
        $animal->idade = $validated['idade'];
        $animal->cor = $validated['cor'];

        $animal->save();

        $anuncio->distrito = $validated['distrito'];
        $anuncio->etiqueta = $validated['etiqueta'];

        $anuncio->save();

        if ($request->descricao != null) {
            $anuncio->descricao = $request->descricao;
        }

        //guardar apenas o titulo de cada fotografia
        if ($request->fotografias != null) {
            $fotografias = [];
            $i = 0;
            foreach ($request->fotografias as $fotografia) {
                $i++;
                $nome_fotografia = $animal->id . $animal->nome . $i . '.' . $fotografia->extension();
                $fotografias[] = $nome_fotografia;
                $fotografia->move(public_path('storage/img/animais'), $nome_fotografia);
            }
            $anuncio->fotografias = json_encode($fotografias);
        }
        //Guardar na  base de dados
        $anuncio->save();

        return response(['anuncio' => new AnuncioResource($anuncio)], 200);
    }

    /**
     * @OA\Get(
     *    path="/api/anuncio/estado/{id}",
     *    tags={"Anuncios"},
     *     security={{ "token": {} }},
     *    summary="Alterar estado do anuncio",
     *    description="",
     *     @OA\Parameter(
     *          name="id",
     *          description="Id do anuncio",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *       @OA\Property(property="anuncio", type="object", ref="#/components/schemas/Anuncio"),
     *          )
     *       ),
     * @OA\Response(
     *    response=404, description="Not Found",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Anuncio não encontrado.")
     * )
     * ),
     * @OA\Response(
     *  response=403, description="Forbidden",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Não tem permissões para alterar o estado deste anuncio.")
     * )
     * ),
     * @OA\Response(
     *  response=401, description="Unauthenticated",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Unauthenticated.")
     * )
     * )
     *  )
     */
    function alterarEstadoAnuncio(Request $request)
    {
        $anuncio = Anuncio::find($request->id);

        if ($anuncio == null) {
            return response(['message' => __('custom.anuncio_nao_encontrado')], 404);
        }

        if ($anuncio->id_utilizador != $request->user()->id) {
            return response(['message' => __('custom.permissoes_alteracao')], 403);
        }

        $anuncio->estado = !$anuncio->estado;

        $anuncio->save();

        return response(['anuncio' => new AnuncioResource($anuncio)], 200);
    }
}
