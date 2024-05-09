<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'Clientes',
            'slug' => 'menu.clientes',
            'description' => 'Mantenedor de clientes',
            'img' => 'assets/images/icon_clients.png',
        ]);

        Permission::create([
            'name' => 'Servicios',
            'slug' => 'menu.servicios',
            'description' => 'Mantenedor de servicios',
            'img' => 'assets/images/icon_servicios.png',
        ]);

        Permission::create([
            'name' => 'Orden de servicio',
            'slug' => 'menu.orden_servicio',
            'description' => 'Mantenedor de Ordenes de servicio',
            'img' => 'assets/images/orden_servicio.png',
        ]);

        Permission::create([
            'name' => 'Registrar Pagos',
            'slug' => 'menu.registrar_pagos',
            'description' => 'Mantenedor de Registrar pagos',
            'img' => 'assets/images/icon_add_money.png',
        ]);

        Permission::create([
            'name' => 'Reportes',
            'slug' => 'menu.reportes',
            'description' => 'Reportes',
            'img' => 'assets/images/icon_reporte.png',
        ]);

        Permission::create([
            'name' => 'Usuarios',
            'slug' => 'menu.usuarios',
            'description' => 'Mantenedor de Usuarios',
            'img' => 'assets/images/icon_usuarios.png',
        ]);

        Permission::create([
            'name' => 'Roles',
            'slug' => 'menu.roles',
            'description' => 'Mantenedor de Roles',
            'img' => 'assets/images/icon_roles.png',
        ]);

        Permission::create([
            'name' => 'Perfil',
            'slug' => 'menu.perfil',
            'description' => 'Mantenedor de perfil',
            'img' => 'assets/images/icon_perfiles.png',
        ]);
    }
}
