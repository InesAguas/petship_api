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
            $table->foreignId('id_utilizador')->constrained('utilizadores');
            $table->string('nome', 30);
            $table->string('sexo', 30);
            $table->string('especie', 30);
            $table->string('raca', 30);
            $table->string('porte', 30);
            $table->string('idade', 30);
            $table->string('cor', 30);
            $table->boolean('ferido')->nullable();
            $table->boolean('agressivo')->nullable();
            $table->date('data_recolha')->nullable();
            $table->string('local_captura', 30)->nullable();
            $table->string('fotografia', 50)->nullable();
            $table->integer('chip')->nullable();
            $table->integer('temperatura')->nullable();
            $table->date('desparasitacao')->nullable();
            $table->string('medicacao', 50)->nullable();
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
