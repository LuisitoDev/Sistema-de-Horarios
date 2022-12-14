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
        Schema::create('entradas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('hora_entrada_programada')->nullable();
            $table->timestamp('hora_salida_programada')->nullable();
            $table->decimal('horas_realizadas_programada', 5, 3);
            $table->timestamp('hora_entrada')->useCurrent()->nullable();
            $table->timestamp('hora_salida')->nullable();
            $table->decimal('horas_realizadas', 5, 3);
            $table->text('reporte_diario');

            $table->tinyInteger('id_status')
                ->references('id')->on('status')
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
        Schema::dropIfExists('entradas');
    }
};
