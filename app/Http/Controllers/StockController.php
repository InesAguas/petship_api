<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\StockResource;

class StockController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/adicionarstock",
     *     security={{ "token": {} }},
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
     *         response=200,
     *         description="Produto do stock adicionado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="produto", ref="#/components/schemas/Stock"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Erro de validação", example="O campo nome é obrigatório."),
     *         )
     *     ),
     * @OA\Response(
     *        response=401,
     *       description="Não autorizado",
     *      @OA\JsonContent(
     *         @OA\Property(property="message", type="string", description="Não autorizado", example="Unauthenticated."),
     *     )
     *   ),
     * )
     */
    function adicionarStock(Request $request)
    {
        //Validar os dados que recebo
        $validated = $request->validate([
            'nome' => 'required|string',
            'descricao' => 'required|string',
            'qnt_atual' => 'required|numeric|gte:0',
            'qnt_min' => 'required|numeric|gte:0',
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
        return response(['produto' => new StockResource($stock)], 200);
    }


    /**
     * @OA\Get(
     *     path="/api/utilizador/stock",
     *     security={{ "token": {} }},
     *     summary="Listar Stock de um determinado Utilizador",
     *     description="Lista todos os produtos de stock pertencentes a um determinado utilizador.",
     *     tags={"Stock"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de produtos do utilizador",
     *         @OA\JsonContent(
     *             @OA\Property(property="stocks", ref="#/components/schemas/Stock"),
     *         )
     *     ),
     *    @OA\Response(
     *        response=401,
     *       description="Não autorizado",
     *      @OA\JsonContent(
     *         @OA\Property(property="message", type="string", description="Não autorizado", example="Unauthenticated."),
     *     )
     *   ),
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
 *     security={{ "token": {} }},
 *     summary="Remover Stock",
 *     description="Remove um produto do stock.",
 *     tags={"Stock"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID do produto do stock a ser removido",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Produto do stock removido com sucesso",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", description="Sucesso", example="Produto do stock removido com sucesso"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Produto do stock não encontrado",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", description="Stock não encontrado.", example="Stock não encontrado."),
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Não autorizado",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", description="Não autorizado", example="Unauthenticated."),
 *         )
 *     ),
 * )
 */
    function removerStock(Request $request)
    {
        $stock = Stock::where('id', $request->id)->first();

        if ($stock == null) {
            return response(['message' => __('custom.stock_nao_encontrado')], 404);
        }

        $stock->delete();

        return response(['message' => __('custom.stock_removido')], 200);
    }

    /**
 * @OA\Post(
 *     path="/api/editarstock/{id}",
 *     security={{ "token": {} }},
 *     summary="Editar Stock",
 *     description="Atualiza a quantidade atual de um produto do stock.",
 *     tags={"Stock"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID do produto do stock a ser editado",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
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
 *         description="Produto do stock removido com sucesso",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", description="Sucesso", example="Produto do stock removido com sucesso"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Produto do stock não encontrado",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", description="Stock não encontrado.", example="Stock não encontrado."),
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Não autorizado",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", description="Não autorizado", example="Unauthenticated."),
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Erro de validação",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", description="Erro de validação", example="O campo qnt_atual é obrigatório."),
 *         )
 *     )
 * )
 */
    function editarStock(Request $request)
    {
        $stock = Stock::where('id', $request->id)->first();

        if ($stock == null) {
            return response(['message' => __('custom.stock_nao_encontrado')], 404);
        }

        $validated = $request->validate([
            'qnt_atual' => 'required|numeric|gte:0',
        ]);


        $stock->qnt_atual = $validated['qnt_atual'];

        $stock->save();

        return response(['produto' => new StockResource($stock)], 200);
    }


    /**
     * @OA\Get(
     *     path="/api/stock/notificacoes",
     *     security={{ "token": {} }},
     *     summary="Stocks do utilizador que estão abaixo do stock mínimo",
     *     description="Retorna os stocks do utilizador que estão abaixo do stock mínimo.",
     *     tags={"Stock"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de produtos do utilizador",
     *         @OA\JsonContent(
     *             @OA\Property(property="stocks", ref="#/components/schemas/Stock"),
     *         )
     *     ),
     *    @OA\Response(
     *        response=401,
     *       description="Não autorizado",
     *      @OA\JsonContent(
     *         @OA\Property(property="message", type="string", description="Não autorizado", example="Unauthenticated."),
     *     )
     *   ),
     * )
     */
    function obterNotificacoes(Request $request) {
        $stock = Stock::where('u_id', $request->user()->id)->where('qnt_atual', '<=', function($query) use(&$request){
            $query->select(DB::raw("qnt_min FROM stock a WHERE a.id = stock.id"));    
        })->orderBy('id', 'desc')->get();

        if($stock == null) {
            return response(['message' => __('custom.stock_nao_encontrado')], 404);
        }

        return response(['stocks' => StockResource::collection($stock)], 200);
    }
}