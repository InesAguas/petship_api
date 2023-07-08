<?php

namespace App\Http\Controllers;

use App\Http\Resources\CandidaturaResource;
use Illuminate\Http\Request;
use App\Models\Candidatura;
use App\Models\Anuncio;
use App\Models\Utilizador;

class CandidaturaController extends Controller
{

     /**
     * @OA\Post(
     *    path="/api/candidaturainserir",
     *    tags={"Candidaturas"},
     *     security={{ "token": {} }},
     *    summary="Iniciar candidatura de adoção",
     *    description="Inicia uma candidatura de adoção a um animal.",
     *    @OA\RequestBody(
     *         required=true,
     *         description="",
     *         @OA\JsonContent(
     *            @OA\Property(property="id_anuncio", type="number", example=1),
     *           @OA\Property(property="id_utilizador", type="number", example=1),
     *          @OA\Property(property="cc", type="number", example=12345678),
     *         @OA\Property(property="termos", type="boolean", example=true),
     *         ),
     *     ),
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *            @OA\Property(property="candidatura", ref="#/components/schemas/Candidatura"),
     *          )
     *       ),
     * @OA\Response(
     *     response=401, description="Não autenticado",
     *   @OA\JsonContent(
     *    @OA\Property(property="message", type="string", description="Não autorizado", example="Unauthenticated"),
     * )
     * ),
     * @OA\Response(
     *    response=422, description="Erro de validação",
     *  @OA\JsonContent(
     *   @OA\Property(property="message", type="string", description="Erro de validação", example="O campo cc é obrigatório."),
     * )
     * ),
     *  )
     */
    function inserirCandidatura(Request $request)
    {
        //Select anuncio com o id do animal que está a ser candidatado

        $validated = $request->validate([
            'id_utilizador' => 'required',
            'cc' => 'required',
            'termos' => 'required'
        ]);

        if(!$request->termos) {
            return response(['message' => __('custom.declaracoes_nao_aceites')], 422);
        }
  
        $candidatura = new Candidatura();
        $candidatura->id_anuncio = $request->id_anuncio;
        $candidatura->id_utilizador = $request->user()->id;
        $candidatura->cc = $validated['cc'];
        $candidatura->termos = true;
        $candidatura->estado = 1;

        $candidatura->save();

        return response(['candidatura' => new CandidaturaResource($candidatura)], 200);
    }

    /**
     * @OA\Get(
     *    path="/api/dashboard/candidaturas",
     *    tags={"Candidaturas"},
     *     security={{ "token": {} }},
     *    summary="Listar candidaturas de adoção",
     *    description="Lista as candidaturas de adoção a animais de uma associação ou candidaturas de adoção de um utilizador.",
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *          @OA\Property(property="candidaturas", type="array", @OA\Items(ref="#/components/schemas/Candidatura")),
     *          )
     *       ),
     * @OA\Response(
     *     response=401, description="Não autenticado",
     *   @OA\JsonContent(
     *    @OA\Property(property="message", type="string", description="Não autorizado", example="Unauthenticated"),
     * )
     * ),
     *  )
     */
    function listarCandidaturas(Request $request) {
        //procurar na tabela as candidaturas que tenham o id que um animal que tem o id da associação
        if($request->user()->isAssociacao()) {
            $candidaturas = Candidatura::whereHas('anuncio.utilizador', function ($query) use ($request) {
                $query->where('id', $request->user()->id);
            })->get();
        } else {
            $candidaturas = Candidatura::where('id_utilizador', $request->user()->id)->get();
        }

        return response(['candidaturas' =>  CandidaturaResource::collection($candidaturas)], 200);
    }

