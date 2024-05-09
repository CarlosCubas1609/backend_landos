<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(EmpresaSeeder::class);
        $this->call(GeneroSeeder::class);
        $this->call(TipoDocumentoSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(TipoPagoSeeder::class);
        // \App\Models\User::factory(10)->create();
    }
}
