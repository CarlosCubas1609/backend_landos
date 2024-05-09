<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Genero;
use App\Models\TipoServicio;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GeneroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Genero::create([
            'descripcion' => 'Femenino',
            'abreviatura' => 'F',
        ]);

        Genero::create([
            'descripcion' => 'Masculino',
            'abreviatura' => 'M',
        ]);

        TipoServicio::create([
            'descripcion' => 'Tipo servicio 1'
        ]);
    }
}
