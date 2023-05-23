<?php

namespace App\Http\Controllers;

use App\Http\Resources\AnimalResource;
use App\Http\Resources\UtilizadorResource;
use Illuminate\Http\Request;
use App\Models\Utilizador;
use App\Models\Animal;
use App\Models\Anuncio;

class AnimalController extends Controller
{
    //

    function publicarAnimal(Request $request) {
        
        $validated = $request->validate([
            'data_recolha' => 'required|date',
            'ferido' => 'required|boolean',
            'agressivo' => 'required|boolean',
            'nome' => 'required',
            'sexo' => 'required',
            'especie' => 'required',
            'raca' => 'required',
            'porte' => 'required',
            'idade' => 'required',
            'cor' => 'required',
        ]);
        
        $animal = new Animal();
        $animal->id_utilizador = $request->user()->id;
        $animal->data_recolha = $validated['data_recolha'];
        $animal->ferido = $validated['ferido'];
        $animal->agressivo = $validated['agressivo'];
        $animal->nome = $validated['nome'];
        $animal->sexo = $validated['sexo'];
        $animal->especie = $validated['especie'];
        $animal->raca = $validated['raca'];
        $animal->porte = $validated['porte'];
        $animal->idade = $validated['idade'];
        $animal->cor = $validated['cor'];
        $animal->save();

        if($request->local_captura != null) {
            $animal->local_captura = $request->local_captura;
        }

        if($request->fotografia != null) {
            $nome_fotografia = $animal->id . $animal->nome . '.' . $request->fotografia->extension();
            $request->fotografia->move(public_path('storage/img/animais'), $nome_fotografia);
            $animal->fotografia = $nome_fotografia;
        }

        if($request->chip != null) {
            $animal->chip = $request->chip;
        }

        if($request->temperatura != null) {
            $animal->temperatura = $request->temperatura;
        }

        if($request->desparasitacao != null) {
            $animal->desparasitacao = $request->desparasitacao;
        }

        if($request->medicacao != null) {
            $animal->medicacao = $request->medicacao;
        }

        $animal->save();

        return response(['animal' => new AnimalResource($animal)], 200);
    }


    function listarAnimaisAssociacao(Request $request) {
        $animais = Animal::where('id_utilizador', $request->user()->id)->get();

        if($request->lang == 'en')  {
            return response(['animais' => AnimalResource::collection($animais)->map->toArrayEnglish()], 200);
        }
        return response(['animais' => AnimalResource::collection($animais)], 200);
    }

    function removerAnimal(Request $request) {
        $animal = Animal::where('id', $request->id)->first();
        if($animal == null) {
            return response(['erro' => 'Animal não encontrado'], 404);
        }
        if($animal->id_utilizador != $request->user()->id) {
            return response(['erro' => 'Não tem permissões para remover este animal'], 403);
        }

        Anuncio::where('id_animal', $animal->id)->delete();
        $animal->delete();
        return response(['sucesso' => 'Animal removido com sucesso'], 200);
    }

    function dadosAnimalNum(Request $request) {
        $animal = Animal::where('id', $request->id)->first();

        if($animal == null) {
            return response(['erro' => 'Animal não encontrado'], 404);
        }

        if($animal->id_utilizador != $request->user()->id) {
            return response(['erro' => 'Não tem permissões para remover este animal'], 403);
        }

        return response(['animal' => (new AnimalResource($animal))->toArrayNumeric()], 200);

    }

    function editarAnimal(Request $request) {
        $animal = Animal::where('id', $request->id)->first();

        if($animal == null) {
            return response(['erro' => 'Animal não encontrado'], 404);
        }

        if($animal->id_utilizador != $request->user()->id) {
            return response(['erro' => 'Não tem permissões para remover este animal'], 403);
        }

        $validated = $request->validate([
            'data_recolha' => 'required|date',
            'ferido' => 'required|boolean',
            'agressivo' => 'required|boolean',
            'nome' => 'required',
            'sexo' => 'required',
            'especie' => 'required',
            'raca' => 'required',
            'porte' => 'required',
            'idade' => 'required',
            'cor' => 'required',
        ]);
        
        $animal->data_recolha = $validated['data_recolha'];
        $animal->ferido = $validated['ferido'];
        $animal->agressivo = $validated['agressivo'];
        $animal->nome = $validated['nome'];
        $animal->sexo = $validated['sexo'];
        $animal->especie = $validated['especie'];
        $animal->raca = $validated['raca'];
        $animal->porte = $validated['porte'];
        $animal->idade = $validated['idade'];
        $animal->cor = $validated['cor'];
        $animal->save();

        if($request->local_captura != null) {
            $animal->local_captura = $request->local_captura;
        }

        if($request->fotografia != null) {
            $nome_fotografia = $animal->id . $animal->nome . '.' . $request->fotografia->extension();
            $request->fotografia->move(public_path('storage/img/animais'), $nome_fotografia);
            $animal->fotografia = $nome_fotografia;
        }

        if($request->chip != null) {
            $animal->chip = $request->chip;
        }

        if($request->temperatura != null) {
            $animal->temperatura = $request->temperatura;
        }

        if($request->desparasitacao != null) {
            $animal->desparasitacao = $request->desparasitacao;
        }

        if($request->medicacao != null) {
            $animal->medicacao = $request->medicacao;
        }

        $animal->save();

        return response(['animal' => new AnimalResource($animal)], 200);

    }

}
