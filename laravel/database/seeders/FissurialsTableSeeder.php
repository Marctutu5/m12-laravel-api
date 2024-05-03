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
                'photo' => 'storage/Drago.jpg',
                'original_life' => 5000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Fenix',
                'photo' => 'storage/Fenix.jpg',
                'original_life' => 7500,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Leviathan',
                'photo' => 'storage/Leviathan.jpg',
                'original_life' => 10000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Minotaur',
                'photo' => 'storage/Minotaur.jpg',
                'original_life' => 6000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Griffin',
                'photo' => 'storage/Antidote.jpg',
                'original_life' => 6500,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
