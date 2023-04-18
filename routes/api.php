<?php

use App\Http\Controllers\UtilizadorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnimalController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [UtilizadorController::class, 'login']);
Route::post('registar',[UtilizadorController::class, 'registar']);

Route::post('anunciaranimal', [AnimalController::class, 'anunciarAnimal']);

Route::get('associacoes', [UtilizadorController::class, 'listarAssociacoes']);

Route::get('adotar', [AnimalController::class, 'listarAnimais']);

Route::get('desaparecido', [AnimalController::class, 'listarAnimaisDesaparecidos']);

Route::get('petsitting', [AnimalController::class, 'listarAnimaisPetsitting']);

Route::get('perfil/{id}', [UtilizadorController::class, 'perfilUtilizador']);
