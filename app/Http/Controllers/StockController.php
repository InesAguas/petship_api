<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;

use App\Http\Resources\StockResource;

class StockController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/adicionarstock",
     *     summary="Adicionar Stock",
     *     description="Adiciona um novo produto de stock.",
     *     tags={"Stock"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nome", type="string", example="Produto A"),
     *             @OA\Property(property="descricao", type="string", example="Descrição do produto A"),
     *             @OA\Property(property="qnt_atual", type="number", example=10),
     *             @OA\Property(property="qnt_min", type="number", example=5),
     *             @OA\Property(property="observacoes", type="string", nullable=true, example="Observações do produto A")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Produto do stock adicionado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="produto", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro na validação dos dados de entrada"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/utilizador/stock",
     *     summary="Listar Stock de um determinado Utilizador",
     *     description="Lista todos os produtos de stock pertencentes a um determinado utilizador.",
     *     tags={"Stock"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de produtos do utilizador",
     *         @OA\JsonContent(
     *             @OA\Property(property="stocks", type="array")
     *         )
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    function listarStockUtilizador(Request $request)
    {
        //Listar todos os stocks de um utilizador
        $stocks = Stock::where('u_id', $request->user()->id)->orderBy('id', 'desc')->get();

        //Retornar os stocks
        return response(['stocks' => StockResource::collection($stocks)], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/removerstock/{id}",
     *     summary="Remover Stock",
     *     description="Remove um produto do stock.",
     *     tags={"Stock"},
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="ID do produto do stock a ser removido",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produto do stock removido com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produto do stock não encontrado"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    function removerStock(Request $request)
    {
        $stock = Stock::where('id', $request->id)->first();

        if ($stock == null) {
            return response(['message' => 'Stock não encontrado'], 404);
        }

        $stock->delete();

        return response(['message' => 'Stock removido com sucesso'], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/editarstock/{id}",
     *     summary="Editar Stock",
     *     description="Atualiza a quantidade atual de um produto do stock.",
     *     tags={"Stock"},
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="ID do produto do stock a ser editado",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="qnt_atual", type="number", example=20)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produto do stock atualizado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="produto", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro na validação dos dados de entrada"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produto do stock não encontrado"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    function editarStock(Request $request)
    {
        $stock = Stock::where('id', $request->id)->first();

        if ($stock == null) {
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
