<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
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
        $admin = new User([
            'name'      => 'Admin', // Nombre del administrador
            'email'     => 'admin@admin.com', // Correo electrónico del administrador
            'password'  => Hash::make('123456789'), // Contraseña del administrador
            'role_id'   => Role::ADMIN // El ID del rol de administrador, asumiendo que Role::ADMIN es un valor constante que representa el ID del rol de administrador en tu modelo Role.
        ]);
        $admin->save();
    }
}
