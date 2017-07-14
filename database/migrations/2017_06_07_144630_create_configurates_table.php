<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfiguratesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configurates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('banner_largura_max_recomendada')->nullable();
            $table->integer('banner_altura_max_recomendada')->nullable();
            $table->enum('noticia_fotos_destaque', ['Sim','Não'])->default('Sim');
            $table->enum('noticia_categoria', ['Sim','Não'])->default('Não');
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
        Schema::dropIfExists('configurates');
    }
}
