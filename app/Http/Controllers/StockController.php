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
            'observacoes' => 'nullable|string',
        ]);

        //Criar novo objeto Stock
        $stock = new Stock();
        $stock->u_id = $request->user()->id;
        $stock->nome = $validated['nome'];
        $stock->descricao = $validated['descricao'];
        $stock->qnt_atual = $validated['qnt_atual'];
        $stock->qnt_min = $validated['qnt_min'];
        $stock->observacoes = $validated['observacoes'];

        //Guardar na BD
        $stock->save();

        //Retornar o stock
        return response(['produto' => new StockResource($stock)], 201);
    }

    function listarStockUtilizador(Request $request)
    {
        //Listar todos os stocks de um utilizador
        $stocks = Stock::where('u_id', $request->user()->id)->get();

        //Retornar os stocks
        return response(['stocks' => StockResource::collection($stocks)], 200);
    }

    function removerStock(Request $request)
    {
        $stock = Stock::where('id',$request->id)->first();

        if($stock == null) {
            return response(['message' => 'Stock não encontrado'], 404);
        }


        $stock->delete();

        return response(['message' => 'Stock removido com sucesso'], 200);
    }

    function editarStock(Request $request)
    {
        $stock = Stock::where('id',$request->id)->first();

        if($stock == null) {
            return response(['message' => 'Stock não encontrado'], 404);
        }

        $validated = $request->validate([
            'qnt_atual' => 'required|numeric',
        ]);

      
        $stock->qnt_atual = $validated['qnt_atual'];

        $stock->save();

        return response(['produto' => new StockResource($stock)], 200);
    }
}
