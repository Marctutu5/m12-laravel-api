<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MapItem;

class MapItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MapItem::create([
            'item_id' => 1,
            'x' => 264,
            'y' => 472,
            'scene' => 1,
        ]);

        MapItem::create([
            'item_id' => 2,
            'x' => 408,
            'y' => 120,
            'scene' => 1,
        ]);

        MapItem::create([
            'item_id' => 3,
            'x' => 504,
            'y' => 312,
            'scene' => 1,
        ]);

        MapItem::create([
            'item_id' => 4,
            'x' => 584,
            'y' => 456,
            'scene' => 1,
        ]);

        MapItem::create([
            'item_id' => 5,
            'x' => 200,
            'y' => 152,
            'scene' => 1,
        ]);
    }
}
