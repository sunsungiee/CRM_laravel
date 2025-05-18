<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Priority;
class PrioritiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Priority::create([
            'priority' => 'Высокий'
        ]);

        Priority::create([
            'priority' => 'Средний',
        ]);

        Priority::create([
            'priority' => 'Низкий',
        ]);
    }
}
