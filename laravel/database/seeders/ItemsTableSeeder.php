<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear algunos objetos
        Item::create([
            'name' => 'Espada',
            'photo' => 'path/to/photo1.jpg',
        ]);

        Item::create([
            'name' => 'Poción de curación',
            'photo' => 'path/to/photo2.jpg',
        ]);

        // Añade más objetos según tus necesidades
    }
}
