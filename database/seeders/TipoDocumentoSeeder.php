<?php

namespace Database\Seeders;

use App\Models\TipoDocumento;
use Illuminate\Database\Seeder;

class TipoDocumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoDocumento::create([
            'descripcion' => 'DNI',
            'abreviatura' => 'DNI',
        ]);

        TipoDocumento::create([
            'descripcion' => 'RUC',
            'abreviatura' => 'RUC',
        ]);
    }
}
