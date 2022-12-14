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
        Schema::create('rotaciones', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->timestamps();
            $table->softDeletes();
            $table->boolean('lunes_presencial')->default(0);
            $table->boolean('martes_presencial')->default(0);
            $table->boolean('miercoles_presencial')->default(0);
            $table->boolean('jueves_presencial')->default(0);
            $table->boolean('viernes_presencial')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rotaciones');
    }
};
