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
            'photo' => 'storage/app/public/Antidote.jpg',
        ]);

        Item::create([
            'name' => 'Baya',
            'photo' => 'storage/app/public/Baya.jpg',
        ]);

        Item::create([
            'name' => 'Hyper Potion',
            'photo' => 'storage/app/public/HyperPotion.jpg',
        ]);

        Item::create([
            'name' => 'Maximum Potion',
            'photo' => 'storage/app/public/MaximumPotion.jpg',
        ]);

        Item::create([
            'name' => 'Super Potion',
            'photo' => 'storage/app/public/SuperPotion.jpg',
        ]);

        // Añade más objetos según tus necesidades
    }
}
