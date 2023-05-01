<?php

namespace App\Http\Controllers;
use App\Models\Mensagem;
use App\Models\Utilizador;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class MensagemController extends Controller
{
    //
    function enviarMensagem(Request $request) {
        $validated = $request->validate([
            'id_recebe' => 'required|integer',
            'mensagem' => 'required|string'
        ]);

        if(Utilizador::where('id', $validated['id_recebe'])->doesntExist()) {
            return response()->json(['message' => 'Utilizador não existe'], 404);
        }

        if($validated['id_recebe'] == $request->user()->id) {
            return response()->json(['message' => 'Não pode enviar uma mensagem para si próprio'], 403);
        }
            
        $mensagem = new Mensagem();
        $mensagem->id_envia = $request->user()->id;
        $mensagem->id_recebe = $validated['id_recebe'];
        $mensagem->mensagem = $validated['mensagem'];

        $mensagem->save();

        return response()->json(['message' => 'Mensagem enviada com sucesso'], 200);
    }

    function lerConversa(Request $request) {

        if(Utilizador::where('id', $request->id_recebe)->doesntExist()) {
            return response()->json(['message' => 'Utilizador não existe'], 404);
        }

        if( $request->id_recebe == $request->user()->id) {
            return response()->json(['message' => 'Não pode ler a sua própria conversa'], 403);
        }

        $mensagens = Mensagem::where('id_envia', $request->user()->id)
            ->where('id_recebe',  $request->id_recebe)
            ->orWhere('id_envia',  $request->id_recebe)
            ->where('id_recebe', $request->user()->id)->orderBy('created_at', 'desc')->take(50)->get();

        return response()->json(['mensagens' => $mensagens], 200);
    }

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
        })->map(function ($conversass) {
            return $conversass->first();
        })->values();

        $conversas = $conversas->map(function ($conversa) {
            $conversa['nome_envia'] = Utilizador::find($conversa['id_envia'])->nome;
            $conversa['nome_recebe'] = Utilizador::find($conversa['id_recebe'])->nome;
            return $conversa;
        })->toArray();

        return response()->json(['conversas' => $conversas], 200);
    }
    
}
