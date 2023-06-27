<?php

namespace App\Http\Controllers;

use App\Http\Resources\CandidaturaResource;
use Illuminate\Http\Request;
use App\Models\Candidatura;
use App\Models\Anuncio;

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
     *         @OA\Property(property="estado", type="boolean", example=true),
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
            'estado' => 'required'
        ]);

        if(!$request->estado) {
            return response(['message' => __('custom.declaracoes_nao_aceites')], 422);
        }
  
        $candidatura = new Candidatura();
        $candidatura->id_anuncio = $request->id_anuncio;
        $candidatura->id_utilizador = $request->user()->id;
        $candidatura->cc = $validated['cc'];
        $candidatura->estado = true;

        $candidatura->save();

        return response(['candidatura' => new CandidaturaResource($candidatura)], 200);
    }
}
