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
        Schema::create('usuarios_programas', function (Blueprint $table) {
            $table->id('id_usuario')
                ->references('id')->on('usuarios');

            $table->timestamps();
            $table->softDeletes();

            $table->bigInteger('id_programa')
                ->references('id')->on('programas')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuarios_programas');
    }
};
