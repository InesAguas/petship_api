<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animal;
use App\Models\Anuncio;
use App\Models\Utilizador;

use App\Http\Resources\AnuncioResource;
use App\Http\Resources\UtilizadorResource;



class AnuncioController extends Controller
{
    //
    function novoAnuncio(Request $request)
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
        $animal->id_utilizador = $request->user()->id;
        $animal->nome = $validated['nome'];
        $animal->sexo = $validated['sexo'];
        $animal->especie = $validated['especie'];
        $animal->raca = $validated['raca'];
        $animal->porte = $validated['porte'];
        $animal->idade = $validated['idade'];
        $animal->cor = $validated['cor'];

        $animal->save();


        $anuncio = new Anuncio();
        $anuncio->id_animal = $animal->id;
        $anuncio->id_utilizador = $request->user()->id;
        $anuncio->distrito = $validated['distrito'];
        $anuncio->etiqueta = $validated['etiqueta'];

        $anuncio->save();

        if($request->descricao != null) {
            $anuncio->descricao = $request->descricao;
        }
        
        //guardar apenas o titulo de cada fotografia
        if ($request->fotografias != null) {
            $fotografias = [];
            $i = 0;
            foreach ($request->fotografias as $fotografia) {
                $i++;
                $nome_fotografia = $animal->id . $animal->nome . $i . '.' . $fotografia->extension();
                $fotografias[] = $nome_fotografia;
                $fotografia->move(public_path('storage/img/animais'), $nome_fotografia);
            }
            $anuncio->fotografias = json_encode($fotografias);
        }
        //Guardar na  base de dados
        $anuncio->save();

        return response(['sucesso' => 'Animal anunciado com sucesso'], 200);
    }


    public function anunciarAnimal(Request $request) {

    }


    function listarAnimaisAdocao(Request $request)
    {
        $anuncios = Anuncio::where('etiqueta', 1)->get();

        if($request->lang == 'en')  {
            
            return response(['animais' => AnuncioResource::collection($anuncios)->map->toArrayEnglish()], 200);
        }
        return response(['animais' => AnuncioResource::collection($anuncios)], 200);
    }

    function listarAnimaisDesaparecidos(Request $request)
    {
        $anuncios = Anuncio::where('etiqueta', 2)->get();

        if($request->lang == 'en')  {
            
            return response(['animais' => AnuncioResource::collection($anuncios)->map->toArrayEnglish()], 200);
        }
        return response(['animais' => AnuncioResource::collection($anuncios)], 200);
    }

    function listarAnimaisPetsitting(Request $request)
    {

        $anuncios = Anuncio::where('etiqueta', 3)->get();

        if($request->lang == 'en')  {
            
            return response(['animais' => AnuncioResource::collection($anuncios)->map->toArrayEnglish()], 200);
        }
        return response(['animais' => AnuncioResource::collection($anuncios)], 200);
    }

    function verAnuncioAnimal(Request $request, $id) {
        $anuncio = Anuncio::find($id);

        if($anuncio == null) {
            return response(['erro' => 'Anuncio não encontrado'], 404);
        }

        $utilizador = Utilizador::find($anuncio->id_utilizador);

        if ($request->lang == 'en') {

            return response(['animal' => new AnuncioResource($anuncio->toArrayEnglish()), 'utilizador' => new UtilizadorResource($utilizador->toArray())], 200);
        }

        return response(['animal' => new AnuncioResource($anuncio), 'utilizador' => new UtilizadorResource($utilizador)], 200);

    }
    
}
