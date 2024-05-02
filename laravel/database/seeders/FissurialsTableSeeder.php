<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FissurialsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('fissurials')->insert([
            [
                'name' => 'Drago',
                'photo' => 'path/to/drago.jpg',
                'original_life' => 100,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Fenix',
                'photo' => 'path/to/fenix.jpg',
                'original_life' => 150,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Leviathan',
                'photo' => 'path/to/leviathan.jpg',
                'original_life' => 200,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Minotaur',
                'photo' => 'path/to/minotaur.jpg',
                'original_life' => 120,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Griffin',
                'photo' => 'path/to/griffin.jpg',
                'original_life' => 130,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
