<?php

namespace Database\Seeders;

use App\Models\TipoPago;
use Illuminate\Database\Seeder;

class TipoPagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoPago::create([
            'descripcion' => 'EFECTIVO',
            'simbolo' => 'EFECTIVO',
            'editable' => '1'
        ]);
        
        TipoPago::create([
            'descripcion' => 'TRANSFERENCIA',
            'simbolo' => 'TRANSFERENCIA',
            'editable' => '1'
        ]);

        TipoPago::create([
            'descripcion' => 'YAPE',
            'simbolo' => 'YAPE',
            'editable' => '1'
        ]);

        TipoPago::create([
            'descripcion' => 'PLIN',
            'simbolo' => 'PLIN',
            'editable' => '1'
        ]);

        TipoPago::create([
            'descripcion' => 'POS',
            'simbolo' => 'POS',
            'editable' => '1'
        ]);
    }
}
