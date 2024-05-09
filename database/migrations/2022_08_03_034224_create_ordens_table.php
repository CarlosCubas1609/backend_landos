<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordens', function (Blueprint $table) {
            $table->id();
            $table->string('cliente');
            $table->foreignId('cliente_id')->references('id')->on('clientes');
            $table->string('telefono')->nullable();
            $table->string('vehiculo');
            $table->foreignId('vehiculo_id')->references('id')->on('vehiculos');
            $table->string('placa');
            $table->string('servicio');
            $table->foreignId('servicio_id')->references('id')->on('servicios');
            $table->unsignedDecimal('total', 10, 2);
            $table->unsignedDecimal('descuento', 10, 2)->default(0);
            $table->unsignedInteger('tipo_pago_id')->nullable();
            $table->foreign('tipo_pago_id')->references('id')->on('tipo_pagos')->onDelete('cascade');
            $table->unsignedDecimal('efectivo', 15, 2)->nullable()->default(0.00);
            $table->unsignedDecimal('importe', 15, 2)->nullable()->default(0.00);
            $table->unsignedInteger('user_id')->nullable();
            $table->enum('estado', ['ACTIVO', 'ANULADO'])->default('ACTIVO');
            $table->enum('estado_pago', ['PAGADA', 'PENDIENTE', 'ADELANTO', 'CONCRETADA', 'VIGENTE', 'DEVUELTO'])->default('PENDIENTE');
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
        Schema::dropIfExists('ordens');
    }
}
