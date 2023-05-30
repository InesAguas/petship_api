<?php

namespace App\Http\Controllers;

use App\Http\Resources\UtilizadorResource;
use Illuminate\Http\Request;
use App\Models\Utilizador;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\Registered;

class UtilizadorController extends Controller
{
    //
    function login(Request $request)
    {

        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $utilizador = Utilizador::where('email', $request->email)->first();

        if ($utilizador == null) {
            return response(['erro' => 'Email ou password incorretos'], 422);
        }

        if (!Hash::check(($request->password), $utilizador->password)) {
            return response(['erro' => 'Email ou password incorretos'], 422);
        }

        if (!$utilizador->hasVerifiedEmail()) {
            return response(['erro' => 'Email não verificado'], 403);
        }

        //apaga tokens anteriores e cria um novo
        $utilizador->tokens()->delete();
        $token = $utilizador->createToken($utilizador->email);

        //retorna o token
        return response(['utilizador' => new UtilizadorResource($utilizador), 'token' => $token->plainTextToken], 200);
    }

    function registar(Request $request)
    {

        //Validar os dados que recebo
        $validated = $request->validate([
            'nome' => 'required|string',
            'email' => 'required|email|unique:utilizadores',
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

        Event(new Registered($utilizador));

        return response(['sucesso' => 'Registo realizado com sucesso'], 200);
    }

    function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response(['sucesso' => 'Logout realizado com sucesso'], 200);
    }

    function listarAssociacoes(Request $request)
    {
        //return todos os utilizadores que sao do tipo 2
        $utilizadores = Utilizador::where('tipo', 2)->get();
        return response(['associacoes' => UtilizadorResource::collection($utilizadores)], 200);
    }

    function perfilUtilizador(Request $request)
    {
        $utilizador = Utilizador::where('id', $request->id)->first();
        if ($utilizador == null)
            return response(['erro' => 'Utilizador não encontrado'], 404);
        return response(['utilizador' => new UtilizadorResource($utilizador)], 200);
    }

    function alterarPerfil(Request $request)
    {   
        $utilizador =  $request->user();
        if ($utilizador == null)
            return response(['erro' => 'Utilizador não encontrado'], 404);

        //Validar os dados que recebo
        $validated = $request->validate([
            'nome' => 'required|string',
            'email' => 'required|email|unique:utilizadores,email,' . $utilizador->id,
            'telefone' => 'string',
            'fotografia' => 'file',
            'localizacao' => 'string'
        ]);

        $utilizador->nome = $validated['nome'];
        $utilizador->email = $validated['email'];

        if ($request->telefone != null) {
            $utilizador->telefone = $request->telefone;
        }

        if($request->localizacao != null){
            $utilizador->localizacao = $request->localizacao;
        }

        if ($request->fotografia != null) {
            $nomeFotografia = $utilizador->id . $utilizador->nome . '.' . $request->fotografia->extension();
            $request->fotografia->move(public_path('storage/img/utilizadores/'), $nomeFotografia);
            $utilizador->fotografia = $nomeFotografia;
        }
        //Guardar na  base de dados
        $utilizador->save();

        return response(['utilizador' => new UtilizadorResource($utilizador)], 200);
    }

    function alterarPerfilAssociacao(Request $request){
        $utilizador =  $request->user();
        if ($utilizador == null)
            return response(['erro' => 'Utilizador não encontrado'], 404);

        //Validar os dados que recebo
        $validated = $request->validate([
            'nome' => 'required|string',
            'email' => 'required|email|unique:utilizadores,email,' . $utilizador->id,
            'telefone' => 'string',
            'fotografia' => 'file',
            'localizacao' => 'string',
            'website' => 'string',
            'facebook' => 'string',
            'instagram' => 'string',
            'horario' => 'json'
        ]);

        $utilizador->nome = $validated['nome'];
        $utilizador->email = $validated['email'];

        if ($request->telefone != null) {
            $utilizador->telefone = $request->telefone;
        }

        if($request->localizacao != null){
            $utilizador->localizacao = $request->localizacao;
        }

        if ($request->fotografia != null) {
            $nomeFotografia = $utilizador->id . $utilizador->nome . '.' . $request->fotografia->extension();
            $request->fotografia->move(public_path('storage/img/utilizadores/'), $nomeFotografia);
            $utilizador->fotografia = $nomeFotografia;
        }

        if($request->website != null){
            $utilizador->website = $request->website;
        }

        if($request->facebook != null){
            $utilizador->facebook = $request->facebook;
        }

        if($request->instagram != null){
            $utilizador->instagram = $request->instagram;
        }

        if($request->horario != null){
            $utilizador->horario = $request->horario;
        }
        

        //Guardar na  base de dados
        $utilizador->save();

        return response(['utilizador' => new UtilizadorResource($utilizador)], 200);
    }


    function forgotPassword(Request $request) {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return  __($status);
    }

    function resetPassword(Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        return  __($status);
    }

    function verificaEmail(Request $request) {
        $utilizador = Utilizador::where('id', $request->id)->first();

        if (hash_equals(sha1($utilizador->getEmailForVerification()), $request->hash)) {
            $utilizador->markEmailAsVerified();
            return redirect('http://localhost:8080/login')->with('success', 'Email verificado com sucesso');
        }

       abort(404, 'Email não verificado');
    }


    //Eliminar conta e todos os dados associados
    function eliminarConta(Request $request){
       $utilizador = Utilizador::where('id', $request->id)->first();
         if($utilizador == null)
                return response(['erro' => 'Utilizador não encontrado'], 404);
    
          $utilizador->delete();
          return response(['sucesso' => 'Conta eliminada com sucesso'], 200);
    }
}
