<?php

namespace App\Http\Controllers;

use App\Http\Resources\AnimalResource;
use Illuminate\Http\Request;
use App\Models\Utilizador;
use App\Models\Animal;

class AnimalController extends Controller
{
    //

    function anunciarAnimal(Request $request)
    {
        //funcao para inserir um animal na base de dados

        //Validar os dados que recebo
        $validated = $request->validate([
            'nome' => 'required|string',
            'sexo' => 'required|string',
            'especie' => 'required|string',
            'raca' => 'required|string',
            'porte' => 'required|string',
            'idade' => 'required|string',
            'cor' => 'required|string',
            'distrito' => 'required|string',
            'etiqueta' => 'required|string',
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

        $animal->save();

        if($request->descricao != null) {
            $animal->descricao = $request->descricao;
        }
        
        //guardar apenas o titulo de cada fotografia
        if ($request->fotografias != null) {
            $fotografias = [];
            $i = 0;
            foreach ($request->fotografias as $fotografia) {
                $i++;
                $nome_fotografia = $animal->id . $animal->nome . $i . '.' . $fotografia->extension();
                $fotografias[] = $nome_fotografia;
                $fotografia->move(public_path('img/animais'), $nome_fotografia);
            }
            $animal->fotografias = json_encode($fotografias);
        }
        //Guardar na  base de dados
        $animal->save();

        return response(['sucesso' => 'Animal inserido com sucesso'], 200);
    }

    function listarAnimais(Request $request)
    {
        $animais = Animal::where('etiqueta', 'Adoção')->get();

        return response(['animais' => AnimalResource::collection($animais)], 200);
    }
}
