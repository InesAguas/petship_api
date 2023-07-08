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
        Schema::create('anuncios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_utilizador')->constrained('utilizadores');
            $table->foreignId('id_animal')->constrained('animais');
            $table->string('distrito', 30);
            $table->string('etiqueta', 30);
            $table->string('descricao', 300)->nullable();
            $table->string('estado', 30)->nullable();
            $table->json('fotografias')->nullable();
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
        Schema::dropIfExists('anuncios');
    }
};
