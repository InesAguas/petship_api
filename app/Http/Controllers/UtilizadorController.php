<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utilizador;

class UtilizadorController extends Controller
{
    //
    function inserir(Request $request) {

        $utilizador = new Utilizador();

        $utilizador->nome = $request->nome;
        $utilizador->apelido = $request->apelido;
        $utilizador->save();

        return 200;
    }
}
