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
                'original_life' => 500,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Fenix',
                'photo' => 'storage/Fenix.jpg',
                'original_life' => 750,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Leviathan',
                'photo' => 'storage/Leviathan.jpg',
                'original_life' => 1000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Minotaur',
                'photo' => 'storage/Minotaur.jpg',
                'original_life' => 600,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Griffin',
                'photo' => 'storage/Antidote.jpg',
                'original_life' => 650,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
