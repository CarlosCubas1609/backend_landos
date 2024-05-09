<?php

namespace Database\Seeders;

use App\Models\Empresa;
use Illuminate\Database\Seeder;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $empresa = new Empresa();
        $empresa->ruc = '12345678912';
        $empresa->razon_social = 'LANDOS';
        $empresa->razon_social_abreviada = 'LANDOS';
        $empresa->direccion_fiscal = 'AV ESPAÑA 1319';
        $empresa->direccion_llegada = 'TRUJILLO';
        $empresa->dni_representante = '1234578';
        $empresa->nombre_representante = 'NOMBRE APELLIDOPAT APELLIDOMAT';
        $empresa->num_asiento = 'A00001';
        $empresa->ubigeo = '130102';
        $empresa->num_partida = '11036086';
        $empresa->estado_ruc = 'ACTIVO';
        $empresa->estado_fe = '1';
        $empresa->save();
    }
}
