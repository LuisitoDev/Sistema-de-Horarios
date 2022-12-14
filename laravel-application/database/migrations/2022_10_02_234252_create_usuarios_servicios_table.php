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
        Schema::create('usuarios_servicios', function (Blueprint $table) {
            $table->bigInteger('id_usuario')
            ->references('id')->on('usuarios');

            $table->timestamps();
            $table->softDeletes();

            $table->bigInteger('id_servicio')
                ->references('id')->on('servicios');

            $table->primary(['id_usuario', 'id_servicio']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuarios_servicios');
    }
};
