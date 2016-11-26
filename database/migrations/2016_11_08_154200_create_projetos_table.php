<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjetosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projetos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome')->unique();
            $table->string('aluno')->unique();
            $table->integer('orientador_id');
            $table->integer('curso_id');
            $table->string('area_primaria');
            $table->string('area_secundaria');
            $table->text('resumo');
            $table->date('data');
            $table->dateTime('hora')->unique();
            $table->string('sala');

            $table->integer('examinador_1')->nullable();
            $table->integer('examinador_2')->nullable();

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
        Schema::dropIfExists('projetos');
    }
}
