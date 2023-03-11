<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('solicitudes_usuarios', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('nombre_usuario');
            $table->string('apellido_pat_usuario');
            $table->string('apellido_mat_usuario');
            $table->string('matricula_usuario')->unique();
            $table->string('correo_universitario_usuario')->unique();
            $table->string('direccion_mac_dispositivo');

            $table->bigInteger('id_carrera_usuario')
                ->references('id')->on('carreras')
                ->onDelete('cascade');
            $table->bigInteger('id_servicio_usuario')
                ->references('id')->on('servicio')
                ->onDelete('cascade');
            $table->bigInteger('id_programa_usuario')
                ->references('id')->on('programas')
                ->onDelete('cascade')
                ->nulleable();
            });

        DB::statement("ALTER TABLE solicitudes_usuarios ADD imagen_usuario MEDIUMBLOB NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solicitudes_usuarios');
    }
};
