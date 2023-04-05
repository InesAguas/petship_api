<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animais', function (Blueprint $table) {
            $table->id();
            //para quando estiver a funcionar o login
            //$table->foreignId('id_utilizador')->constrained('utilizadores');
            $table->string('nome', 30);
            $table->string('sexo', 30);
            $table->string('especie', 30);
            $table->string('raca', 30);
            $table->string('porte', 30);
            $table->string('idade', 30);
            $table->string('cor', 30);
            $table->string('distrito', 30);
            $table->string('etiqueta', 30);
            $table->string('descricao', 30);
            $table->json('fotografias');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('animais');
    }
};
