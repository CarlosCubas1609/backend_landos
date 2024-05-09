<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('descripcion');
            $table->unsignedDecimal('precio', 8, 2);
            $table->foreignId('tipo_servicio_id')->references('id')->on('tipo_servicios');
            $table->unsignedDecimal('precio_oferta', 8, 2)->default(0);
            $table->enum('estado_oferta', ['ON', 'OFF'])->default('OFF');
            $table->enum('estado', ['ACTIVO', 'ANULADO'])->default('ACTIVO');           
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
        Schema::dropIfExists('servicios');
    }
}
