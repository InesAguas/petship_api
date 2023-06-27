<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     description="Petship API",
 *     version="0.0.1",
 *     title="Documentação da API"
 * ),
 * @OA\PathItem(
 *    path="/api",
 *   description="Rotas da API",
 * ),
 * @OA\SecurityScheme(
 *    type="http",
 *     description="Login with email and password to get the authentication token",
 *     name="Token based Based",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="token",
 * ),
 * @OA\Server(
 *    description="Petship API",
 *   url="https://api.petship.pt"
 * )
 * 
 * @OA\Tag(
     *   name="Utilizadores",
     *   description="Rotas de Utilizadores"
     * ),
     * @OA\Tag(
     *   name="Animais",
     *   description="Rotas de Animais"
     * ),
     * @OA\Tag(
     *   name="Anuncios",
     *   description="Rotas de Anuncios"
     * ),
     * @OA\Tag(
     *   name="Mensagens",
     *   description="Rotas de Mensagens"
     * ),
     * @OA\Tag(
     *   name="Stock",
     *   description="Rotas de Stock"
     * ),
 * 
 * 
 * 
 * 
 * 
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
