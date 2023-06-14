<?php

namespace App\Http\Controllers;

use App\Http\Resources\CandidaturaResource;
use Illuminate\Http\Request;
use App\Models\Candidatura;
use App\Models\Anuncio;

class CandidaturaController extends Controller
{
    function inserirCandidatura(Request $request)
    {

        //Select anuncio com o id do animal que está a ser candidatado

        $validated = $request->validate([
            'id_anuncio' => 'required',
            'id_utilizador' => 'required',
            'cc' => 'required',
            'estado' => 'required'
        ]);

        $candidatura = new Candidatura();
        $candidatura->id_anuncio = $request->id_anuncio;
        $candidatura->id_utilizador = $request->id_utilizador;
        $candidatura->cc = $validated['cc'];
        $candidatura->estado = true;

        $candidatura->save();

        return response(['candidatura' => new CandidaturaResource($candidatura)], 200);
    }
}
