<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // ✅ À AJOUTER

class TimeSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('time_slots')->insert([
    [
        'code' => 'AM',
        'label' => 'Demi-journée matin',
        'start_time' => '07:00',
        'end_time' => '13:00',
        'order_index' => 1
    ],
    [
        'code' => 'PM',
        'label' => 'Demi-journée après-midi',
        'start_time' => '13:00',
        'end_time' => '18:00',
        'order_index' => 2
    ],
    [
        'code' => 'FULL_DAY',
        'label' => 'Journée complète',
        'start_time' => '07:00',
        'end_time' => '18:00',
        'order_index' => 3
    ],
]);

    }
}
