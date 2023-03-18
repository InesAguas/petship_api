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
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $utilizador = Utilizador::where('email', $request->email)->first();

        if($utilizador == null) {
            return response(['erro' => 'Email ou password incorretos'], 422);
        }

        if (!Hash::check(($request->password), $utilizador->password)) {
            return response(['erro' => 'Email ou password incorretos'], 422);
		}

        //apaga tokens anteriores e cria um novo
        $utilizador->tokens()->delete();
        $token = $utilizador->createToken($utilizador->email);
        
        //retorna o token
        return response(['token' => $token->plainTextToken], 200);
    }

    function registar(Request $request) {

        //Validar os dados que recebo
        $validated = $request->validate([
            'nome' => 'required|string',
            'email' => 'required|email|unique:utilizador',
            'password' => 'required|string|min:8',
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
        
        return response(['sucesso' => 'Registo realizado com sucesso'], 200);
    }
}
