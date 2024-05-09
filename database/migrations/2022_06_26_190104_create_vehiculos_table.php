<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string("placa");
            $table->string("color");
            $table->string("modelo");
            $table->string("marca")->nullable();
            $table->string("url_foto_placa")->nullable();
            $table->foreignId('cliente_id')->nullable()->constrained()->onDelete('SET NULL');
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
        Schema::dropIfExists('vehiculos');
    }
}
