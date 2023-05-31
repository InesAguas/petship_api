<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;

use App\Http\Resources\StockResource;

class StockController extends Controller
{
    //Adicionar stock
    function adicionarStock(Request $request)
    {
        //Validar os dados que recebo
        $validated = $request->validate([
            'nome' => 'required|string',
            'descricao' => 'required|string',
            'qnt_atual' => 'required|numeric',
            'qnt_min' => 'required|numeric',
            'observacoes' => 'required|string',
        ]);

        //Criar novo objeto Stock
        $stock = new Stock();
        $stock->nome = $validated['nome'];
        $stock->descricao = $validated['descricao'];
        $stock->qnt_atual = $validated['qnt_atual'];
        $stock->qnt_min = $validated['qnt_min'];
        $stock->observacoes = $validated['observacoes'];

        //Guardar na BD
        $stock->save();

        //Retornar o stock
        return response(new StockResource($stock), 201);
    }
}
