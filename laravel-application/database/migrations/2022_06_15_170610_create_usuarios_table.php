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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('nombre');
            $table->string('apellido_pat');
            $table->string('apellido_mat');
            $table->string('matricula')->unique();
            $table->string('correo_universitario')->unique();
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->boolean('estado')->default(1);

            $table->bigInteger('id_carrera')
                ->references('id')->on('carreras')
                ->onDelete('cascade');
            $table->bigInteger('id_ciclo_escolar')
                ->references('id')->on('ciclo_escolar')
                ->onDelete('cascade');
            $table->bigInteger('id_rotacion')
                ->references('id')->on('rotaciones')
                ->onDelete('cascade');
        });

        DB::statement("ALTER TABLE usuarios ADD imagen MEDIUMBLOB NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
};
