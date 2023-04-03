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
            $table->string('nome');
            $table->integer('sexo');
            $table->integer('especie');
            $table->integer('raca');
            $table->integer('porte');
            $table->integer('idade');
            $table->integer('cor');
            $table->integer('distrito');
            $table->integer('etiqueta');
            $table->string('descricao');
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
