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
 *   description="All the routes",
 * ),
 * @OA\Server(
 *    description="Petship API",
 *   url="http://api.petship.pt"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
