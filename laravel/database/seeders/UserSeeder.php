<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Wallet;
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

        // Crear una wallet para el usuario administrador con un saldo inicial
        $admin->wallet()->create([
            'coins' => 1000 // Saldo inicial de 1000 monedas
        ]);

        // Aquí puedes añadir más usuarios y sus respectivas wallets si es necesario
    }
}
