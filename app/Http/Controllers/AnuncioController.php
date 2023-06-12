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
     *    summary="Criar um anuncio",
     *    description="Rota para criar um anuncio, o utilizador tem de estar logado. Se o anuncio for criado com sucesso retorna o status 200",
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
     *             @OA\Property(property="status", type="integer", example="200"),
     *             @OA\Property(property="data",type="object")
     *          )
     *       )
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

        if($request->animal_id != null) {
            $animal = Animal::find($request->animal_id);
            if($animal->id_utilizador != $request->user()->id) {
                return response()->json(['message' => 'Não tem permissões para editar este animal'], 403);
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

        if($request->descricao != null) {
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


        if ($request->lang == 'en') {

            return response(['anuncio' => new AnuncioResource($anuncio->toArrayEnglish())], 200);
        }

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
     *             @OA\Property(property="status", type="integer", example="200"),
     *             @OA\Property(property="data",type="object")
     *          )
     *       )
     *  )
     */
    function listarAnimaisAdocao(Request $request)
    {
        $anuncios = Anuncio::where('etiqueta', 1)->get();

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
     *             @OA\Property(property="status", type="integer", example="200"),
     *             @OA\Property(property="data",type="object")
     *          )
     *       )
     *  )
     */
    function listarAnimaisDesaparecidos(Request $request)
    {
        $anuncios = Anuncio::where('etiqueta', 2)->get();

        if($request->lang == 'en')  {
            
            return response(['animais' => AnuncioResource::collection($anuncios)->map->toArrayEnglish()], 200);
        }
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
     *             @OA\Property(property="status", type="integer", example="200"),
     *             @OA\Property(property="data",type="object")
     *          )
     *       )
     *  )
     */
    function listarAnimaisPetsitting(Request $request)
    {

        $anuncios = Anuncio::where('etiqueta', 3)->get();

        if($request->lang == 'en')  {
            
            return response(['animais' => AnuncioResource::collection($anuncios)->map->toArrayEnglish()], 200);
        }
        return response(['animais' => AnuncioResource::collection($anuncios)], 200);
    }

    /**
     * @OA\Get(
     *    path="/api/animal/{id}",
     *    tags={"Anuncios"},
     *    summary="Listar animais para adoção",
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
     *             @OA\Property(property="status", type="integer", example="200"),
     *             @OA\Property(property="data",type="object")
     *          )
     *       )
     *  )
     */
    function verAnuncioAnimal(Request $request, $id) {
        $anuncio = Anuncio::find($id);

        if($anuncio == null) {
            return response(['erro' => 'Anuncio não encontrado'], 404);
        }

        $utilizador = Utilizador::find($anuncio->id_utilizador);

        if ($request->lang == 'en') {

            return response(['animal' => new AnuncioResource($anuncio->toArrayEnglish()), 'utilizador' => new UtilizadorResource($utilizador->toArray())], 200);
        }

        return response(['animal' => new AnuncioResource($anuncio), 'utilizador' => new UtilizadorResource($utilizador)], 200);

    }

    /**
     * @OA\Get(
     *    path="/utilizador/anuncios",
     *    tags={"Anuncios"},
     *    summary="Listar anuncios de um utilizador",
     *    description="",
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example="200"),
     *             @OA\Property(property="data",type="object")
     *          )
     *       )
     *  )
     */
    function listarAnunciosUtilizador(Request $request) {
        $anuncios = Anuncio::where('id_utilizador', $request->user()->id)->get();

        if($request->lang == 'en')  {
            return response(['anuncios' => AnuncioResource::collection($anuncios)->map->toArrayEnglish()], 200);
        }
        return response(['anuncios' => AnuncioResource::collection($anuncios)], 200);
    }

    /**
     * @OA\Get(
     *    path="/api/removeranuncio/{id}",
     *    tags={"Anuncios"},
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
     *             @OA\Property(property="status", type="integer", example="200"),
     *             @OA\Property(property="data",type="object")
     *          )
     *       )
     *  )
     */
    function removerAnuncio(Request $request) {
        $anuncio = Anuncio::find($request->id);

        if($anuncio == null) {
            return response(['erro' => 'Anuncio não encontrado'], 404);
        }

        if($anuncio->id_utilizador != $request->user()->id) {
            return response(['erro' => 'Não tem permissões para remover este anuncio'], 403);
        }

        $anuncio->delete();

        return response(['sucesso' => 'Anuncio removido com sucesso'], 200);
    }


    /**
     * @OA\Get(
     *    path="/api/anuncio/num/{id}",
     *    tags={"Anuncios"},
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
     *             @OA\Property(property="status", type="integer", example="200"),
     *             @OA\Property(property="data",type="object")
     *          )
     *       )
     *  )
     */
    function dadosAnuncioNum(Request $request) {
        $anuncio = Anuncio::where('id', $request->id)->first();

        if($anuncio == null) {
            return response(['erro' => 'Anuncio não encontrado'], 404);
        }

        if($anuncio->id_utilizador != $request->user()->id) {
            return response(['erro' => 'Não tem permissões para ver este anuncio'], 403);
        }

        return response(['anuncio' => (new AnuncioResource($anuncio))->toArrayNumeric()], 200);

    }


    /**
     * @OA\Post(
     *    path="/api/editaranuncio/{id}",
     *    tags={"Anuncios"},
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
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example="200"),
     *             @OA\Property(property="data",type="object")
     *          )
     *       )
     *  )
     */
    function editarAnuncio(Request $request) {
        $anuncio = Anuncio::find($request->id);

        if($anuncio == null) {
            return response(['erro' => 'Anuncio não encontrado'], 404);
        }

        if($anuncio->id_utilizador != $request->user()->id) {
            return response(['erro' => 'Não tem permissões para editar este anuncio'], 403);
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

        if($request->descricao != null) {
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


        if ($request->lang == 'en') {

            return response(['anuncio' => (new AnuncioResource($anuncio))->toArrayEnglish()], 200);
        }

        return response(['anuncio' => new AnuncioResource($anuncio)], 200);
    }

    /**
     * @OA\Get(
     *    path="/api/anuncio/estado/{id}",
     *    tags={"Anuncios"},
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
     *             @OA\Property(property="status", type="integer", example="200"),
     *             @OA\Property(property="data",type="object")
     *          )
     *       )
     *  )
     */
    function alterarEstadoAnuncio(Request $request) {
        $anuncio = Anuncio::find($request->id);

        if($anuncio == null) {
            return response(['erro' => 'Anuncio não encontrado'], 404);
        }

        if($anuncio->id_utilizador != $request->user()->id) {
            return response(['erro' => 'Não tem permissões para alterar o estado deste anuncio'], 403);
        }

        $anuncio->estado = !$anuncio->estado;

        $anuncio->save();

        if ($request->lang == 'en') {

            return response(['anuncio' =>(new AnuncioResource($anuncio))->toArrayEnglish()], 200);
        }

        return response(['anuncio' => new AnuncioResource($anuncio)], 200);
    }
    
}
