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
            'name' => 'Hyper',
            'photo' => 'storage/HyperPotion.jpg',
        ]);

        Item::create([
            'name' => 'Maximum',
            'photo' => 'storage/MaximumPotion.jpg',
        ]);

        Item::create([
            'name' => 'Super',
            'photo' => 'storage/SuperPotion.jpg',
        ]);

        Item::create([
            'name' => 'Book',
            'photo' => 'storage/book.jpg',
        ]);

        Item::create([
            'name' => 'Key',
            'photo' => 'storage/key.jpg',
        ]);

        Item::create([
            'name' => 'Bicycle',
            'photo' => 'storage/bicycle.jpg',
        ]);

        // Añade más objetos según tus necesidades
    }
}
