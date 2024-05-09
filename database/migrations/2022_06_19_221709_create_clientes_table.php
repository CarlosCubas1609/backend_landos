<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_documento_id')->references('id')->on('tipo_documentos');
            $table->string('documento')->nullable();
            $table->string('nombres')->nullable();
            $table->string('apellidos')->nullable();
            $table->string('celular')->nullable();
            $table->string('email')->nullable();
            $table->string('direccion')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->foreignId('genero_id')->references('id')->on('generos');
            $table->enum('estado',['ACTIVO', 'ANULADO'])->default('ACTIVO');
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
        Schema::dropIfExists('clientes');
    }
}
