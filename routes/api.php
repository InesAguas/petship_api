<?php

use App\Http\Controllers\UtilizadorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\MensagemController;

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

//rotas que o utilizador tem de estar logged in e ser particular
Route::middleware(['auth:sacntum', 'particular'])->group(function () {
   
});

//rotas em que o utilizador tem de estar logged in e ser associacao
Route::middleware(['auth:sacntum', 'associacao'])->group(function () {
    
});

//rotas em que o utilizador apenas tem de estar logged in
Route::middleware('auth:sanctum')->group(function() {
    Route::post('anunciaranimal', [AnimalController::class, 'anunciarAnimal']);
    Route::post('enviarmensagem', [MensagemController::class, 'enviarMensagem']);
    Route::get('mensagens/{id_recebe}', [MensagemController::class, 'lerConversa']);
    Route::get('conversasativas', [MensagemController::class, 'conversasAtivas']);
});


Route::post('login', [UtilizadorController::class, 'login']);
Route::post('registar',[UtilizadorController::class, 'registar']);

Route::get('perfil/{id}', [UtilizadorController::class, 'perfilUtilizador']);

Route::get('associacoes', [UtilizadorController::class, 'listarAssociacoes']);

Route::get('adotar', [AnimalController::class, 'listarAnimais']);

Route::get('desaparecido', [AnimalController::class, 'listarAnimaisDesaparecidos']);

Route::get('petsitting', [AnimalController::class, 'listarAnimaisPetsitting']);

Route::get('animal/{id}', [AnimalController::class, 'listarAnimal']);
