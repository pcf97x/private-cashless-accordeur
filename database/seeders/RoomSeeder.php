<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // ✅ À AJOUTER

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('rooms')->insert([
    ['name' => 'Salle 1', 'capacity' => 8],
    ['name' => 'Salle 2', 'capacity' => 12],
    ['name' => 'Salle 3', 'capacity' => 20],
    ['name' => 'Salle 4', 'capacity' => 6],
    ['name' => 'Salle 5', 'capacity' => 10],
    ['name' => 'Salle événementielle', 'capacity' => 50],
]);

    }
}
