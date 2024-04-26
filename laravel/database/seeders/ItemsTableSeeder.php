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
            'name' => 'Antidote',
            'photo' => 'storage/Antidote.jpg',
        ]);

        Item::create([
            'name' => 'Baya',
            'photo' => 'storage/Baya.jpg',
        ]);

        Item::create([
            'name' => 'Hyper Potion',
            'photo' => 'storage/HyperPotion.jpg',
        ]);

        Item::create([
            'name' => 'Maximum Potion',
            'photo' => 'storage/MaximumPotion.jpg',
        ]);

        Item::create([
            'name' => 'Super Potion',
            'photo' => 'storage/SuperPotion.jpg',
        ]);

        // Añade más objetos según tus necesidades
    }
}
