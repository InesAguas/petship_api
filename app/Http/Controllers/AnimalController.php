<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utilizador;
use App\Models\Animal;

class AnimalController extends Controller
{
    //

    function anunciarAnimal(Request $request) {
        //funcao para inserir um animal na base de dados

        //Validar os dados que recebo
        $validated = $request->validate([
            'nome' => 'required|string',
            'sexo' => 'required|numeric',
            'especie' => 'required|numeric',
            'raca' => 'required|numeric',
            'porte' => 'required|numeric',
            'idade' => 'required|numeric',
            'cor' => 'required|numeric',
            'distrito' => 'required|numeric',
            'etiqueta' => 'required|numeric',
            'descricao' => 'required|string',
            'fotografias' => 'required',
        ]);

        //Criar novo objeto Animal
        $animal = new Animal();
        $animal->nome = $validated['nome'];
        $animal->sexo = $validated['sexo'];
        $animal->especie = $validated['especie'];
        $animal->raca = $validated['raca'];
        $animal->porte = $validated['porte'];
        $animal->idade = $validated['idade'];
        $animal->cor = $validated['cor'];
        $animal->distrito = $validated['distrito'];
        $animal->etiqueta = $validated['etiqueta'];
        $animal->descricao = $validated['descricao'];

        //guardar apenas o titulo de cada fotografia
        $fotografias = [];
        foreach($validated['fotografias'] as $fotografia) {
            $fotografias[] = $fotografia->getClientOriginalName();
        }
        $animal->fotografias = json_encode($fotografias);

        //Guardar na  base de dados
        $animal->save();

        return response(['sucesso' => 'Animal inserido com sucesso'], 200);
    }
}
