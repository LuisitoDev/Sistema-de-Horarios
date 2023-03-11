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
        Schema::create('tarea_ejecucion', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->timestamp('hora_ejecucion')->useCurrent();
            $table->integer('id_tarea_programada');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tarea_ejecucion');
    }
};