    /**
     * @OA\Post(
     *    path="/api/candidatura/aceitar/{id}",
     *    tags={"Candidaturas"},
     *     security={{ "token": {} }},
     *    summary="Aceitar candidatura de adoção",
     *    description="Aceita uma candidatura de adoção a um animal. Apenas a associação que criou o anúncio pode aceitar uma candidatura.",
     *   @OA\Parameter(
     *        description="ID da candidatura",
     *       in="path",
     *      name="id",
     *     required=true,
     *   @OA\Schema(
     *         type="integer",
     *    )
     * ),
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *            @OA\Property(property="candidatura", ref="#/components/schemas/Candidatura"),
     *          )
     *       ),
     * @OA\Response(
     *     response=401, description="Não autenticado",
     *   @OA\JsonContent(
     *    @OA\Property(property="message", type="string", description="Não autorizado", example="Unauthenticated"),
     * )
     * ),
     * @OA\Response(
     *   response=403, description="Não autorizado",
     * @OA\JsonContent(
     *   @OA\Property(property="message", type="string", description="Não autorizado", example="Não autorizado"),
     * )
     * ),
     *  )
     */
    function aceitarCandidatura(Request $request) {
        $candidatura = Candidatura::where('id', $request->id)->first();
        $utilizador = $request->user();

        if($utilizador->isAssociacao() && $candidatura->anuncio->utilizador->id == $utilizador->id) {
            $candidatura->estado = 2;
            $candidatura->save();
            return response(['candidatura' => new CandidaturaResource($candidatura)], 200);
        } else {
            return response(['message' => __('custom.nao_autorizado')], 403);
        }
        
    }


    /**
     * @OA\Post(
     *    path="/api/candidatura/concluir/{id}",
     *    tags={"Candidaturas"},
     *     security={{ "token": {} }},
     *    summary="Concluir candidatura de adoção",
     *    description="Conclui uma candidatura de adoção a um animal. Apenas a associação que criou o anúncio pode concluir uma candidatura.",
     *   @OA\Parameter(
     *        description="ID da candidatura",
     *       in="path",
     *      name="id",
     *     required=true,
     *   @OA\Schema(
     *         type="integer",
     *    )
     * ),
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *            @OA\Property(property="candidatura", ref="#/components/schemas/Candidatura"),
     *          )
     *       ),
     * @OA\Response(
     *     response=401, description="Não autenticado",
     *   @OA\JsonContent(
     *    @OA\Property(property="message", type="string", description="Não autorizado", example="Unauthenticated"),
     * )
     * ),
     * @OA\Response(
     *  response=403, description="Não autorizado",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", description="Não autorizado", example="Não autorizado"),
     * )
     * ),
     *  )
     */
    function concluirCandidatura(Request $request) {
                
            $candidatura = Candidatura::where('id', $request->id)->first();
    
            $utilizador = $request->user();
    
            if($utilizador->isAssociacao() && $candidatura->anuncio->utilizador->id == $utilizador->id) {
                $candidatura->estado = 3;
                $candidatura->save();
                return response(['candidatura' => new CandidaturaResource($candidatura)], 200);
            } else {
                return response(['message' => __('custom.nao_autorizado')], 403);
            }
    }


    /**
     * @OA\Post(
     *    path="/api/candidatura/cancelar/{id}",
     *    tags={"Candidaturas"},
     *     security={{ "token": {} }},
     *    summary="Cancelar candidatura de adoção",
     *    description="Cancela uma candidatura de adoção a um animal.",
     *   @OA\Parameter(
     *        description="ID da candidatura",
     *       in="path",
     *      name="id",
     *     required=true,
     *   @OA\Schema(
     *         type="integer",
     *    )
     * ),
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *            @OA\Property(property="candidatura", ref="#/components/schemas/Candidatura"),
     *          )
     *       ),
     * @OA\Response(
     *     response=401, description="Não autenticado",
     *   @OA\JsonContent(
     *    @OA\Property(property="message", type="string", description="Não autorizado", example="Unauthenticated"),
     * )
     * ),
     * @OA\Response(
     * response=403, description="Não autorizado",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", description="Não autorizado", example="Não autorizado"),
     * )
     * ),
     *  )
     */
    function cancelarCandidatura(Request $request) {

        $candidatura = Candidatura::where('id', $request->id)->first();

        $utilizador = $request->user();

        if(($utilizador->isAssociacao() && $candidatura->anuncio->utilizador->id == $utilizador->id) || ($utilizador->isParticular() && $candidatura->id_utilizador == $utilizador->id)) {
            $candidatura->estado = 4;
            $candidatura->save();

            return response(['candidatura' => new CandidaturaResource($candidatura)], 200);
        } else {
            return response(['message' => __('custom.nao_autorizado')], 401);
        }
    }
}
