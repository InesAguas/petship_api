<?php

use App\Http\Controllers\UtilizadorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\MensagemController;
use App\Http\Controllers\AnuncioController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\CandidaturaController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

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
Route::middleware(['auth:sanctum', 'associacao'])->group(function () {
    Route::post('publicaranimal', [AnimalController::class, 'publicarAnimal']);
    Route::get('associacao/animais', [AnimalController::class, 'listarAnimaisAssociacao']);
    Route::delete('removeranimal/{id}', [AnimalController::class, 'removerAnimal']);
    Route::post('editaranimal/{id}', [AnimalController::class, 'editarAnimal']);
    Route::get('associacao/animal/num/{id}', [AnimalController::class, 'dadosAnimalNum']);
    Route::post('adicionarstock',[StockController::class, 'adicionarStock']);
    Route::get('stock/notificacoes', [StockController::class, 'obterNotificacoes']);
    Route::post('candidatura/aceitar/{id}', [CandidaturaController::class, 'aceitarCandidatura']);
    Route::post('candidatura/concluir/{id}', [CandidaturaController::class, 'concluirCandidatura']);
   
});

//rotas em que o utilizador apenas tem de estar logged in
Route::middleware('auth:sanctum')->group(function() {
    Route::post('novoanuncio', [AnuncioController::class, 'novoAnuncio']);
    Route::post('enviarmensagem', [MensagemController::class, 'enviarMensagem']);
    Route::get('mensagens/{id_recebe}', [MensagemController::class, 'lerConversa']);
    Route::get('conversasativas', [MensagemController::class, 'conversasAtivas']);
    Route::post('editarperfil', [UtilizadorController::class, 'alterarPerfil']);
    Route::post('editarperfilA', [UtilizadorController::class, 'alterarPerfilAssociacao']);
    Route::get('utilizador/anuncios', [AnuncioController::class, 'listarAnunciosUtilizador']);
    Route::delete('removeranuncio/{id}', [AnuncioController::class, 'removerAnuncio']);
    Route::get('anuncio/num/{id}', [AnuncioController::class, 'dadosAnuncioNum']);
    Route::post('editaranuncio/{id}', [AnuncioController::class, 'editarAnuncio']);
    Route::delete('eliminarconta/{id}', [UtilizadorController::class, 'eliminarConta']);
    Route::post('anuncio/estado/{id}', [AnuncioController::class, 'alterarEstadoAnuncio']);
    Route::get('utilizador/stock', [StockController::class, 'listarStockUtilizador']);
    Route::delete('removerstock/{id}', [StockController::class, 'removerStock']);
    Route::post('editarstock/{id}', [StockController::class, 'editarStock']);
    Route::post('logout', [UtilizadorController::class, 'logout']);
    Route::post('candidaturainserir', [CandidaturaController::class, 'inserirCandidatura']);
    Route::get('dashboard/candidaturas', [CandidaturaController::class, 'listarCandidaturas']);
    Route::post('candidatura/cancelar/{id}', [CandidaturaController::class, 'cancelarCandidatura']);
});


Route::post('login', [UtilizadorController::class, 'login']);
Route::post('registar',[UtilizadorController::class, 'registar']);

Route::get('perfil/{id}', [UtilizadorController::class, 'perfilUtilizador']);

Route::get('associacoes', [UtilizadorController::class, 'listarAssociacoes']);

Route::get('adotar', [AnuncioController::class, 'listarAnimaisAdocao']);

Route::get('desaparecido', [AnuncioController::class, 'listarAnimaisDesaparecidos']);

Route::get('petsitting', [AnuncioController::class, 'listarAnimaisPetsitting']);

Route::get('animal/{id}', [AnuncioController::class, 'verAnuncioAnimal']);

Route::post('/forgot-password', [UtilizadorController::class, 'forgotPassword'])->middleware('guest')->name('password.email');
Route::post('/reset-password', [UtilizadorController::class, 'resetPassword'])->middleware('guest')->name('password.update');


//rotas dos emails

Route::get('/email/verify/{id}/{hash}', [UtilizadorController::class, 'verificaEmail'])->name('verification.verify');

