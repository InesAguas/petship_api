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
        Schema::create('traducoes', function (Blueprint $table) {
            $table->integer('id');
            $table->string('tipo');
            $table->string('pt');
            $table->string('en');

            $table->primary(['id', 'tipo']);
            $table->index(['id', 'tipo']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('traducoes');
    }
};
