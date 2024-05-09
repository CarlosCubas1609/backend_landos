<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userAdmin = User::create([
            'name' => "Administrador",
            'email' => "admin@landos.com",
            'password' => Hash::make('12345678'),
            'contra' => '12345678',
        ]);

        $roleAdmin = Role::create([
            'name' => 'Administrador',
            'slug' => 'admin',
            'description' => 'Administrador',
            'full_access' => 'SI'
        ]);

        $userAdmin->roles()->sync([$roleAdmin->id]);
    }
}
