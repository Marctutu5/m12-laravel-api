<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FissurialsAttacksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fissurialsIds = range(1, 5); // Suponiendo que los ID de Fissurials son 1-5
        $attackIds = range(1, 20); // IDs de los ataques disponibles

        foreach ($fissurialsIds as $fissurialId) {
            shuffle($attackIds); // Mezclar para obtener aleatoriedad
            $selectedAttacks = array_slice($attackIds, 0, 4); // Seleccionar los primeros 4 ataques

            foreach ($selectedAttacks as $attackId) {
                DB::table('fissurials_attacks')->insert([
                    'fissurial_id' => $fissurialId,
                    'attack_id' => $attackId
                ]);
            }
        }
    }
}
