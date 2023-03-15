<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utilizador;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UtilizadorController extends Controller
{
    //
    function login(Request $request) {

        $validated = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $utilizador = Utilizador::where('email', $request->email)->first();

        if($utilizador == null) {
            return response('cenas', 400);
        }

        if (!Hash::check(($request->password), $utilizador->password)) {
            return response('cenas2', 401);
		}

        $token = $utilizador->createToken($utilizador->email);
        return response(['token' => $token->plainTextToken], 200);
    }

    function inserir(Request $request) {

        /*$utilizador = new Utilizador();

        $utilizador->nome = $request->nome;
        $utilizador->apelido = $request->apelido;
        $utilizador->save();*/

        return response(200);
    }
}
