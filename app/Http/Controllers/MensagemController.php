<?php

namespace App\Http\Controllers;
use App\Models\Mensagem;
use App\Models\Utilizador;

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

        $recebidas = $utilizador->mensagensRecebidas->pluck('id_envia')->unique();
        $enviadas = $utilizador->mensagensEnviadas->pluck('id_recebe')->unique();

        $conversas = $recebidas->merge($enviadas)->unique()->toArray();

        $conversas = array_values($conversas);

        return response()->json(['conversas' => $conversas], 200);
    }
    
}
