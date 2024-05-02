<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttacksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attacks = [
            ['name' => 'Fire Blast', 'power' => 120],
            ['name' => 'Thunder Shock', 'power' => 90],
            ['name' => 'Ice Beam', 'power' => 110],
            ['name' => 'Earthquake', 'power' => 100],
            ['name' => 'Hydro Pump', 'power' => 150],
            ['name' => 'Solar Beam', 'power' => 120],
            ['name' => 'Shadow Ball', 'power' => 80],
            ['name' => 'Rock Slide', 'power' => 95],
            ['name' => 'Quick Attack', 'power' => 40],
            ['name' => 'Iron Tail', 'power' => 100],
            ['name' => 'Poison Jab', 'power' => 80],
            ['name' => 'Aerial Ace', 'power' => 60],
            ['name' => 'Flame Thrower', 'power' => 110],
            ['name' => 'Surf', 'power' => 95],
            ['name' => 'Thunderbolt', 'power' => 90],
            ['name' => 'Blizzard', 'power' => 120],
            ['name' => 'Psychic', 'power' => 90],
            ['name' => 'Double Edge', 'power' => 100],
            ['name' => 'Fury Swipes', 'power' => 45],
            ['name' => 'Cross Chop', 'power' => 100]
        ];

        DB::table('attacks')->insert($attacks);
    }
}
