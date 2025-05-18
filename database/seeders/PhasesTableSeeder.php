<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Phase;

class PhasesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Phase::create([
            'phase' => 'Новое'
        ]);

        Phase::create([
            'phase' => 'Уточнение деталей'
        ]);

        Phase::create([
            'phase' => 'Подтверждена'
        ]);

        Phase::create([
            'phase' => 'Совершена'
        ]);

        Phase::create([
            'phase' => 'Отменена'
        ]);
    }
}
