<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // ✅ À AJOUTER

class PricingProfileSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pricing_profiles')->insert([
            ['code' => 'ABONNE', 'label' => 'Abonné'],
            ['code' => 'ADHERENT', 'label' => 'Adhérent'],
            ['code' => 'NON_ADHERENT', 'label' => 'Non-adhérent'],
        ]);
    }
}
