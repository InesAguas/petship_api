<?php

namespace App\Http\Controllers;
use App\Models\Mensagem;
use App\Models\Utilizador;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;


class MensagemController extends Controller
{

    /**
     * @OA\Post(
     *    path="/api/enviarmensagem",
     *    tags={"Mensagens"},
     *    summary="Enviar mensagem a um utilizador",
     *    description="Rota para enviar mensagem a um utilizador, o utilizador tem de estar logado. Se a mensagem for enviada com sucesso retorna o status 200",
     * 
     *    @OA\RequestBody(
     *         required=true,
     *         description="",
     *         @OA\JsonContent(
     *            required={"id_recebe", "mensagem"},
     *            @OA\Property(property="id_recebe", example="1"),
     *            @OA\Property(property="mensagem",  example="Olá, tudo bem?"),
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
    function enviarMensagem(Request $request) {
        $validated = $request->validate([
            'id_recebe' => 'required|integer',
            'mensagem' => 'required|string'
        ]);

        if(Utilizador::where('id', $validated['id_recebe'])->doesntExist()) {
            return response()->json(['message' => __('custom.utilizador_nao_encontrado')], 404);
        }

        if($validated['id_recebe'] == $request->user()->id) {
            return response()->json(['message' => __('custom.propria_conversa')], 403);
        }
            
        $mensagem = new Mensagem();
        $mensagem->id_envia = $request->user()->id;
        $mensagem->id_recebe = $validated['id_recebe'];
        $mensagem->mensagem = $validated['mensagem'];

        $mensagem->save();

            $mensagem['nome_envia'] = Utilizador::find($mensagem['id_envia'])->nome;
            $mensagem['nome_recebe'] = Utilizador::find($mensagem['id_recebe'])->nome;

        return response()->json(['mensagem' => $mensagem], 200);
    }

    /**
     * @OA\Get(
     *    path="/api/mensagens/{id_recebe}",
     *    tags={"Mensagens"},
     *    summary="Ler conversa com um utilizador",
     *    description="Rota para ler a conversa com um utilizador, o utilizador tem de estar logado. Se a conversa for lida com sucesso retorna o status 200",
     *    @OA\Parameter(
     *          name="id_recebe",
     *          description="Id do utilizador com quem quer ler a conversa",
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
    function lerConversa(Request $request) {

        if(Utilizador::where('id', $request->id_recebe)->doesntExist()) {
            return response()->json(['message' => __('custom.utilizador_nao_encontrado')], 404);
        }

        if( $request->id_recebe == $request->user()->id) {
            return response()->json(['message' => __('custom.propria_conversa')], 403);
        }

        $mensagens = Mensagem::where('id_envia', $request->user()->id)
            ->where('id_recebe',  $request->id_recebe)
            ->orWhere('id_envia',  $request->id_recebe)
            ->where('id_recebe', $request->user()->id)->orderBy('created_at', 'desc')->take(50)->get();

        return response()->json(['mensagens' => $mensagens], 200);
    }

    /**
     * @OA\Get(
     *    path="/api/conversasativas",
     *    tags={"Mensagens"},
     *    summary="Obter conversas ativas",
     *    description="Rota para obter todas as conversas que se tem ativas (com mensagens enviadas ou recebidas). O utilizador tem de estar logado. Se as conversas forem obtidas com sucesso retorna o status 200",
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example="200"),
     *             @OA\Property(property="data",type="object")
     *          )
     *       )
     *  )
     */
    function conversasAtivas(Request $request) {
        $utilizador = Utilizador::find($request->user()->id);

        $recebidas = $utilizador->mensagensRecebidas()->whereIn('id', function($query) use(&$utilizador){
            $query->select(DB::raw("MAX(id) FROM mensagens WHERE id_recebe = " . $utilizador->id . " GROUP BY id_envia"));    
        })->get();

        $enviadas = $utilizador->mensagensEnviadas()->whereIn('id', function($query) use(&$utilizador){
            $query->select(DB::raw("MAX(id) FROM mensagens WHERE id_envia = " . $utilizador->id . " GROUP BY id_recebe"));    
        })->get();



        $conversas = $recebidas->merge($enviadas)->unique()->toArray();
        $conversas = collect($conversas)->sortByDesc('created_at')->groupBy(function ($conversa) {
            return collect([$conversa['id_envia'], $conversa['id_recebe']])->sort()->implode('-');
        })->map(function ($conversa) {
            return $conversa->first();
        })->values();

        $conversas = $conversas->map(function ($conversa) {
            $conversa['nome_envia'] = Utilizador::find($conversa['id_envia'])->nome;
            $conversa['nome_recebe'] = Utilizador::find($conversa['id_recebe'])->nome;
            $conversa['fotografia'] = Utilizador::find($conversa['id_envia'])->fotografiaUrl();
            return $conversa;
        })->toArray();

        return response()->json(['conversas' => $conversas], 200);
    }
    
}
