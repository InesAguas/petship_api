<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utilizador;
use Illuminate\Support\Facades\Hash;

class UtilizadorController extends Controller
{
    //
    function login(Request $request) {

        $user = new Utilizador();
        $user->email = "ines@email.com";
        $user->password = Hash::make("ines");
        $user->nome = "ines";
        $user->tipo = 1;

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

        return response(200);
    }

    function registar(Request $request) {

        //Validar os dados que recebo
        $validated = $request->validate([
            'nome' => 'required|string',
            'email' => 'required|string|unique:utilizador',
            'password' => 'required|string',
            'tipo' => 'required|numeric',
        ]);

        //Criar novo objeto Utilizador
        $utilizador = new Utilizador();
        $utilizador->nome = $validated['nome'];
        $utilizador->email = $validated['email'];
        $utilizador->password = Hash::make($validated['password']);
        $utilizador->tipo = $validated['tipo'];

        //Guardar na  base de dados
        $utilizador->save();
        
        return response(200);
    }
}
